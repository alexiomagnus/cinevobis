<?php
// Home page Cinevobis: mostra film in evidenza e migliori film.
// Recupera i dati da MongoDB dalla collezione `films` del database `cinevobis`.
require_once(__DIR__ . '/config/config.php');
require_once(__DIR__ . '/config/functions.php');
require_once(__DIR__ . '/config/connection.php');
require_once(__DIR__ . '/includes/header_logic.php');
require_once(__DIR__ . '/vendor/autoload.php');

$nome = $_SESSION['nome'] ?? '';

// Film da mostrare
$recommendedFilms = [];
$topFilms = [];


try {
    // Controllo per evitare errori se MongoDB è offline
    if (!$collection) {
        throw new \Exception("Connessione a MongoDB non disponibile.");
    }

    // Film in evidenza: gli ultimi 12 film più recenti
    $cursor = $collection->find([], [
        'limit' => 12,
        'sort' => ['release_date' => -1]
    ]);

    $recommendedFilms = iterator_to_array($cursor);

} catch (\Throwable $e) { // \Throwable cattura sia Exception che Fatal Error
    error_log("Errore caricamento film in evidenza: " . $e->getMessage());
}


try {
    // Controllo per evitare errori se MongoDB è offline
    if (!$collection) {
        throw new \Exception("Connessione a MongoDB non disponibile.");
    }

    // Prende i migliori 12 film ordinati per voto medio
    $cursor = $collection->find([], [
        'limit' => 12,
        'sort' => ['vote_average' => -1]
    ]);

    $topFilms = iterator_to_array($cursor);
    
} catch (\Throwable $e) {
    error_log("Errore caricamento migliori film: " . $e->getMessage());
}


// Film casuale ogni settimana in prima pagina
// srand((int)date('oW'));
// $film = $topFilms[array_rand($topFilms)] ?? null;


// Film statico in prima pagina
$movie_id = 129;  // La città incantata
$film = search_film_by_id($topFilms, $movie_id);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
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
            if (!empty($film)):
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

            <hr class="my-5 border-0" style="border-top: 0.5px solid var(--text) !important;">

            <div class="mt-5 mb-5 py-lg-4">
                <div class="row g-4 g-lg-5 align-items-center">

                    <div class="col-12 col-lg-6">
                        <p class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                            <span class="me-3 rounded-pill" style="width: 30px; height: 2px; background-color: currentColor;"></span>
                            Il progetto
                        </p>
                        
                        <h2 class="fw-bolder mb-3" style="font-size: clamp(1.75rem, 3.5vw, 2.25rem); line-height: 1.2;">
                            Perché nasce Cinevobis?
                        </h2>
                        
                        <p class="text-secondary mb-4" style="line-height: 1.8; font-size: 1.05rem;">
                            Cinevobis nasce per chi ama i film, concedendo la possibilità di condividere
                            la propria passione con gli altri. Il nome deriva da
                            <strong class="text-dark">cine</strong>, inteso come cinema, e
                            <strong class="text-dark">vobis</strong>, dal latino <em>per voi</em>.
                        </p>
                        
                        <blockquote class="mb-0 p-4 rounded-4 bg-light border-start border-4" style="border-color: var(--bs-gray-400) !important;">
                            <p class="fst-italic text-dark mb-2" style="font-size: 0.95rem; line-height: 1.6;">
                                "I film non ti dicono cosa pensare. Ti insegnano come sentire."
                            </p>
                            <cite class="text-muted fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.05em;">— Roger Ebert</cite>
                        </blockquote>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="row g-3 g-md-4">
                            <?php
                            $features = [
                                ['icon' => 'bi-heart-fill',    'titolo' => 'Preferiti',  'desc' => 'I film che ami nel tuo catalogo personale.', 'color' => '#dc3646'],
                                ['icon' => 'bi-pen-fill',      'titolo' => 'Recensioni', 'desc' => 'Scrivi, vota e condividi il tuo pensiero.', 'color' => 'var(--text)'],
                                ['icon' => 'bi-eye-fill',      'titolo' => 'Watched',    'desc' => 'Lo storico di tutto ciò che hai già visto.', 'color' => '#1b8855'],
                                ['icon' => 'bi-bookmark-fill', 'titolo' => 'Watchlist',  'desc' => 'I titoli che non vuoi assolutamente perderti.', 'color' => '#267bfd'],
                            ];
                            foreach ($features as $f): ?>
                            <div class="col-12 col-sm-6">
                                <div class="p-4 rounded-4 h-100 transition-hover" 
                                    style="background-color: var(--bg-muted); border: 1px solid var(--border);">
                                    
                                    <i class="bi <?= $f['icon'] ?> mb-3 d-block" style="font-size: 1.8rem; color: <?= $f['color'] ?>;"></i>
                                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.05rem;"><?= $f['titolo'] ?></h5>
                                    <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.6;">
                                        <?= $f['desc'] ?>
                                    </p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <?php require_once('includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>