<?php
// Home page Cinevobis: mostra film in evidenza e migliori film.
// Recupera i dati da MongoDB dalla collezione `films` del database `cinevobis`.
require_once(__DIR__ . '/config/config.php');
require_once(__DIR__ . '/config/connection.php');
require_once(__DIR__ . '/includes/header_logic.php');
require_once(__DIR__ . '/vendor/autoload.php');

use MongoDB\Client;

$nome = $_SESSION['nome'] ?? '';

// Prepara gli array di dati che verranno popolati dal database.
$collection = [];
$cursor = [];

try {
    // Connessione a MongoDB locale e selezione della collezione film.
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}

// Film in evidenza: ultimi film aggiunti.
$recommendedFilms = [];

try {
    $cursor = $collection->find([], [
        'limit' => 6,
        'sort' => ['release_date' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
    ]);

    $recommendedFilms = iterator_to_array($cursor);

    // Prende i migliori film ordinati per voto medio.
    $cursor = $collection->find([], [
        'limit' => 6,
        'sort' => ['vote_average' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
    ]);
    
} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
}

// Mappa i risultati dei migliori film e sceglie il film della settimana.
$topFilms = [];
$film = [];

try {
    $topFilms = iterator_to_array($cursor);
    
    // srand((int)date('oW'));
    srand(10);
    $film = $topFilms[array_rand($topFilms)] ?? null;

} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
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
            <?php if($nome != ''): ?>
                <h1 class="fw-bold mb-4">Benvenuto, <?= htmlspecialchars($nome) ?></h1>
            <?php else: ?>
                <h1 class="fw-bold mb-4">Benvenuto</h1>
            <?php endif; ?>

            <?php 
            /** @var array $film */
            if (!empty($topFilms)):
                $id = $film['id'] ?? '';
                
                $titolo = $film['title'] ?? '';
                $anno = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';  // Restituisce parte di una stringa
                
                $rating = isset($film['vote_average']) ? number_format((float)$film['vote_average'], 1) : null;
                $overview = $film['overview'] ?? '';

                $background = '';

                // Controlliamo se c'è un background
                if (!empty($film['backdrop_path'])) {
                    
                    $background = "https://image.tmdb.org/t/p/w1280" . $film['backdrop_path'];

                // Se non c'è controlliamo il poster
                } elseif (!empty($film['poster_path'])) {
                    
                    $background = "https://image.tmdb.org/t/p/w500" . $film['poster_path'];

                // Se non c'è né il background né il poster
                } else {
                    $background = ''; 
                }
            ?>

            <div class="position-relative rounded-4 overflow-hidden mb-5"
                 style="min-height: 420px; background: url('<?= htmlspecialchars($background) ?>') center/cover no-repeat #1a1a1a;">
                <div class="position-absolute top-0 start-0 w-100 h-100"
                     style="background: linear-gradient(to right, rgba(0,0,0,.85) 0%, rgba(0,0,0,.4) 60%, transparent 100%);"></div>
                <div class="position-relative d-flex align-items-end h-100 p-4 p-md-5" style="min-height: 420px;">
                    <div style="max-width: 500px;">
                        <div class="mb-2 d-flex align-items-center gap-2">
                            <span class="badge bg-white bg-opacity-25 text-white border border-white border-opacity-25 fw-semibold">Film della settimana</span>
                            <?php if ($anno): ?>
                                <span class="text-white-50 small"><?= htmlspecialchars($anno) ?></span>
                            <?php endif; ?>
                        </div>
                        <h2 class="fw-bold text-white mb-2" style="font-size: clamp(1.6rem, 3.5vw, 2.4rem);">
                            <?= htmlspecialchars($titolo) ?>
                        </h2>
                        <?php if ($rating): ?>
                            <p class="text-white fw-semibold mb-2">
                                <i class="bi bi-star-fill text-warning me-1"></i><?= $rating ?> <span class="text-white-50">/ 10</span>
                            </p>
                        <?php endif; ?>
                        <?php if ($overview): ?>
                            <p class="text-white-50 small mb-3 d-none d-md-block hero-overview">
                                <?= htmlspecialchars($overview) ?>
                            </p>
                        <?php endif; ?>
                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>"
                           class="btn btn-light fw-bold rounded-pill px-4">Scopri di più
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>


           <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
                <h3 class="fw-bold m-0">Esplora</h3>
            </div>

           <div class="row g-4">
                <div class="col-12 col-md-6">
                    <a href="/pages/public/genres.php" class="text-decoration-none d-block h-100">
                        <div class="card transition-hover h-100">
                            <div class="card-body d-flex align-items-center justify-content-between p-4">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-grid-fill fs-2" style="color: var(--accent);"></i>
                                    <div>
                                        <div class="fw-bold" style="font-size: 1rem;">Generi</div>
                                        <div class="text-muted" style="font-size: 0.85rem;">Esplora il catalogo per categoria</div>
                                    </div>
                                </div>
                                <i class="bi bi-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-6">
                    <a href="/pages/public/notice_board.php" class="text-decoration-none d-block h-100">
                        <div class="card transition-hover h-100">
                            <div class="card-body d-flex align-items-center justify-content-between p-4">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-journal-text fs-2 text-warning"></i>
                                    <div>
                                        <div class="fw-bold" style="font-size: 1rem;">Bacheca</div>
                                        <div class="text-muted" style="font-size: 0.85rem;">Le ultime recensioni della community</div>
                                    </div>
                                </div>
                                <i class="bi bi-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
                <h3 class="fw-bold m-0">I Film in evidenza</h3>
                <a href="/pages/public/recommended_films.php" class="text-decoration-none fw-semibold" style="color: var(--accent);">Vedi tutti <i class="bi bi-arrow-right"></i></a>
            </div>

            <?php if (empty($recommendedFilms)): ?>
                <div class="alert alert-info shadow-sm rounded-4 border-0">
                    <i class="bi bi-info-circle me-2"></i>Nessun film trovato nel database.
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                    <?php 
                    /** @var array $film */
                    foreach ($recommendedFilms as $film):
                        $id     = $film['id'] ?? '';
                        $titolo = $film['title'] ?? 'Titolo non disponibile';
                        $poster = !empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : "https://via.placeholder.com/500x750?text=No+Poster";
                        $anno   = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                    ?>
                    <div class="col">
                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                                <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" style="object-fit: cover; aspect-ratio: 2/3;">
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

            <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
                <h3 class="fw-bold m-0">I migliori Film</h3>
                <a href="/pages/public/top_films.php" class="text-decoration-none fw-semibold" style="color: var(--accent);">Vedi tutti <i class="bi bi-arrow-right"></i></a>
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

        </div>
    </main>

    <?php require_once('includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>