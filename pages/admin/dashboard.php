<?php
/**
 * Dashboard amministrativa (riservata al profilo admin, id_profilo = 1).
 * Raccoglie e mostra quattro contatori statistici: numero di film nel catalogo
 * MongoDB, numero di utenti registrati, numero totale di sessioni e numero di
 * notifiche non lette. Fornisce link rapidi alle sezioni di gestione.
 *
 * @note Interagisce con la collezione MongoDB: `films` (countDocuments).
 * @note Interagisce con le tabelle MariaDB: `utenti`, `sessioni`, `notifiche`.
 */
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


// Conteggio utenti
try {
    $sql = "SELECT COUNT(*) FROM utenti";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $totaleUtenti = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Errore DB: " . $e->getMessage());
}


// Conteggio sessioni
try {
    $sql = "SELECT COUNT(*) FROM sessioni";
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
        .card-link {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid var(--border) !important;
            border-radius: var(--radius-md);
        }
        .card-link:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md) !important;
            border-color: var(--accent) !important;
        }
        .stat-card {
            border-left: 3px solid var(--border);
            border-radius: 8px;
        }
        .stat-card .stat-label {
            font-size: 0.7rem;
            letter-spacing: 0.5px;
        }
        /* Card gestione più grandi */
        .action-card {
            padding: 2.5rem 1.5rem !important;
            min-height: 220px;
        }
        .action-card .card-icon {
            font-size: 3.5rem;
            line-height: 1;
        }
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

        <!-- Statistiche -->
        <div class="row g-3 mb-5">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 stat-card bg-white">
                    <div class="text-muted stat-label fw-bold text-uppercase mb-1">Film</div>
                    <div class="fs-4 fw-bold text-dark"><?= number_format($totaleFilm, 0, ',', '.') ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 stat-card bg-white">
                    <div class="text-muted stat-label fw-bold text-uppercase mb-1">Utenti</div>
                    <div class="fs-4 fw-bold text-dark"><?= number_format($totaleUtenti, 0, ',', '.') ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 stat-card bg-white">
                    <div class="text-muted stat-label fw-bold text-uppercase mb-1">Sessioni</div>
                    <div class="fs-4 fw-bold text-dark"><?= number_format($totaleSessioni, 0, ',', '.') ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 stat-card bg-white">
                    <div class="text-muted stat-label fw-bold text-uppercase mb-1">Messaggi</div>
                    <div class="fs-4 fw-bold text-dark"><?= number_format($totaleNotifiche, 0, ',', '.') ?></div>
                </div>
            </div>
        </div>

        <h2 class="h6 fw-bold text-uppercase text-muted mb-4">Gestione Sistema</h2>

        <!-- Card azioni -->
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-3">
                <a href="films.php" class="card card-link action-card h-100 text-decoration-none bg-white text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="card-icon text-warning mb-3"><i class="bi bi-collection-play-fill"></i></div>
                    <h3 class="h5 fw-bold text-dark mb-2">Archivio Film</h3>
                    <p class="text-muted small mb-0">Gestisci il catalogo multimediale e i film</p>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="users.php" class="card card-link action-card h-100 text-decoration-none bg-white text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="card-icon text-success mb-3"><i class="bi bi-person-lines-fill"></i></div>
                    <h3 class="h5 fw-bold text-dark mb-2">Utenti</h3>
                    <p class="text-muted small mb-0">Amministra gli account e i ruoli utenti</p>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="sessions.php" class="card card-link action-card h-100 text-decoration-none bg-white text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="card-icon text-primary mb-3"><i class="bi bi-shield-lock-fill"></i></div>
                    <h3 class="h5 fw-bold text-dark mb-2">Log Accessi</h3>
                    <p class="text-muted small mb-0">Monitora la sicurezza e le sessioni attive</p>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="notifications.php" class="card card-link action-card h-100 text-decoration-none bg-white text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="card-icon text-danger mb-3"><i class="bi bi-chat-left-dots-fill"></i></div>
                    <h3 class="h5 fw-bold text-dark mb-2">Messaggi</h3>
                    <p class="text-muted small mb-0">Gestisci le comunicazioni degli utenti</p>
                </a>
            </div>
        </div>

    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>