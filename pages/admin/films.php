<?php
session_start();

require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}

$cursor = [];

try {
    // Connessione a MongoDB
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

    // Recuperiamo i film (ordinati per data di aggiunta)
    $cursor = $collection->find([], [
        'sort' => ['last_updated' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
        ]); 
} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Film - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1 d-flex flex-column align-items-center">
        
        <div class="w-100" style="max-width: 650px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fs-4 fw-bold mb-0">Archivio Film</h1>
                <span class="badge bg-dark rounded-pill">MongoDB</span>
            </div>

            <div class="d-flex flex-column gap-3">
                <?php if (!empty($cursor)): ?>
                    <?php foreach($cursor as $movie): ?>
                        <a href="/pages/admin/film_db.php?tmdb_id=<?= urlencode($movie['id']) ?>" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-3 card-hover bg-white search-result-card">
                                <div class="card-body px-4 py-3 d-flex align-items-center gap-3">

                                    <?php if (!empty($movie['poster_path'])): ?>
                                        <img src="https://image.tmdb.org/t/p/w92<?= $movie['poster_path'] ?>" 
                                            alt="Poster" 
                                            class="rounded-2 flex-shrink-0"
                                            style="width: 48px; height: 72px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-2 flex-shrink-0 bg-secondary d-flex align-items-center justify-content-center"
                                            style="width: 48px; height: 72px;">
                                            <i class="bi bi-film text-white fs-5"></i>
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex-grow-1 overflow-hidden">
                                        <span class="fs-6 text-dark fw-medium d-block text-truncate">
                                            <?= htmlspecialchars($movie['title'] ?? 'Senza titolo') ?>
                                        </span>
                                        <small class="text-primary font-monospace" style="font-size: 0.75rem;">
                                            ID: <?= (string)$movie['_id'] ?>
                                        </small>
                                    </div>

                                    </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info text-center rounded-3 border-0 shadow-sm">
                        <i class="bi bi-info-circle me-2"></i> Nessun film trovato nel database
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>