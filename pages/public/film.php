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

// 3. Estrazione dati
if ($movie_db) {
    $titolo       = Env::search('title', $movie_db) ?? 'Titolo non disponibile';
    $titolo_orig  = Env::search('original_title', $movie_db) ?? '';
    $overview     = Env::search('overview', $movie_db) ?: 'Nessuna trama disponibile.';
    $poster_path  = Env::search('poster_path', $movie_db);
    $voto         = Env::search('vote_average', $movie_db);
    $durata       = Env::search('runtime', $movie_db);
    $generi       = Env::search('genres', $movie_db) ?? [];
    $cast         = Env::search('credits.cast[:12]', $movie_db) ?? [];

    $trailerKey = Env::search("videos.results[?type=='Trailer' && site=='YouTube'].key | [0]", $movie_db) 
                  ?? Env::search("videos.results[?type=='Trailer'].key | [0]", $movie_db);

    $release_date = Env::search('release_date', $movie_db);
    $anno = !empty($release_date) ? substr($release_date, 0, 4) : '?';

    $paese_raw = Env::search('production_countries[0].name', $movie_db);
    $paese = !empty($paese_raw) ? strtoupper($paese_raw) : '?';

    $registi = Env::search('credits.crew[?job == `Director`].name', $movie_db) ?? [];
    $regista = implode(', ', $registi);
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
        :root { --accent-color: #ffc107; }
        .film-card { border-radius: 1.5rem; border: none; }
        .overview-text { text-align: justify; line-height: 1.7; color: #333; }
        
        /* Voto */
        .vote-display { font-size: 1.3rem; font-weight: 900; display: flex; align-items: center; }
        .vote-display .bi-star-fill { color: var(--accent-color); margin-right: 8px; }

        /* Avatar Cast */
        .cast-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        /* Trailer Modale */
        .trailer-modal-content { background: transparent; border: none; }
        .trailer-video-container { border-radius: 1.5rem; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.4); }
        .btn-trailer { letter-spacing: 1px; font-weight: 600; transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .btn-trailer:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important; }
        .close-trailer { filter: drop-shadow(0 2px 4px rgba(0,0,0,0.8)); transform: scale(1.1); opacity: 0.85; transition: opacity 0.2s; }
        .close-trailer:hover { opacity: 1; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container my-5 flex-grow-1">
        <?php if ($movie_db): ?>
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card shadow-sm film-card p-4 p-md-5 bg-white">
                        
                        <div class="row g-5 mb-5">
                            <div class="col-md-4">
                                <img src="https://image.tmdb.org/t/p/w500<?= $poster_path ?>" class="img-fluid rounded-4 shadow-sm mb-3 w-100" alt="Poster">
                                <?php if ($trailerKey): ?>
                                    <button class="btn btn-dark btn-trailer w-100 py-3 rounded-4 shadow-sm d-flex justify-content-center align-items-center" data-bs-toggle="modal" data-bs-target="#trailerModal">
                                        <i class="bi bi-play-circle-fill me-2 fs-5"></i> Guarda Trailer
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-8">
                                <h1 class="fw-bold text-dark display-5 mb-1"><?= htmlspecialchars($titolo) ?></h1>
                                <?php if (!empty($titolo_orig) && strcasecmp(trim($titolo_orig), trim($titolo)) !== 0): ?>
                                    <p class="text-muted fs-5 mb-4"><?= htmlspecialchars($titolo_orig) ?></p>
                                <?php endif; ?>

                                <div class="mb-4">
                                    <small class="text-uppercase fw-bold text-muted" style="letter-spacing:1px">Regia</small>
                                    <p class="fs-5 fw-medium"><?= htmlspecialchars($regista) ?></p>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($generi as $genre): ?>
                                        <span class="badge bg-white text-dark border rounded-pill px-3 py-2"><?= htmlspecialchars($genre['name']) ?></span>
                                    <?php endforeach; ?>
                                </div>

                                <div class="border-top pt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="fw-bold m-0">Trama</h4>
                                        <div class="vote-display">
                                            <i class="bi bi-star-fill"></i>
                                            <span><?= number_format($voto, 1) ?> <small class="text-muted fw-normal" style="font-size:0.8rem">/ 10</small></span>
                                        </div>
                                    </div>
                                    <p class="overview-text fs-6"><?= nl2br(htmlspecialchars($overview)) ?></p>
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
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content trailer-modal-content">
                        <div class="modal-body p-0">
                            <div class="d-flex justify-content-start mb-3 ms-2">
                                <button type="button" class="btn border-0 p-0 text-white close-trailer d-flex align-items-center justify-content-center" style="transform: scale(1.2);" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="bi bi-x-lg fs-5" style="text-shadow: 0 2px 4px rgba(0,0,0,0.8);"></i>
                                </button>
                            </div>
                            <div class="ratio ratio-16x9 trailer-video-container">
                                <iframe id="trailerVideo" src="https://www.youtube.com/embed/<?= $trailerKey ?>?enablejsapi=1" allowfullscreen></iframe>
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
    <script>
        // Stop video quando chiudi il modale
        const m = document.getElementById('trailerModal');
        m && m.addEventListener('hidden.bs.modal', () => { 
            const f = document.getElementById('trailerVideo'); 
            const s = f.src; f.src = ''; f.src = s; 
        });
    </script>
</body>
</html>