<?php
session_start();
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

use Kiwilan\Tmdb\Tmdb;

$tmdb = Tmdb::client($_ENV['API_KEY']);

$errore = "";
$movie = null;

$movie_id = $_GET['tmdb_id'] ?? null;

if (!empty($movie_id)) {
    $movie = $tmdb->movies()->details(
        movie_id: $movie_id,
        append_to_response: ['credits']
    );

    if (!$movie) {
        $errore = "Film non trovato su TMDB";
    }
} else {
    $errore = "Nessun film selezionato";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $movie ? htmlspecialchars($movie->getTitle()) : 'Film' ?> - Cinevobis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">

        <?php if ($errore): ?>
            <div class="alert alert-danger text-center shadow-sm rounded-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errore) ?>
            </div>

        <?php elseif ($movie): ?>
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-11">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-5">

                            <!-- Poster + info principali -->
                            <div class="row g-4 mb-4">

                                <?php if ($movie->getPosterPath()): ?>
                                <div class="col-md-3 text-center">
                                    <img 
                                        src="https://image.tmdb.org/t/p/w342<?= $movie->getPosterPath() ?>"
                                        alt="<?= htmlspecialchars($movie->getTitle()) ?>"
                                        class="img-fluid rounded-3 shadow-sm"
                                    >
                                </div>
                                <?php endif; ?>

                                <div class="col-md-<?= $movie->getPosterPath() ? '9' : '12' ?>">
                                    <h1 class="fw-bold mb-1"><?= htmlspecialchars($movie->getTitle()) ?></h1>

                                    <?php if ($movie->getOriginalTitle() !== $movie->getTitle()): ?>
                                        <p class="text-muted fst-italic mb-3"><?= htmlspecialchars($movie->getOriginalTitle()) ?></p>
                                    <?php endif; ?>

                                    <!-- Generi -->
                                    <?php if (!empty($movie->getGenres())): ?>
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <?php foreach ($movie->getGenres() as $genre): ?>
                                                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">
                                                    <?= htmlspecialchars($genre->getName()) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Voto -->
                                    <?php if ($movie->getVoteAverage()): ?>
                                        <div class="mb-3">
                                            <i class="bi bi-star-fill text-warning me-1"></i>
                                            <span class="fw-semibold"><?= number_format($movie->getVoteAverage(), 1) ?></span>
                                            <span class="text-muted small">/ 10</span>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Trama -->
                                    <h5 class="text-muted fw-semibold mb-2">Trama</h5>
                                    <p class="fs-6 text-secondary lh-lg">
                                        <?= nl2br(htmlspecialchars($movie->getOverview() ?? 'Nessuna trama disponibile.')) ?>
                                    </p>
                                </div>
                            </div>

                            <hr class="text-muted opacity-25 mb-4">

                            <!-- Dettagli tecnici -->
                            <div class="row text-center text-md-start g-4 mb-4">
                                <div class="col-md-4">
                                    <div class="text-uppercase text-muted small fw-bold mb-1">Durata</div>
                                    <div class="fs-5 fw-medium">
                                        <i class="bi bi-clock text-primary me-2"></i>
                                        <?= $movie->getRuntime() ? htmlspecialchars($movie->getRuntime()) . ' min' : '?' ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-uppercase text-muted small fw-bold mb-1">Anno di uscita</div>
                                    <div class="fs-5 fw-medium">
                                        <i class="bi bi-calendar3 text-primary me-2"></i>
                                        <?= $movie->getReleaseDate()?->format('Y') ?? '?' ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-uppercase text-muted small fw-bold mb-1">Paese di produzione</div>
                                    <div class="fs-5 fw-medium">
                                        <i class="bi bi-globe text-primary me-2"></i>
                                        <?php
                                        $countries = $movie->getProductionCountries();
                                        echo !empty($countries)
                                            ? htmlspecialchars(strtoupper($countries[0]->getIsoCode()))
                                            : '?';
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Cast -->
                            <?php
                            $cast = $movie->getCredits()?->getCast() ?? [];
                            $cast = array_slice($cast, 0, 10); // primi 10 attori
                            ?>
                            <?php if (!empty($cast)): ?>
                                <hr class="text-muted opacity-25 mb-4">
                                <h5 class="text-muted fw-semibold mb-3">Cast</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($cast as $actor): ?>
                                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-6 fw-normal">
                                            <i class="bi bi-person-circle me-1 text-secondary"></i>
                                            <?= htmlspecialchars($actor->getName()) ?>
                                            <?php if ($actor->getCharacter()): ?>
                                                <span class="text-muted fst-italic">
                                                    (<?= htmlspecialchars($actor->getCharacter()) ?>)
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

</body>
</html>