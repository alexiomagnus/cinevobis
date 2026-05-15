<?php
// Pagina della watchlist personale, con i film da guardare dell'utente.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/functions.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');


// Controllo autenticazione
$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}


// Estrazione tmdb_id
$ids = [];
$id_utente = $_SESSION['id_utente'] ?? '';

try {
    $sql = "SELECT tmdb_id FROM watchlist WHERE id_utente = :id_u ORDER BY data_aggiunto DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_u' => $id_utente]);

    // Prende l'intera colonna e la mette dentro un array
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
    
} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
    $ids = [];
}


// Dichiarazione variabili
$films = [];
$count = 0;

if (!empty($ids)) {

    // Conteggio film nel DB
    try {
        $sql = "SELECT COUNT(*) FROM watchlist WHERE id_utente = :id_u";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_u' => $id_utente]);

        $count = $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }


    // Ricerca film
    try {
        // Controllo per evitare errori se MongoDB è offline
        if (!$collection) {
            throw new \Exception("Connessione a MongoDB non disponibile.");
        }

        $cursor = $collection->find(
            ['id' => ['$in' => $ids]]
        );

        $films = movie_sorting($cursor, $ids);

    } catch (\Throwable $e) { // \Throwable cattura sia Exception che Fatal Error
        error_log("Errore caricamento film da vedere: " . $e->getMessage());
        $films = [];
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-4">Watchlist</h1>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non hai ancora aggiunto film alla tua watchlist
            </div>
        <?php else: ?>
            <?php 
            if ($count > 0) {
                echo "<div class='mb-4'>";
                echo "<small class='text-uppercase fw-bold text-muted d-block mb-2' style='letter-spacing:1px'>Hai " . htmlspecialchars($count) . " Film che vorresti vedere</small>";
                echo "</div>";
            }
            ?>

            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                
                <?php 
                /** @var array $film */
                foreach ($films as $film):
                    $id = $film['id'] ?? '';
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) 
                        ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] 
                        : "https://via.placeholder.com/500x750?text=No+Poster";
                    $anno = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                ?>

                <div class="col">
                    <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                            <div class="position-relative">
                                <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" style="object-fit: cover; aspect-ratio: 2/3;">
                            </div>
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
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>