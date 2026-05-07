<?php
require_once(__DIR__ . '/../../config/config.php');
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

// Estrazione tmdb_id + dati recensione
$recensioni_map = [];
$ids = [];
$id_utente = $_SESSION['id_utente'] ?? '';

try {
    $sql = "SELECT tmdb_id, commento, voto FROM recensioni WHERE id_utente = :id_u";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_u' => $id_utente]);

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $ids[] = (int) $row['tmdb_id'];

        $recensioni_map[(int) $row['tmdb_id']] = [
            'voto' => $row['voto'],
            'commento' => $row['commento'],
        ];
    }

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}

// Connessione a MongoDB e ricerca film
$films = [];
$numeroRecensioni = 0;

if (!empty($ids)) {

    // Conteggio recensioni
    try {
        $sql = "SELECT COUNT(*) FROM recensioni WHERE id_utente = :id_u";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_u' => $id_utente]);

        $numeroRecensioni = $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }


    // Connessione a MongoDB
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db = $mongoClient->selectDatabase("cinevobis");
        $collection = $db->selectCollection("films");

        $cursor = $collection->find(
            ['id' => ['$in' => $ids]],
            [
                'sort' => ['vote_average' => -1],
                'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array'],
            ]
        );

        $films = iterator_to_array($cursor);

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recensioni - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root { --accent-color: #ffc107; }

        .text-justify { text-align: justify; }

        .cast-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        .review-poster {
            width: 120px;
            min-width: 120px;
            aspect-ratio: 2/3;
            object-fit: cover;
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .star-rating {
            color: #ccc;
            font-size: 1.1rem;
            letter-spacing: 2px;
        }

        .star-rating .filled {
            color: var(--accent-color);
        }

        .vote-badge {
            background-color: var(--accent-color);
            color: #1a1a1a;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 0.4rem;
            padding: 2px 10px;
            display: inline-block;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-4">Recensioni</h1>

        <?php 
        if ($numeroRecensioni > 0) {
            echo "<div class='mb-4'>";
            echo "<small class='text-uppercase fw-bold text-muted d-block mb-2' style='letter-spacing:1px'>" . htmlspecialchars($numeroRecensioni) . " Film che hai recensito</small>";
            echo "</div>";
        }
        ?>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non hai ancora recensito nessun film
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-3">
                <?php
                /** @var array $film */
                foreach ($films as $film):
                    $id = (int) ($film['id'] ?? 0);
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path'])
                        ? "https://image.tmdb.org/t/p/w500" . $film['poster_path']
                        : "https://via.placeholder.com/500x750?text=No+Poster";
                    $rec = $recensioni_map[$id] ?? [];
                    $voto = isset($rec['voto']) ? (float) $rec['voto'] : null;
                    $commento = $rec['commento'] ?? '';
                ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover position-relative">
                        <div class="d-flex">

                            <img src="<?= htmlspecialchars($poster) ?>"
                                alt="<?= htmlspecialchars($titolo) ?>"
                                class="review-poster">

                            <div class="card-body d-flex flex-column justify-content-between p-3">
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark stretched-link">
                                            <?= htmlspecialchars($titolo) ?>
                                        </a>
                                    </h5>

                                    <?php if (!empty($commento)): ?>
                                        <p class="text-muted small mb-2 text-justify">
                                            "<?= nl2br(htmlspecialchars($commento)) ?>"
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <?php if ($voto !== null): ?>
                                    <div class="d-flex align-items-center gap-1 mt-1">
                                        <i class="bi bi-star-fill" style="color: var(--accent-color); font-size: 1rem;"></i>
                                        <span class="fw-bold fs-5"><?= $voto ?></span>
                                        <span class="text-muted small">/10</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
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