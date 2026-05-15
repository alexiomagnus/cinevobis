<?php
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');


// Array per stampare i dati
$topFilms = [];

try {
    // Controllo per evitare errori se MongoDB è offline
    if (!$collection) {
        throw new \Exception("Connessione a MongoDB non disponibile.");
    }

    // Prende i migliori film ordinati per voto medio.
    $cursor = $collection->find([], [
        'limit' => 36,
        'sort' => ['vote_average' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
    ]);

    $topFilms = iterator_to_array($cursor);

} catch (\Throwable $e) { // \Throwable cattura sia Exception che Fatal Error
    error_log("Errore caricamentoi migliori film: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migliori - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include(__DIR__ . "/../../includes/header.php"); ?>
    
    <main class="container mt-5 mb-5 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0">I migliori Film</h2>
        </div>

        <?php if (empty($topFilms)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Nessun film trovato nel database.
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                <?php 
                /** @var array $film */
                foreach ($topFilms as $film):
                    $id     = $film['id'] ?? '';
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : "https://via.placeholder.com/500x750?text=No+Poster";
                    $anno   = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                ?>
                <div class="col">
                    <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                            <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" loading="lazy" style="object-fit: cover; aspect-ratio: 2/3;">
                            <div class="card-body p-2 d-flex flex-column bg-white">
                                <h6 class="card-title fw-bold text-truncate mb-1" style="font-size: 0.95rem;" title="<?= htmlspecialchars($titolo) ?>"><?= htmlspecialchars($titolo) ?></h6>
                                <div class="mt-auto">
                                    <small class="text-muted fw-medium" style="font-size: 0.85rem;"><?= htmlspecialchars($anno) ?></small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>