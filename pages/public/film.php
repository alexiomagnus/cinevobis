<?php
session_start();

require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Dotenv\Dotenv;
use Kiwilan\Tmdb\Tmdb;
use MongoDB\Client as MongoClient;
use JmesPath\Env;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$tmdb  = Tmdb::client($_ENV['API_KEY']);

// Connessione a MongoDB
$mongoClient = new MongoClient("mongodb://localhost:27017"); 
$db = $mongoClient->selectDatabase('cinevobis'); 
$collection = $db->selectCollection('films'); 

$movie_api = null;
$movie_db = null;
$errore = "";

$movie_id = $_GET['tmdb_id'] ?? null;

// 1. Recupero film da TMDB
if (!empty($movie_id)) {
    $results = $tmdb->raw()->url("/movie/{$movie_id}", [
        'language' => 'it-IT',
        'append_to_response' => 'credits'
    ]);

    $body = $results?->getBody();

    if ($body) {
        $movie_api = is_string($body) ? json_decode($body, true) : $body;
    }

    if (empty($movie_api)) {
        $errore = "Film non trovato su TMDB";
    }
} else {
    $errore = "Nessun film selezionato";
}

// 2. Controllo/inserimento in MongoDB
if (!empty($movie_api)) {
    $movie_db = $collection->findOne(
        ['id' => (int)$movie_id],
        ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
    );

    if ($movie_db === null) {
        $collection->insertOne($movie_api);
        $movie_db = $movie_api; 
    }
}

// ========== 3. Estrazione con JMESPath ==========
$subtitle = [];
if ($movie_db) {
    $titolo       = Env::search('title', $movie_db) ?? 'Titolo non disponibile';
    $titolo_orig  = Env::search('original_title', $movie_db) ?? '';
    $overview     = Env::search('overview', $movie_db) ?: 'Nessuna trama disponibile.';
    $poster_path  = Env::search('poster_path', $movie_db);
    $voto         = Env::search('vote_average', $movie_db);
    $durata       = Env::search('runtime', $movie_db);
    $generi       = Env::search('genres', $movie_db) ?? [];
    $cast         = Env::search('credits.cast[:10]', $movie_db) ?? [];

    // Anno: primi 4 caratteri di release_date
    $release_date = Env::search('release_date', $movie_db);
    $anno = !empty($release_date) ? substr($release_date, 0, 4) : '?';

    // Paese: primo paese di produzione in uppercase
    $paese_raw = Env::search('production_countries[0].name', $movie_db);
    $paese = !empty($paese_raw) ? strtoupper($paese_raw) : '?';

    // Regista: filtra crew per job "Director" e concatena i nomi
    $registi = Env::search('credits.crew[?job == `Director`].name', $movie_db) ?? [];
    $regista = implode(', ', $registi);

    // Sottotitolo
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
    <title><?= $movie_db ? htmlspecialchars($titolo) : 'Film' ?> - Cinevobis</title>
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

        <?php elseif ($movie_db): ?>
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