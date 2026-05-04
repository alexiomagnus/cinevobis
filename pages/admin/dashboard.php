<?php
session_start();
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
    <title>Dashboard - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container flex-grow-1 py-5">

        <!-- Titolo dashboard -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="fw-bold mb-0">Dashboard</h1>
            </div>
        </div>

        <!-- Statistiche -->
        <div class="row g-3 mb-5">
            <div class="col-6 col-lg-3">
                <div class="p-3 rounded-2 bg-body-secondary">
                    <div class="text-muted small mb-1">Film nel catalogo</div>
                    <div class="fs-3 fw-medium"><?= $totaleFilm ?></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="p-3 rounded-2 bg-body-secondary">
                    <div class="text-muted small mb-1">Utenti</div>
                    <div class="fs-3 fw-medium"><?= $totaleUtenti ?></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="p-3 rounded-2 bg-body-secondary">
                    <div class="text-muted small mb-1">Sessioni</div>
                    <div class="fs-3 fw-medium"><?= $totaleSessioni ?></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="p-3 rounded-2 bg-body-secondary">
                    <div class="text-muted small mb-1">Notifiche da leggere</div>
                    <div class="fs-3 fw-medium"><?= $totaleNotifiche ?></div>
                </div>
            </div>
        </div>

        <!-- Sezioni di gestione -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h5 fw-semibold text-uppercase text-muted mb-3">
                    Gestione
                </h2>
            </div>
        </div>

        <div class="row g-4">
            <!-- Gestione film -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="films.php" class="text-decoration-none h-100 d-block">
                    <div class="card border-0 shadow-sm text-center p-4 h-100 card-hover">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="display-4 mb-3 text-warning">
                                <i class="bi bi-film"></i>
                            </div>
                            <h2 class="h4 fw-bold mb-2 text-dark">Gestione film</h2>
                            <p class="text-muted mb-0 small">Visualizza i film presenti nel database</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Gestione utenti -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="users.php" class="text-decoration-none h-100 d-block">
                    <div class="card border-0 shadow-sm text-center p-4 h-100 card-hover">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="display-4 mb-3 text-success">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h2 class="h4 fw-bold mb-2 text-dark">Gestione utenti</h2>
                            <p class="text-muted mb-0 small">Visualizza e gestisci gli utenti</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Gestione sessioni -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="sessions.php" class="text-decoration-none h-100 d-block">
                    <div class="card border-0 shadow-sm text-center p-4 h-100 card-hover">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="display-4 mb-3 text-primary">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <h2 class="h4 fw-bold mb-2 text-dark">Gestione sessioni</h2>
                            <p class="text-muted mb-0 small">Visualizza le sessioni</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Gestione notifiche -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="notifications.php" class="text-decoration-none h-100 d-block">
                    <div class="card border-0 shadow-sm text-center p-4 h-100 card-hover">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="display-4 mb-3 text-danger">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                            <h2 class="h4 fw-bold mb-2 text-dark">Gestione notifiche</h2>
                            <p class="text-muted mb-0 small">Visualizza i report inviati dagli utenti</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>