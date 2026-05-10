<?php
// Mostra le recensioni degli utenti per un film specifico.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

$recensioni_altri = [];
$movie_id = $_GET['tmdb_id'] ?? null;

// Recuperiamo le recensioni degli altri utenti
try {   
    $sql = "SELECT r.commento, r.voto, u.nome, u.cognome
            FROM recensioni r
            JOIN utenti u ON r.id_utente = u.id_utente 
            WHERE tmdb_id = :tmdb_id
            ORDER BY r.tmdb_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':tmdb_id' => $movie_id]);

    $recensioni_altri = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recensioni della Community - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root { --accent-color: #ffc107; }
        .text-justify { text-align: justify; }
        .review-poster {
            width: 120px;
            min-width: 120px;
            aspect-ratio: 2/3;
            object-fit: cover;
            border-radius: 0.5rem 0 0 0.5rem;
        }
        .transition-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-2">Recensioni della Community</h1>
        <p class="text-muted mb-4">Scopri cosa pensano gli altri utenti</p>

            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($recensioni_altri as $r): 
                    $poster_url = !empty($r['poster']) ? "https://image.tmdb.org/t/p/w500" . $r['poster'] : "https://via.placeholder.com/500x750?text=No+Poster";
                ?>
                <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover">
                            <div class="d-flex h-100">

                                <div class="card-body d-flex flex-column justify-content-between p-3">
                                    <div>
                                        <h5 class="fw-bold mb-1 text-dark">
                                            <?= htmlspecialchars($r['titolo']) ?>
                                        </h5>
                                        
                                        <div class="small text-primary mb-2">
                                            <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($r['nome']) . " " . htmlspecialchars($r['cognome'])?>
                                        </div>

                                        <?php if (!empty($r['commento'])): ?>
                                            <p class="text-muted small mb-2 text-justify">
                                                "<?= nl2br(htmlspecialchars($r['commento'])) ?>"
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex align-items-center gap-1 mt-2">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span class="fw-bold fs-5"><?= number_format($r['voto'], 1) ?></span>
                                        <span class="text-muted small">/10</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>