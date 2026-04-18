<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Dotenv\Dotenv;
use Kiwilan\Tmdb\Tmdb;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$tmdb = Tmdb::client($_ENV['API_KEY']);

$errore = "";
$searched = "";

$movie = null;

$movie_id = $_GET['tmdb_id'] ?? null;

if (!empty($movie_id)) {
    // Estrazione dei dati grezzi
    $results = $tmdb->raw()->url("/movie/{$movie_id}", [
        'language' => 'it-IT',
        'append_to_response' => 'credits'
    ]);

    $movie = $results?->getBody();

    if (!$movie) {
        $errore = "Film non trovato su TMDB";
    }
} else {
    $errore = "Nessun film selezionato";
}

// Variabili estratte dall'array
if ($movie) {
    $titolo          = $movie['title'] ?? 'Titolo non disponibile';
    $titolo_orig     = $movie['original_title'] ?? '';
    $overview        = $movie['overview'] ?? 'Nessuna trama disponibile.';
    $poster_path     = $movie['poster_path'] ?? null;
    $voto            = $movie['vote_average'] ?? null;
    $durata          = $movie['runtime'] ?? null;
    $anno            = isset($movie['release_date']) && $movie['release_date'] !== ''
                        ? substr($movie['release_date'], 0, 4) : '?';                     // Prendere solo l'anno (i primi quattro caratteri)
    $generi          = $movie['genres'] ?? [];
    $paesi           = $movie['production_countries'] ?? [];
    $paese           = !empty($paesi) ? strtoupper($paesi[0]['name'] ?? '?') : '?';
    $cast            = array_slice($movie['credits']['cast'] ?? [], 0, 10);               // Prendere solo i primi 10 attori
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $movie ? htmlspecialchars($titolo) : 'Film' ?> - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">

        <div class="mx-auto mb-5 px-3" style="max-width: 650px;">
            <form action="search.php" method="GET" class="search-wrap d-flex w-100 mb-0">
                <input type="text" name="search" class="flex-grow-1" placeholder="Cerca un film..." autocomplete="off"
                    aria-label="Cerca" value="<?= htmlspecialchars($searched) ?>" autofocus>
                <button type="submit" class="btn btn-brand px-4">Cerca</button>
            </form>
        </div>

        <?php if ($errore): ?>
            <div class="alert alert-danger text-center shadow-sm rounded-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errore) ?>
            </div>

        <?php elseif ($movie): ?>
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-11">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-5">

                            <div class="row g-4 mb-4">

                                <?php if ($poster_path): ?>
                                <div class="col-md-3 text-center">
                                    <img
                                        src="https://image.tmdb.org/t/p/w342<?= $poster_path ?>"
                                        alt="<?= htmlspecialchars($titolo) ?>"
                                        class="img-fluid rounded-3 shadow-sm"
                                    >
                                </div>
                                <?php endif; ?>

                                <div class="col-md-<?= $poster_path ? '9' : '12' ?>">
                                    <h1 class="fw-bold mb-1"><?= htmlspecialchars($titolo) ?></h1>

                                    <?php if ($titolo_orig !== $titolo): ?>
                                        <p class="text-muted fst-italic mb-3"><?= htmlspecialchars($titolo_orig) ?></p>
                                    <?php endif; ?>

                                    <?php if (!empty($generi)): ?>
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <?php foreach ($generi as $genre): ?>
                                                <span class="badge rounded-pill badge-genre px-3 py-2">
                                                    <?= htmlspecialchars($genre['name']) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($voto): ?>
                                        <div class="mb-3">
                                            <i class="bi bi-star-fill icon-star me-1"></i>
                                            <span class="fw-semibold"><?= number_format($voto, 1) ?></span>
                                            <span class="text-muted small">/ 10</span>
                                        </div>
                                    <?php endif; ?>

                                    <h5 class="text-muted fw-semibold mb-2">Trama</h5>
                                    <p class="fs-6 text-secondary lh-lg">
                                        <?= nl2br(htmlspecialchars($overview)) ?>
                                    </p>
                                </div>
                            </div>

                            <hr class="text-muted opacity-25 mb-4">

                            <div class="row text-center text-md-start g-4 mb-4">
                                <div class="col-md-4">
                                    <div class="text-uppercase text-muted small fw-bold mb-1">Durata</div>
                                    <div class="fs-5 fw-medium">
                                        <i class="bi bi-clock icon-accent me-2"></i>
                                        <?= $durata ? $durata . ' min' : '?' ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-uppercase text-muted small fw-bold mb-1">Anno di uscita</div>
                                    <div class="fs-5 fw-medium">
                                        <i class="bi bi-calendar3 icon-accent me-2"></i>
                                        <?= htmlspecialchars($anno) ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-uppercase text-muted small fw-bold mb-1">Paese di produzione</div>
                                    <div class="fs-5 fw-medium">
                                        <i class="bi bi-globe icon-accent me-2"></i>
                                        <?= htmlspecialchars($paese) ?>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($cast)): ?>
                                <hr class="text-muted opacity-25 mb-4">
                                <h5 class="text-muted fw-semibold mb-3">Cast</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($cast as $actor): ?>
                                        <span class="badge badge-cast px-3 py-2 rounded-pill fs-6 fw-normal">
                                            <i class="bi bi-person-circle me-1 text-muted"></i>
                                            <?= htmlspecialchars($actor['name'] ?? 'Sconosciuto') ?>
                                            <?php if (!empty($actor['character'])): ?>
                                                <span class="text-muted fst-italic">
                                                    (<?= htmlspecialchars($actor['character']) ?>)
                                                </span>
                                            <?php endif; ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>