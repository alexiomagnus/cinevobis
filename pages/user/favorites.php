<?php
// Pagina preferiti: mostra i film che l'utente ha salvato come preferiti.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/functions.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

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
    $sql = "SELECT tmdb_id FROM preferiti WHERE id_utente = :id_u ORDER BY data_aggiunto DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_u' => $id_utente]);

    // Prende l'intera colonna e la mette dentro un array
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
    
} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
    $ids = [];
}


// Connessione a MongoDB e ricerca film
$films = [];
$count = 0;

if (!empty($ids)) {

    // Conteggio preferiti
    try {
        $sql = "SELECT COUNT(*) FROM preferiti WHERE id_utente = :id_u";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_u' => $id_utente]);

        $count = $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }


    // Connessione a MongoDB e ricerca film
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db = $mongoClient->selectDatabase("cinevobis");
        $collection = $db->selectCollection("films");

        $cursor = $collection->find(
            ['id' => ['$in' => $ids]],
            [
                'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
            ]
        );

        $films = movie_sorting($cursor, $ids);

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
        $films = [];
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preferiti - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-4">Preferiti</h1>

        <?php 
            if ($count > 0) {
                echo "<div class='mb-4'>";
                echo "<small class='text-uppercase fw-bold text-muted d-block mb-2' style='letter-spacing:1px'>Hai " . htmlspecialchars($count) . " Film come preferiti</small>";
                echo "</div>";
            }
        ?>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non hai ancora aggiunto film ai tuoi preferiti
            </div>
        <?php else: ?>
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