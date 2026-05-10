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
use MongoDB\Client;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$tmdb = Tmdb::client($_ENV['API_KEY']);


// Dichiarazione variabili
$movie_api = null;
$movie_db = null;
$collection = [];
$errore = "";

$movie_id = $_GET['tmdb_id'] ?? null;


// Connessione a MongoDB
try {
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');
    
} catch(Exception $e) {
    error_log("Errore: " . $e->getMessage());
}


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

    $now = time();
    $aMonthInSeconds = 30 * 24 * 60 * 60; // 30 giorni

    
    // Cercare il film nel DB
    $movie_db = $collection->findOne(
        ['id' => (int)$movie_id],
        ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
    );


    // Se non esiste lo si inserisci
    if ($movie_db === null) {
        $movie_api['last_updated'] = new \MongoDB\BSON\UTCDateTime();  // Timestamp attuale in secondi
        $collection->insertOne($movie_api);
        $movie_db = $movie_api;
    } else {
        // Se esiste si recupera il timestamp 
        $lastUpdateSeconds = isset($movie_db['last_updated']) ? $movie_db['last_updated']->toDateTime()->getTimestamp() : 0;

        // Se è passato un mese si aggiorna il film
        if (($now - $lastUpdateSeconds) > $aMonthInSeconds) {
            $movie_api['last_updated'] = new \MongoDB\BSON\UTCDateTime();

            $collection->updateOne(
                ['id' => $movie_id],
                ['$set' => $movie_api]
            );

            $movie_db = $movie_api;  // Usare i dati fresci per la visualizzazione
        }
    }
}


// 3. Estrazione dati
$titolo = $trama = $poster_path = $trailerKey = $paese = '';
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


// Dichiarazione variabili
$tmdb_id = $movie_db['id'];
$id_utente = $_SESSION['id_utente'];

$is_favorite = false;
$is_review = false;
$is_watchlist = false;
$is_watched = false;


// Verifica condizioni per preferiti
if ($tmdb_id != null && $id_utente != null) {

    // Aggiungere il film alla lista preferiti
    if (isset($_POST['favorite'])) {
        try {
            $sql = "INSERT INTO preferiti (tmdb_id, id_utente, data_aggiunto) VALUES
                    (:tmdb_id, :id_utente, :data_aggiunto)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'tmdb_id' => $tmdb_id,
                'id_utente' => $id_utente,
                'data_aggiunto' => date('Y-m-d H:i:s')
            ]);

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nell'aggiunta alla lista preferiti";
        }
    }

    // Elimina dalla lista preferiti
    if (isset($_POST['delete_favorite'])) {
        try {
            $sql = "DELETE FROM preferiti WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id_utente' => $id_utente,
                ':tmdb_id' => $tmdb_id
            ]);

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nella rimozione dalla lista preferiti";
        }
    }

    // Controllo se l'utente ha aggiunto il film ai preferiti (DOPO aver gestito il POST)
    try {
        $sql = "SELECT * FROM preferiti WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_utente' => $id_utente,
            ':tmdb_id' => $tmdb_id
        ]);

        $results = $stmt->fetchColumn();
        
        if (!empty($results)) 
            $is_favorite = true;

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
    }
}


// Verifica condizioni per watchlist
if ($tmdb_id != null && $id_utente != null) {

    // Aggiungere il film alla lista watchlist
    if (isset($_POST['watchlist'])) {
        try {
            $sql = "INSERT INTO watchlist (tmdb_id, id_utente, data_aggiunto) VALUES
                    (:tmdb_id, :id_utente, :data_aggiunto)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'tmdb_id' => $tmdb_id,
                'id_utente' => $id_utente,
                'data_aggiunto' => date('Y-m-d H:i:s')
            ]);

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nell'aggiunta alla lista watchlist";
        }
    }

    // Elimina dalla lista watchlist
    if (isset($_POST['delete_watchlist'])) {
        try {
            $sql = "DELETE FROM watchlist WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id_utente' => $id_utente,
                ':tmdb_id' => $tmdb_id
            ]);

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nella rimozione dalla lista watchlist";
        }
    }

    // Controllo se l'utente ha aggiunto il film ai watchlist (DOPO aver gestito il POST)
    try {
        $sql = "SELECT * FROM watchlist WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_utente' => $id_utente,
            ':tmdb_id' => $tmdb_id
        ]);

        $results = $stmt->fetchColumn();

        if (!empty($results)) 
            $is_watchlist = true;

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
    }
}


// Verifica condizioni per watched
if ($tmdb_id != null && $id_utente != null) {

    // Aggiungere il film alla lista watched
    if (isset($_POST['watched'])) {
        try {
            $sql = "INSERT INTO watched (tmdb_id, id_utente, data_aggiunto) VALUES
                    (:tmdb_id, :id_utente, :data_aggiunto)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'tmdb_id' => $tmdb_id,
                'id_utente' => $id_utente,
                'data_aggiunto' => date('Y-m-d H:i:s')
            ]);

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nell'aggiunta alla lista watched";
        }
    }

    // Elimina dalla lista watched
    if (isset($_POST['delete_watched'])) {
        try {
            $sql = "DELETE FROM watched WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id_utente' => $id_utente,
                ':tmdb_id' => $tmdb_id
            ]);

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nella rimozione dalla lista watched";
        }
    }

    // Controllo se l'utente ha aggiunto il film ai watched (DOPO aver gestito il POST)
    try {
        $sql = "SELECT * FROM watched WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_utente' => $id_utente,
            ':tmdb_id' => $tmdb_id
        ]);

        $results = $stmt->fetchColumn();

        if (!empty($results)) 
            $is_watched = true;

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
    }
}


