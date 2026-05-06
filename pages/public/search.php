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

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$tmdb = Tmdb::client($_ENV['API_KEY']);

$errore = "";
$moviesList = [];
$searched = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($searched !== '') {
    $raw = $tmdb->raw()->url('/search/movie', [
        'query' => $searched,
        'language' => 'it-IT'
    ]);

    $results = $raw?->getBody()['results'] ?? [];

    if (empty($results)) 
        $errore = "Nessun risultato trovato per: " . htmlspecialchars($searched);
    else 
        $moviesList = movieObj::search($results);
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerca Film - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1 d-flex flex-column align-items-center">

        <div class="w-100" style="max-width: 650px;">

            <?php if ($errore): ?>
                <div class="alert alert-warning text-center shadow-sm rounded-3 border-0">
                    <i class="bi bi-info-circle me-2"></i> <?= $errore ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($moviesList)): ?>
                <h5 class="text-muted mb-3 fw-normal">Risultati della ricerca</h5>
                <div class="d-flex flex-column gap-3">

                    <?php foreach ($moviesList as $movie): ?>
                        <a href="film.php?tmdb_id=<?= urlencode($movie['id']) ?>" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-3 card-hover bg-white search-result-card">
                                <div class="card-body px-4 py-3 d-flex align-items-center gap-3">

                                    <?php if ($movie['poster']): ?>
                                        <img src="<?= htmlspecialchars($movie['poster']) ?>"
                                            alt="Poster <?= htmlspecialchars($movie['titolo']) ?>"
                                            class="rounded-2 flex-shrink-0"
                                            style="width: 48px; height: 72px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-2 flex-shrink-0 bg-secondary d-flex align-items-center justify-content-center"
                                            style="width: 48px; height: 72px;">
                                            <i class="bi bi-film text-white fs-5"></i>
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex-grow-1 overflow-hidden">
                                        <span class="fs-6 text-dark fw-medium d-block text-truncate">
                                            <?= htmlspecialchars($movie['titolo']) ?>
                                        </span>
                                        <?php if ($movie['anno']): ?>
                                            <small class="text-muted"><?= htmlspecialchars($movie['anno']) ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <i class="bi bi-chevron-right text-muted flex-shrink-0"></i>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

        </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>

</html>