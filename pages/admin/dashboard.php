<?php
// Dashboard admin che mostra le statistiche del sito:
// film in MongoDB, utenti, sessioni e notifiche non lette.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;


// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}


// Dichiarazione variabili
$totaleFilm = 0;
$totaleUtenti = 0;
$totaleSessioni = 0;
$totaleNotifiche = 0;


// Connessione a MongoDB
try {
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

    // Conteggio documenti
    $totaleFilm = $collection->countDocuments([]);
} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}


// Conteggio utenti attivi
try {
    $sql = "SELECT COUNT(*) FROM utenti WHERE attivo = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $totaleUtenti = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Errore DB: " . $e->getMessage());
}


// Conteggio sessioni attive
try {
    $sql = "SELECT COUNT(*) FROM sessioni WHERE data_logout IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $totaleSessioni = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Errore DB: " . $e->getMessage());
}


// Conteggio notifiche
try {
    $sql = "SELECT COUNT(*) FROM notifiche WHERE letta = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $totaleNotifiche = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Errore DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* 1. Gestione Hover (Bootstrap non ha utility per transform) */
        .hover-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .hover-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important; /* Equivale a shadow-md */
            border-color: var(--accent, #0d6efd) !important;
        }
        
        /* 2. Micro-rifiniture non presenti in Bootstrap */
        .min-h-280 { min-height: 280px; }
        .letter-spacing-sm { letter-spacing: 0.5px; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container flex-grow-1 py-5">

        <div class="row mb-4">
            <div class="col-12 text-center text-sm-start">
                <h1 class="fw-bold h3 mt-1">
                    Dashboard <span class="text-muted fw-normal">| Benvenuto <?= htmlspecialchars($username) ?></span>
                </h1>
            </div>
        </div>

        <div class="row g-3 mb-5">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm bg-white border-start border-4 h-100 d-flex flex-column justify-content-center p-3">
                    <div class="text-muted fw-bold text-uppercase mb-1 letter-spacing-sm" style="font-size: 0.75rem;">Film nel catalogo</div>
                    <div class="fw-bold text-dark fs-3"><?= number_format($totaleFilm, 0, ',', '.') ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm bg-white border-start border-4 h-100 d-flex flex-column justify-content-center p-3">
                    <div class="text-muted fw-bold text-uppercase mb-1 letter-spacing-sm" style="font-size: 0.75rem;">Utenti attivi</div>
                    <div class="fw-bold text-dark fs-3"><?= number_format($totaleUtenti, 0, ',', '.') ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm bg-white border-start border-4 h-100 d-flex flex-column justify-content-center p-3">
                    <div class="text-muted fw-bold text-uppercase mb-1 letter-spacing-sm" style="font-size: 0.75rem;">Sessioni attive</div>
                    <div class="fw-bold text-dark fs-3"><?= number_format($totaleSessioni, 0, ',', '.') ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm bg-white border-start border-4 h-100 d-flex flex-column justify-content-center p-3">
                    <div class="text-muted fw-bold text-uppercase mb-1 letter-spacing-sm" style="font-size: 0.75rem;">Messaggi da leggere</div>
                    <div class="fw-bold text-dark fs-3"><?= number_format($totaleNotifiche, 0, ',', '.') ?></div>
                </div>
            </div>
        </div>

        <h2 class="h6 fw-bold text-uppercase text-muted mb-4">Gestione Sistema</h2>

        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-3">
                <a href="films.php" class="card hover-card min-h-280 text-decoration-none bg-white text-center d-flex flex-column align-items-center justify-content-center py-5 px-4 border rounded">
                    <div class="text-warning mb-3 display-3 lh-1"><i class="bi bi-collection-play-fill"></i></div>
                    <h3 class="fs-4 fw-bold text-dark mb-2">Archivio Film</h3>
                    <p class="text-muted fs-6 mb-0">Gestisci il catalogo multimediale e i film</p>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="users.php" class="card hover-card min-h-280 text-decoration-none bg-white text-center d-flex flex-column align-items-center justify-content-center py-5 px-4 border rounded">
                    <div class="text-success mb-3 display-3 lh-1"><i class="bi bi-person-lines-fill"></i></div>
                    <h3 class="fs-4 fw-bold text-dark mb-2">Utenti</h3>
                    <p class="text-muted fs-6 mb-0">Amministra gli account e i ruoli utenti</p>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="sessions.php" class="card hover-card min-h-280 text-decoration-none bg-white text-center d-flex flex-column align-items-center justify-content-center py-5 px-4 border rounded">
                    <div class="text-primary mb-3 display-3 lh-1"><i class="bi bi-shield-lock-fill"></i></div>
                    <h3 class="fs-4 fw-bold text-dark mb-2">Log Accessi</h3>
                    <p class="text-muted fs-6 mb-0">Monitora la sicurezza e le sessioni attive</p>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="notifications.php" class="card hover-card min-h-280 text-decoration-none bg-white text-center d-flex flex-column align-items-center justify-content-center py-5 px-4 border rounded">
                    <div class="text-danger mb-3 display-3 lh-1"><i class="bi bi-chat-left-dots-fill"></i></div>
                    <h3 class="fs-4 fw-bold text-dark mb-2">Messaggi</h3>
                    <p class="text-muted fs-6 mb-0">Gestisci le comunicazioni degli utenti</p>
                </a>
            </div>
        </div>

    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>