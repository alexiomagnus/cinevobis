<?php
session_start();

require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Dotenv\Dotenv;
use Kiwilan\Tmdb\Tmdb;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$tmdb  = Tmdb::client($_ENV['API_KEY']);
$movie = null;
$errore = "";

$movie_id = $_GET['tmdb_id'] ?? null;

if (!empty($movie_id)) {
    $results = $tmdb->raw()->url("/movie/{$movie_id}", [
        'language'          => 'it-IT',
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
    $titolo = $movie['title'] ?? 'Titolo non disponibile';
    $titolo_orig = $movie['original_title'] ?? '';
    $overview = !empty($movie['overview']) ? $movie['overview'] : 'Nessuna trama disponibile.';
    $poster_path = $movie['poster_path'] ?? null;
    $voto = $movie['vote_average'] ?? null;
    $durata = $movie['runtime'] ?? null;
    $generi = $movie['genres']  ?? [];
    $cast = array_slice($movie['credits']['cast'] ?? [], 0, 10);

    // Anno: prendi solo i primi 4 caratteri della data di uscita
    $anno = !empty($movie['release_date'])
        ? substr($movie['release_date'], 0, 4)
        : '?';

    // Paese: primo paese di produzione
    $paesi = $movie['production_countries'] ?? [];
    $paese = !empty($paesi) ? strtoupper($paesi[0]['name'] ?? '?') : '?';

    // Regista: filtra i membri del crew con job === 'Director'
    $regista = implode(', ', array_column(
        array_filter($movie['credits']['crew'] ?? [], fn($m) => ($m['job'] ?? '') === 'Director'),
        'name'
    ));

    // Sottotitolo: regista + titolo originale (se diverso)
    $subtitle = array_filter([
        $regista    ? 'Diretto da: ' . htmlspecialchars($regista) : '',
        $titolo_orig !== $titolo ? htmlspecialchars($titolo_orig) : '',
    ]);

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

        <?php if ($errore): ?>
            <div class="alert alert-danger text-center shadow-sm rounded-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= htmlspecialchars($errore) ?>
            </div>

        <?php elseif ($movie): ?>
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-11">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-5">

                            <!-- Poster + info principali -->
                            <div class="row g-4 mb-4">

                                <div class="col-md-3 text-center">
                                    <?php if ($poster_path): ?>
                                        <img
                                            src="https://image.tmdb.org/t/p/w342<?= $poster_path ?>"
                                            alt="<?= htmlspecialchars($titolo) ?>"
                                            class="img-fluid rounded-3 shadow-sm"
                                        >
                                    <?php else: ?>
                                        <div class="ratio ratio-2x3 rounded-3 bg-secondary-subtle d-flex align-items-center justify-content-center">
                                            <div class="text-center text-muted">
                                                <i class="bi bi-film fs-1"></i>
                                                <p class="small mt-2 mb-0">Poster non disponibile</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-9">
                                    <h1 class="fw-bold mb-1"><?= htmlspecialchars($titolo) ?></h1>

                                    <?php if (!empty($subtitle)): ?>
                                        <p class="text-muted fst-italic mb-3">
                                            <?= implode(' — ', $subtitle) ?>
                                        </p>
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

                            <!-- Metadati: durata, anno, paese -->
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

                            <!-- Cast -->
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
    <script src="/assets/js/script.js"></script>
</body>
</html>