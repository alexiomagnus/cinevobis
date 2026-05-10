<?php
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

$generi = [];
$errorMessage = null;

try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase("cinevobis");
    $collection = $db->selectCollection("films");

    $pipeline = [
        ['$unwind' => '$genres'],
        ['$group' => [
            '_id'  => '$genres.id',
            'name' => ['$first' => '$genres.name'],
        ]],
        ['$sort' => ['name' => 1]],
    ];

    $generi = $collection->aggregate($pipeline)->toArray();

} catch (Exception $e) {
    error_log("Errore con MongoDB: " . $e->getMessage());
    $errorMessage = "Impossibile caricare i generi. Riprova più tardi.";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinevobis - Generi</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include(__DIR__ . "/../../includes/header.php"); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <div class="container">

            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-grid-fill" style="color: var(--accent); font-size: 1.6rem"></i>
                <h1 class="fw-bold mb-0">Generi</h1>
            </div>
            <p class="mb-4" style="color: var(--text-muted);">Esplora il catalogo per categoria</p>

            <?php if ($errorMessage): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($errorMessage) ?>
                </div>

            <?php elseif (empty($generi)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>Nessun genere trovato.
                </div>

            <?php else: ?>
                <!-- row-cols-md-3 su tablet (era 4), row-cols-lg-4 solo su desktop -->
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-4 g-3">
                    <?php foreach ($generi as $genere):
                        $gId   = (int) $genere['_id'];
                        $gName = $genere['name'];
                    ?>
                    <div class="col">
                        <a href="search_genre.php?id=<?= urlencode($gId) ?>&name=<?= urlencode($gName) ?>"
                           class="card transition-hover text-decoration-none d-flex flex-row align-items-center gap-2 p-3"
                           style="color: var(--text);">
                            <i class="bi bi-film flex-shrink-0" style="font-size: 1.2rem; color: var(--accent);"></i>
                            <!-- rimosso text-truncate, aggiunto word-break per evitare tagli -->
                            <span class="fw-semibold" style="font-size: 0.9rem; word-break: break-word;">
                                <?= htmlspecialchars($gName) ?>
                            </span>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>