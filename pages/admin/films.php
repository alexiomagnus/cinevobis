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


$mongoClient = null;
$db = null;
$collection = [];

// Connessione a MongoDB
try {
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

} catch (PDOException $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}


$cursor = [];

// Recuperiamo i film (ordinati per data di aggiunta)
try {
    $cursor = $collection->find([], [
        'sort' => ['last_updated' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
        ]); 

} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}


// Eliminazione film
if (isset($_POST['delete'])) {
    $id = $_POST['_id'] ?? '';

    try {
        $objectId = new MongoDB\BSON\ObjectId($id);
        $collection->deleteOne(['_id' => $objectId]);

        header("Location: films.php");
        exit();

    } catch (Exception $e) {
        error_log("Errore MongoDB: " . $e->getMessage());
    }
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
    <style>
        /* Bootstrap non ha row-cols-xl-10 di default, lo aggiungiamo */
        @media (min-width: 1200px) {
            .row-cols-xl-10 > * {
                flex: 0 0 auto;
                width: 10%;
            }
        }
        @media (min-width: 992px) {
            .row-cols-lg-8 > * {
                flex: 0 0 auto;
                width: 12.5%;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fs-4 fw-bold mb-0">Archivio Film</h1>
        </div>

        <?php if (!empty($cursor)): ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 row-cols-lg-8 row-cols-xl-10 g-2">
                <?php foreach($cursor as $movie): 
                    $titolo = $movie['title'] ?? 'Senza titolo';
                    $anno = !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '';
                    $poster = !empty($movie['poster_path'])
                        ? "https://image.tmdb.org/t/p/w185" . $movie['poster_path']
                        : null;
                ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden transition-hover">
                        <a href="/pages/admin/film_db.php?tmdb_id=<?= urlencode($movie['id']) ?>" class="text-decoration-none text-dark d-block">
                            <?php if ($poster): ?>
                                <img src="<?= $poster ?>" 
                                     alt="<?= htmlspecialchars($titolo) ?>" 
                                     class="card-img-top w-100"
                                     style="object-fit: cover; aspect-ratio: 2/3;">
                            <?php else: ?>
                                <div class="bg-secondary d-flex align-items-center justify-content-center w-100" style="aspect-ratio: 2/3;">
                                    <i class="bi bi-film text-white fs-4"></i>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="card-body p-1">
                            <h6 class="card-title mb-1 text-truncate" style="font-size: 0.75rem;" title="<?= htmlspecialchars($titolo) ?>">
                                <?= htmlspecialchars($titolo) ?>
                            </h6>
                            
                            <form method="POST" class="mt-0">
                                <input type="hidden" name="_id" value="<?= (string)$movie['_id'] ?>">
                                <button type="submit" name="delete"
                                        class="btn btn-link p-0 text-danger"
                                        style="font-size: 0.8rem;"
                                        title="Elimina"
                                        onclick="return confirm('Sei sicuro di voler eliminare questo film?')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center rounded-3 border-0 shadow-sm">
                <i class="bi bi-info-circle me-2"></i> Nessun film trovato nel database
            </div>
        <?php endif; ?>

    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>