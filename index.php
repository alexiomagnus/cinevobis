<?php
/**
 * Homepage di Cinevobis. Recupera da MongoDB due liste di film:
 * - "Film in evidenza": gli ultimi 12 film aggiunti al catalogo (ordinati per data).
 * - "Migliori film": i 6 film con il voto medio più alto, da cui viene estratto
 *   il "Film della settimana" usando il numero della settimana ISO come seed deterministico.
 *
 * @note Interagisce con la collezione MongoDB: `films` (database: `cinevobis`).
 */
require_once(__DIR__ . '/config/config.php');
require_once(__DIR__ . '/config/connection.php');
require_once(__DIR__ . '/includes/header_logic.php');
require_once(__DIR__ . '/vendor/autoload.php');

use MongoDB\Client;

$nome = $_SESSION['nome'] ?? '';


// DIchiarazione variabili
$recommendedFilms = [];
$cursor = [];
$topFilms = [];

try {
    // Connessione a MongoDB
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');


    // I Film in evidenza
    $cursor = $collection->find([], [
        'limit' => 12,
        'sort' => ['release_date' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
    ]);

    $recommendedFilms = iterator_to_array($cursor);

    $cursor = $collection->find([], [
        'limit' => 6,
        'sort' => ['vote_average' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
    ]);


    // I migliori Film
    $topFilms = iterator_to_array($cursor);
    
    // Film della settimana: cambia ogni lunedì usando il numero della settimana come seed
    $weekSeed = (int)date('oW');  // anno ISO + numero settimana
    $index = $weekSeed % count($topFilms);
    $film = $topFilms[$index] ?? null;

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
            <?php if(isset($nome)): ?>
                <h1 class="fw-bold mb-4">Benvenuto <?= htmlspecialchars($nome) ?></h1>
            <?php else: ?>
                <h1 class="fw-bold mb-4">Benvenuto</h1>
            <?php endif; ?>

            <?php if (!empty($topFilms)):
                $id       = $film['id'] ?? '';
                $titolo   = $film['title'] ?? '';
                $anno     = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                $rating   = isset($film['vote_average']) ? number_format((float)$film['vote_average'], 1) : null;
                $overview = $film['overview'] ?? '';
                $bg       = !empty($film['backdrop_path'])
                    ? "https://image.tmdb.org/t/p/w1280" . $film['backdrop_path']
                    : (!empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : '');
            ?>
            <div class="position-relative rounded-4 overflow-hidden mb-5"
                 style="min-height: 420px; background: url('<?= htmlspecialchars($bg) ?>') center/cover no-repeat #1a1a1a;">
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
                <h3 class="fw-bold m-0">I Film in evidenza</h3>
            </div>

            <?php if (empty($recommendedFilms)): ?>
                <div class="alert alert-info shadow-sm rounded-4 border-0">
                    <i class="bi bi-info-circle me-2"></i>Nessun film trovato nel database.
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                    <?php foreach ($recommendedFilms as $film):
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
            </div>

            <?php if (empty($topFilms)): ?>
                <div class="alert alert-info shadow-sm rounded-4 border-0">
                    <i class="bi bi-info-circle me-2"></i>Nessun film trovato nel database.
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                    <?php foreach ($topFilms as $film):
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