// Controlla se l'utente ha già recensito il film
if ($tmdb_id != null && $id_utente != null) {
    try {
        $sql = "SELECT * FROM recensioni WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_utente' => $id_utente, 
            ':tmdb_id' => $tmdb_id
        ]);
        
        $results = $stmt->fetchColumn();
        
        if (!empty($results)) {
            $is_review = true;
            $is_watched = true;
        }

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
    }
}


// Contiamo le recensioni degli altri utenti
$recensioni_altri = 0;
try {   
    $sql = "SELECT COUNT(*)
            FROM recensioni r
            WHERE tmdb_id = :tmdb_id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':tmdb_id' => $movie_id]);

    $recensioni_altri = $stmt->fetchColumn();

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
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
                                <?php if($poster_path): ?>
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
                                    <div class="mb-4">
                                        <small class="text-uppercase fw-bold d-block mb-1" style="letter-spacing: 1px; color: var(--text-muted);">Regia</small>
                                        <p class="fs-5 fw-medium mb-0" style="color: var(--text);">
                                            <?php 
                                            $registi_links = array_map(function($regista) {
                                                $name = htmlspecialchars($regista['name']);
                                                $id = urlencode($regista['id']);
                                                return "<a href='https://www.themoviedb.org/person/$id' class='text-decoration-none' style='color: var(--accent); transition: color 0.2s;' onmouseover='this.style.color=\"var(--accent-hover)\"' onmouseout='this.style.color=\"var(--accent)\"'>$name</a>";
                                            }, $registi);
                                            echo implode(', ', $registi_links);
                                            ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($generi as $genre): ?>
                                        <a href="search_genre.php?id=<?= urlencode($genre['id']) ?>&name=<?= urlencode($genre['name']) ?>" 
                                            class="badge text-decoration-none px-3 py-2" 
                                            style="background-color: var(--bg-muted); color: var(--text); border: 1px solid var(--border); transition: var(--transition);"
                                            onmouseover="this.style.borderColor='var(--accent)'; this.style.backgroundColor='white';"
                                            onmouseout="this.style.borderColor='var(--border)'; this.style.backgroundColor='var(--bg-muted)';">
                                            <?= htmlspecialchars($genre['name']) ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>

                                <?php if($_SESSION['username']): ?>
                                    <form method="POST" class="d-flex flex-wrap gap-2 mb-4 pb-4 border-bottom">
                                        
                                        <button class="btn <?= $is_favorite ? 'btn-danger' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3" name="<?= $is_favorite ? 'delete_favorite' : 'favorite' ?>">
                                            <i class="bi bi-heart-fill me-1"></i> <?= $is_favorite ? 'Rimuovi' : 'Preferiti' ?>
                                        </button>

                                        <button class="btn <?= $is_watchlist ? 'btn-primary' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3" name="<?= $is_watchlist ? 'delete_watchlist' : 'watchlist' ?>">
                                            <i class="bi bi-bookmark-fill me-1"></i> <?= $is_watchlist ? 'Rimuovi' : 'Watchlist' ?>
                                        </button>

                                        <button class="btn <?= $is_watched ? 'btn-success' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3" name="<?= $is_watched ? 'delete_watched' : 'watched' ?>">
                                            <i class="bi bi-eye-fill me-1"></i> <?= $is_watched ? 'Rimuovi' : 'Visto' ?>
                                        </button>

                                        <a href="/pages/user/review.php?tmdb_id=<?= urlencode($tmdb_id) ?>" class="btn btn-dark btn-sm rounded-pill px-3">
                                            <i class="bi bi-pencil-fill me-1"></i> <?= $is_review ? "Modifica recensione" : "Scrivi recensione" ?>
                                        </a>
                                    </form>
                                <?php endif; ?>

                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="fw-bold m-0" style="color: var(--text);">Sinossi</h4>
                                        <div class="d-flex align-items-center fs-4 fw-bold">
                                            <i class="bi bi-star-fill text-warning me-2"></i>
                                            <span>
                                                <?= number_format($voto, 1) ?>
                                                <small style="color: var(--text-muted);" class="fw-normal fs-6">/ 10</small>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-justify lh-lg fs-6 mb-4" style="color: var(--text-muted);"><?= nl2br(htmlspecialchars($trama)) ?></p>
                                    
                                    <?php if($recensioni_altri > 0): ?>
                                        <a href="/pages/public/users_reviews.php?tmdb_id=<?= urlencode($tmdb_id) ?>" class="text-decoration-none fw-bold" style="color: var(--accent);">
                                            <i class="bi bi-chat-left-text-fill me-1"></i> Leggi le recensioni della community
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

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

                        <div>
                            <h4 class="fw-bold mb-4" style="color: var(--text);">Cast Principale</h4>
                            <div class="row g-3">
                                <?php 
                                foreach ($cast as $actor):
                                    // 1. Estrazione dati con valori di default
                                    $nome = $actor['name'] ?? 'Attore Sconosciuto';
                                    $ruolo = $actor['character'] ?? 'Personaggio non specificato';
                                    $idTMDB = $actor['id'] ?? '';
                                    
                                    // 2. Logica per l'immagine del profilo
                                    $path = $actor['profile_path'] ?? null;
                                    if ($path) {
                                        $fotoUrl = "https://image.tmdb.org/t/p/w185" . $path;
                                    } else {
                                        $fotoUrl = "https://ui-avatars.com/api/?name=" . urlencode($nome) . "&background=f1f5f9&color=64748b";
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

                                <?php endforeach; // Fine del ciclo ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?php if ($trailerKey): ?>
            <div class="modal fade" id="trailerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content bg-transparent border-0">
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="ratio ratio-16x9 shadow-lg rounded-4 overflow-hidden" style="background: #000;">
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
</body>
</html>