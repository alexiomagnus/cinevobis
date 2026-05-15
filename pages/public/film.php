<?php
// Pagina pubblica di dettaglio film: recupera dati TMDB, salva/aggiorna MongoDB
// e gestisce le azioni utente su preferiti, watchlist e watched.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/movie_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Dotenv\Dotenv;
use Kiwilan\Tmdb\Tmdb;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$tmdb = Tmdb::client($_ENV['API_KEY']);


// Dichiarazione variabili
$movie_api = null;
$movie_db = null;
$errore = "";
$movie_id = $_GET['tmdb_id'] ?? null;


// 1. Recupero film da TMDB
if (!empty($movie_id)) {
    $results = $tmdb->raw()->url("/movie/{$movie_id}", [
        'language'           => 'it-IT',
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

    // Controllo per evitare errori se MongoDB è offline
    if (!$collection) {
        throw new \Exception("Connessione a MongoDB non disponibile.");
    }
    
    $now = time();
    $aMonthInSeconds  = 30 * 24 * 60 * 60;  // Un mese in secondi

    $movie_db = $collection->findOne(
        ['id' => (int)$movie_id]
    );

    if ($movie_db === null) {
        $movie_api['last_updated'] = new \MongoDB\BSON\UTCDateTime();
        $collection->insertOne($movie_api);
        $movie_db = $movie_api;
    } else {
        $lastUpdateSeconds = isset($movie_db['last_updated'])
            ? $movie_db['last_updated']->toDateTime()->getTimestamp()
            : 0;

        if (($now - $lastUpdateSeconds) > $aMonthInSeconds) {
            $movie_api['last_updated'] = new \MongoDB\BSON\UTCDateTime();
            $collection->updateOne(['id' => $movie_id], ['$set' => $movie_api]);
            $movie_db = $movie_api;
        }
    }
}


// 3. Estrazione dati dal film
$titolo = $titolo_orig = $trama = $poster_path = $trailerKey = $paese = '';
$voto = 0;
$durata = $anno = '';
$generi = $cast = $registi = [];

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


// 4. Gestione liste utente tramite userObj
$tmdb_id = $movie_db['id'] ?? null;
$id_utente = $_SESSION['id_utente'] ?? null;

$is_favorite = false;
$is_review = false;
$is_watchlist = false;
$is_watched = false;

if ($tmdb_id !== null && $id_utente !== null) {
    $userObj = new userObj($conn, $_SESSION['username']);

    try {
        // Gestione POST preferiti
        if (isset($_POST['favorite'])) $userObj->addFavorite((int)$tmdb_id, $id_utente);
        if (isset($_POST['delete_favorite'])) $userObj->removeFavorite((int)$tmdb_id, $id_utente);

        // Gestione POST watchlist
        if (isset($_POST['watchlist'])) $userObj->addWatchlist((int)$tmdb_id, $id_utente);
        if (isset($_POST['delete_watchlist'])) $userObj->removeWatchlist((int)$tmdb_id, $id_utente);

        // Gestione POST watched
        if (isset($_POST['watched'])) $userObj->addWatched((int)$tmdb_id, $id_utente);
        if (isset($_POST['delete_watched'])) $userObj->removeWatched((int)$tmdb_id, $id_utente);

        // Stato corrente (DOPO aver gestito i POST)
        $is_favorite = $userObj->isFavorite((int)$tmdb_id, $id_utente);
        $is_watchlist = $userObj->isInWatchlist((int)$tmdb_id, $id_utente);
        $is_watched = $userObj->isWatched((int)$tmdb_id, $id_utente);
        $is_review = $userObj->hasReview((int)$tmdb_id, $id_utente);

        // Se ha una recensione, il film è implicitamente "visto"
        if ($is_review) $is_watched = true;

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
        $errore = "Errore nell'aggiornamento delle liste";
    }
}


// Conteggio recensioni della community
$recensioni_altri = 0;
if ($tmdb_id !== null) {
    try {
        $userObj = new userObj($conn);
        $recensioni_altri = $userObj->countReviews((int)$tmdb_id);
    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
    }
}


// Siti per vedere il film
$links = [    
    'VidSrc' => 'https://vsembed.ru//embed/movie?tmdb=',
    'VixSrc' => 'https://vixsrc.to/movie/'
];
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
        .cast-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
        .text-justify {
            text-align: justify;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container my-5 flex-grow-1">
        <?php if ($movie_db): ?>
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card p-4 p-md-5 mb-5">

                        <div class="row g-5 mb-5">
                            <div class="col-md-4">
                                <?php if ($poster_path): ?>
                                    <img src="https://image.tmdb.org/t/p/w500<?= $poster_path ?>" 
                                         class="img-fluid rounded-4 shadow-md w-100" 
                                         alt="Poster di <?= htmlspecialchars($titolo) ?>">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center rounded-4 shadow-sm w-100" 
                                         style="aspect-ratio: 2/3; background-color: var(--bg-muted); border: 2px dashed var(--border);">
                                        <div class="text-center">
                                            <i class="bi bi-film text-muted" style="font-size: 3.5rem;"></i>
                                            <p class="text-muted small mt-2 fw-medium">Poster non disponibile</p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($trailerKey): ?>
                                    <div class="mt-4">
                                        <button type="button"
                                            class="btn btn-dark w-100 py-2 d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#trailerModal">
                                            <i class="bi bi-play-circle-fill fs-5 me-2"></i> Guarda Trailer
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-8">
                                <h1 class="fw-bold display-5 mb-2" style="color: var(--text);"><?= htmlspecialchars($titolo) ?></h1>

                                <?php if (!empty($titolo_orig) && strcasecmp(trim($titolo_orig), trim($titolo)) !== 0): ?>
                                    <p class="fs-5 mb-4" style="color: var(--text-muted);"><?= htmlspecialchars($titolo_orig) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($registi)): ?>

                                <?php
                                // Trasformo l'array dei registi in link HTML
                                $registi_links = [];
                                
                                foreach ($registi as $regista) {
                                    $name = htmlspecialchars($regista['name']);
                                    $id   = urlencode($regista['id']);

                                    $registi_links[] = "<a href='https://www.themoviedb.org/person/$id'
                                                        class='text-decoration-none'
                                                        style='color: var(--accent); transition: color 0.2s;'
                                                        onmouseover='this.style.color=\"var(--accent-hover)\"'
                                                        onmouseout='this.style.color=\"var(--accent)\"'
                                                        target='_blank'>
                                                        $name
                                                        </a>";
                                }
                                $registi_output = implode(', ', $registi_links);
                                ?>

                                <div class="mb-4">
                                    <small class="text-uppercase fw-bold d-block mb-1"
                                        style="letter-spacing: 1px; color: var(--text-muted);">
                                        Regia
                                    </small>

                                    <p class="fs-5 fw-medium mb-0" style="color: var(--text);">
                                        <?= $registi_output ?>
                                    </p>
                                </div>

                            <?php endif; ?>

                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($generi as $genre): ?>
                                        <a href="search_genre.php?id=<?= urlencode($genre['id']) ?>&name=<?= urlencode($genre['name']) ?>" 
                                            class="badge badge-genre text-decoration-none px-3 py-2">
                                            <?= htmlspecialchars($genre['name']) ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>

                                <?php if (isset($_SESSION['username'])): ?>
                                    <form method="POST" class="d-flex flex-wrap gap-2 mb-4 pb-4 border-bottom">
                                        
                                        <button class="btn <?= $is_favorite ? 'btn-danger' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3" name="<?= $is_favorite ? 'delete_favorite' : 'favorite' ?>">
                                            <i class="bi bi-heart-fill me-1"></i> <?= $is_favorite ? 'Rimuovi' : 'Preferiti' ?>
                                        </button>

                                        <button class="btn <?= $is_watchlist ? 'btn-primary' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3" name="<?= $is_watchlist ? 'delete_watchlist' : 'watchlist' ?>">
                                            <i class="bi bi-bookmark-fill me-1"></i> <?= $is_watchlist ? 'Rimuovi' : 'Watchlist' ?>
                                        </button>

                                        <button class="btn <?= $is_watched ? 'btn-success' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3" name="<?= $is_watched ? 'delete_watched' : 'watched' ?>">
                                            <i class="bi bi-eye-fill me-1"></i> <?= $is_watched ? 'Rimuovi' : 'Watched' ?>
                                        </button>

                                        <a href="/pages/user/review.php?tmdb_id=<?= urlencode($tmdb_id) ?>" class="btn btn-dark btn-sm rounded-pill px-3">
                                            <i class="bi bi-pencil-fill me-1"></i> <?= $is_review ? "Modifica recensione" : "Scrivi recensione" ?>
                                        </a>
                                    </form>
                                <?php endif; ?>

                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="fw-bold m-0" style="color: var(--text);">Trama</h4>
                                        <div class="d-flex align-items-center fs-4 fw-bold">
                                            <i class="bi bi-star-fill text-warning me-2"></i>
                                            <span>
                                                <?= number_format($voto, 1) ?>
                                                <small style="color: var(--text-muted);" class="fw-normal fs-6">/ 10</small>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-justify lh-lg fs-6 mb-4" style="color: var(--text-muted);"><?= nl2br(htmlspecialchars($trama)) ?></p>
                                    
                                    <?php if ($recensioni_altri > 0): ?>
                                        <a href="/pages/public/community_reviews.php?tmdb_id=<?= urlencode($tmdb_id) ?>&title=<?= urlencode($titolo) ?>"" class="text-decoration-none fw-bold" style="color: var(--accent);">
                                            <i class="bi bi-chat-left-text-fill me-1"></i> Leggi le recensioni della community
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- STAT BAR -->
                        <div class="row text-center py-4 rounded-4 mb-5 mx-0" style="background-color: var(--bg-muted); border: 1px solid var(--border);">
                            <div class="col-4 border-end" style="border-color: var(--border) !important;">
                                <div class="small text-uppercase fw-bold" style="color: var(--text-muted);">Durata</div>
                                <div class="fw-bold fs-5" style="color: var(--text);"><?= $durata ?> min</div>
                            </div>
                            <div class="col-4 border-end" style="border-color: var(--border) !important;">
                                <div class="small text-uppercase fw-bold" style="color: var(--text-muted);">Anno</div>
                                <div class="fw-bold fs-5" style="color: var(--text);"><?= $anno ?></div>
                            </div>
                            <div class="col-4">
                                <div class="small text-uppercase fw-bold" style="color: var(--text-muted);">Paese</div>
                                <div class="fw-bold fs-5" style="color: var(--text);"><?= $paese ?></div>
                            </div>
                        </div>

                        <!-- CAST -->
                        <div>
                            <h4 class="fw-bold mb-4" style="color: var(--text);">Cast Principale</h4>
                            <div class="row g-3">
                                <?php foreach ($cast as $actor):
                                    $nome = $actor['name'] ?? 'Attore Sconosciuto';
                                    $ruolo = $actor['character'] ?? 'Personaggio non specificato';
                                    $idTMDB = $actor['id'] ?? '';
                                    $path = $actor['profile_path'] ?? null;
                                    
                                    if ($path) {
                                        $fotoUrl = 'https://image.tmdb.org/t/p/w185' . $path;
                                    } else {
                                        $nomeEncoded = urlencode($nome);
                                        $fotoUrl = "https://ui-avatars.com/api/?name={$nomeEncoded}&background=f1f5f9&color=64748b";
                                    }
                                ?>
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="https://www.themoviedb.org/person/<?= $idTMDB ?>" class="text-decoration-none d-block" target="_blank">
                                        <div class="d-flex align-items-center p-2 rounded-3 transition-hover" 
                                            style="background-color: var(--bg-surface); border: 1px solid var(--border);">
                                            
                                            <img src="<?= $fotoUrl ?>"
                                                class="cast-avatar rounded-circle border border-2 border-white shadow-sm me-3"
                                                style="width: 50px; height: 50px; object-fit: cover;"
                                                loading="lazy"
                                                alt="<?= htmlspecialchars($nome) ?>">

                                            <div class="overflow-hidden">
                                                <p class="mb-0 fw-bold text-truncate" style="font-size: 0.95rem; color: var(--text);">
                                                    <?= htmlspecialchars($nome) ?>
                                                </p>
                                                <p class="mb-0 text-truncate" style="font-size: 0.85rem; color: var(--text-muted);">
                                                    <?= htmlspecialchars($ruolo) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Player per admin o tester -->
                        <?php if(isset($_SESSION['tester']) && $_SESSION['tester'] == 1): ?>
                            <div class="mt-5 pt-4" style="border-top: 1px solid var(--border);">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <h3 class="fw-bold mb-0" style="color: var(--text);">Visiona il Film</h3>
                                </div>

                                <div class="mb-3">
                                    <label for="siteSelect" class="form-label text-uppercase fw-bold mb-1" style="letter-spacing: 1px; color: var(--text-muted); font-size: 0.75rem;">
                                        Sorgente Video
                                    </label>
                                    
                                    <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden" style="border: 1px solid var(--border); max-width: 250px;">
                                        <span class="input-group-text border-0" style="background-color: var(--bg-muted); color: var(--text-muted);">
                                            <i class="bi bi-play-circle-fill"></i>
                                        </span>
                                        <select id="siteSelect" class="form-select border-0 shadow-none" name="sites" style="background-color: var(--bg-surface); color: var(--text); font-weight: 500; cursor: pointer;">
                                            <?php /** @var array $links **/
                                            foreach($links as $key => $value): ?>
                                                <option value="<?php echo $value; ?>" style="background-color: var(--bg-surface); color: var(--text);">
                                                    Server: <?php echo $key; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="rounded-4 overflow-hidden shadow-sm" 
                                    style="border: 1px solid var(--border); background: #000;">
                                    <div class="ratio ratio-16x9">
                                        <!--Primo elemento dell'array con reset() -->
                                        <iframe id="videoFrame"
                                                src="<?php echo reset($links) . $movie_id; ?>"
                                                allowfullscreen
                                                allow="encrypted-media; picture-in-picture"
                                                loading="lazy"
                                                style="border: none;">
                                        </iframe>
                                    </div>
                                </div>

                                <p class="small mt-2 text-end" style="color: var(--text-muted);">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Contenuto fornito da terze parti
                                </p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <?php if ($trailerKey): ?>
                <div class="modal fade" id="trailerModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content border-0" style="background: transparent; box-shadow: none; outline: none;">

                            <button type="button"
                                    data-bs-dismiss="modal"
                                    aria-label="Chiudi"
                                    style="position: absolute;
                                        top: -44px; right: 0;
                                        width: 36px; height: 36px;
                                        border-radius: 50%;
                                        border: 2px solid rgba(255,255,255,0.7);
                                        background: rgba(0,0,0,0.5);
                                        color: #fff;
                                        font-size: 18px;
                                        line-height: 1;
                                        cursor: pointer;">
                                &times;
                            </button>

                            <div class="ratio ratio-16x9" style="border-radius: 16px; overflow: hidden; box-shadow: none;">
                                <iframe id="trailerVideo"
                                    data-src="https://www.youtube.com/embed/<?= $trailerKey ?>?rel=0&autoplay=1"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen
                                    style="border: none; outline: none; display: block;">
                                </iframe>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-warning shadow-sm rounded-4 d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div><?= htmlspecialchars($errore) ?></div>
            </div>
        <?php endif; ?>

    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
    
    <script>
        const select = document.getElementById('siteSelect');
        const iframe = document.getElementById('videoFrame');

        select.addEventListener('change', function() {
            iframe.src = this.value + "<?= $movie_id ?>";
        });
    </script>
</body>
</html>