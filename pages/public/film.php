<?php
session_start();

require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/movie_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Dotenv\Dotenv;
use Kiwilan\Tmdb\Tmdb;
use MongoDB\Client;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$tmdb = Tmdb::client($_ENV['API_KEY']);

// Connessione a MongoDB
$mongoClient = new Client("mongodb://localhost:27017");
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
        'append_to_response' => 'credits,videos'
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


// 2. Controllo/inserimento o aggiornamento in MongoDB
if (!empty($movie_api)) {
    // Cercare il film nel DB
    $movie_db = $collection->findOne(
        ['id' => (int)$movie_id],
        ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
    );

    $aMonthInSeconds = 30 * 24 * 60 * 60; // 30 giorni
    
    // Se non esiste lo si inserisci
    if ($movie_db === null) {
        $movie_api['last_updated'] = new \MongoDB\BSON\UTCDateTime();  // Timestamp attuale in secondi
        $collection->insertOne($movie_api);
        $movie_db = $movie_api;

    } else {
        // Se esiste si recupera il timestamp 
        $lastUpdateSeconds = isset($movie_db['last_updated'])
            ? $movie_db['last_updated']->toDateTime()->getTimestamp()
            : 0;

        // Se è passato un mese si aggiorna il film
        if (($now - $lastUpdateSeconds) > $aMonthInSeconds) {
            $movie_api['last_updated'] = new \MongoDB\BSON\UTCDateTime();

            $collection->updateOne(
                ['id' => $movie_id],
                ['$set' => $movie_api]
            );

            $movie_db = $movie_api;
        }
    }
}


// 3. Estrazione dati
if ($movie_db) {
    $movieObj = new movieObj($movie_db);
    $data = $movieObj->toArray();

    $titolo = $data['titolo'];
    $titolo_orig = $data['titolo_orig'];

    $trama = $data['trama'];
    $poster_path = $data['poster_path'];

    $voto = $data['voto'];
    $trailerKey = $data['trailer_key'];

    $durata = $data['durata'];
    $anno = $data['anno'];

    $generi = $data['generi'];
    $paese = $data['paese'];

    $cast = $data['cast'];
    $registi = $data['registi'];
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
    <style>
        :root {
            --accent-color: #ffc107;
        }

        .text-justify {
            text-align: justify;
        }

        .cast-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        .btn-trailer-custom {
            background-color: #000;
            color: #fff;
            border: none;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .btn-trailer-custom:hover {
            background-color: #222;
            transform: scale(1.02);
            color: #ffc107;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container my-5 flex-grow-1">
        <?php if ($movie_db): ?>
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white">

                        <div class="row g-5 mb-5">
                            <div class="col-md-4">
                                <img src="https://image.tmdb.org/t/p/w500<?= $poster_path ?>" class="img-fluid rounded-4 shadow-sm w-100" alt="Poster">

                                <?php if ($trailerKey): ?>
                                    <div class="mt-3">
                                        <button type="button"
                                            class="btn btn-dark w-100 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#trailerModal">
                                            <i class="bi bi-play-fill fs-4 me-2"></i> Trailer
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-8">
                                <h1 class="fw-bold text-dark display-5 mb-1"><?= htmlspecialchars($titolo) ?></h1>
                                <?php if (!empty($titolo_orig) && strcasecmp(trim($titolo_orig), trim($titolo)) !== 0): ?>
                                    <p class="text-muted fs-5 mb-4"><?= htmlspecialchars($titolo_orig) ?></p>
                                <?php endif; ?>


                                <div class="mb-4">
                                    <small class="text-uppercase fw-bold text-muted d-block mb-1" style="letter-spacing:1px">Regia</small>
                                    <p class="fs-5 fw-medium mb-0">
                                        <?= htmlspecialchars(implode(', ', array_column($registi, 'name'))) ?>
                                    </p>
                                </div>


                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($generi as $genre): ?>
                                        <span class="badge bg-white text-dark border rounded-pill px-3 py-2"><?= htmlspecialchars($genre['name']) ?></span>
                                    <?php endforeach; ?>
                                </div>

                                <div class="border-top pt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="fw-bold m-0">Trama</h4>
                                        <div class="d-flex align-items-center fs-4 fw-bold">
                                            <i class="bi bi-star-fill text-warning me-2"></i>
                                            <span><?= number_format($voto, 1) ?> <small class="text-muted fw-normal fs-6">/ 10</small></span>
                                        </div>
                                    </div>
                                    <!--- Mandare a capo <br> --->
                                    <p class="text-justify lh-lg text-dark fs-6"><?= nl2br(htmlspecialchars($trama)) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row text-center py-4 bg-light rounded-4 mb-5 border mx-0">
                            <div class="col-4 border-end">
                                <div class="small text-muted text-uppercase fw-bold">Durata</div>
                                <div class="fw-bold"><?= $durata ?> min</div>
                            </div>
                            <div class="col-4 border-end">
                                <div class="small text-muted text-uppercase fw-bold">Anno</div>
                                <div class="fw-bold"><?= $anno ?></div>
                            </div>
                            <div class="col-4">
                                <div class="small text-muted text-uppercase fw-bold">Paese</div>
                                <div class="fw-bold"><?= $paese ?></div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <h4 class="fw-bold mb-4">Cast Principale</h4>
                            <div class="row g-3">
                                <?php foreach ($cast as $actor):
                                    $profile = $actor['profile_path']
                                        ? "https://image.tmdb.org/t/p/w185" . $actor['profile_path']
                                        : "https://ui-avatars.com/api/?name=" . urlencode($actor['name']) . "&background=random";
                                ?>
                                    <div class="col-12 col-sm-6 col-lg-4">
                                        <div class="d-flex align-items-center p-2 border rounded-3 bg-light shadow-sm transition-hover">
                                            <img src="<?= $profile ?>"
                                                class="cast-avatar rounded-circle border border-2 border-white shadow-sm me-3"
                                                alt="<?= htmlspecialchars($actor['name']) ?>">
                                            <div class="overflow-hidden">
                                                <p class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.9rem;">
                                                    <?= htmlspecialchars($actor['name']) ?>
                                                </p>
                                                <p class="mb-0 text-muted text-truncate" style="font-size: 0.8rem;">
                                                    <?= htmlspecialchars($actor['character']) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="trailerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content bg-transparent border-0">
                        <div class="d-flex justify-content-end mb-4">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-0">
                            <div class="ratio ratio-16x9 shadow-lg rounded-4 overflow-hidden">
                                <iframe id="trailerVideo"
                                    data-src="https://www.youtube.com/embed/<?= $trailerKey ?>?rel=0&autoplay=1"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="alert alert-warning shadow-sm rounded-4"><?= htmlspecialchars($errore) ?></div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>