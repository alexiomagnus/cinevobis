<?php
session_start();

require_once(__DIR__ . '/config/config.php');
require_once(__DIR__ . '/config/connection.php');
require_once(__DIR__ . '/includes/header_logic.php');
require_once(__DIR__ . '/vendor/autoload.php');

use MongoDB\Client;

$nome = $_SESSION['nome'] ?? '';

// Connessione a MongoDB e recupero di 5 film
$films = [];
try {
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

    // Recupera 5 film
    $cursor = $collection->find([], [
        'limit' => 5,
        'sort' => ['_id' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
    ]);
    
    $films = iterator_to_array($cursor);
} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include("includes/header.php"); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <div class="container">
            <h1 class="fw-bold mb-4">Ciao <?= htmlspecialchars($nome) ?></h1>
            
            <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
                <h3 class="fw-bold m-0">Film in Evidenza</h3>
            </div>
            
            <?php if (empty($films)): ?>
                <div class="alert alert-info shadow-sm rounded-4 border-0">
                    <i class="bi bi-info-circle me-2"></i>Nessun film trovato nel database.
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
                    <?php foreach ($films as $film): 
                        $id = $film['id'] ?? '';
                        $titolo = $film['title'] ?? 'Titolo non disponibile';
                        $poster = !empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : "https://via.placeholder.com/500x750?text=No+Poster";
                        $voto = $film['vote_average'] ?? 0;
                        $anno = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                    ?>
                    <div class="col">
                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                                <div class="position-relative">
                                    <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" style="object-fit: cover; aspect-ratio: 2/3;">
                                    <?php if ($voto > 0): ?>
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-warning text-dark rounded-pill shadow-sm d-flex align-items-center gap-1">
                                            <i class="bi bi-star-fill" style="font-size: 0.75rem;"></i> <?= number_format($voto, 1) ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body p-3 d-flex flex-column bg-white">
                                    <h6 class="card-title fw-bold text-truncate mb-1" title="<?= htmlspecialchars($titolo) ?>"><?= htmlspecialchars($titolo) ?></h6>
                                    <div class="mt-auto">
                                        <small class="text-muted fw-medium"><?= htmlspecialchars($anno) ?></small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php require_once('includes/footer.php'); ?>

    <!-- Per Tom Select -->
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>