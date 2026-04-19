<?php
session_start();

require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area amministratore - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container flex-grow-1 d-flex flex-column justify-content-center py-5">

        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="fw-bold">Profilo Amministratore</h1>
                <p class="text-secondary">Gestisci i contenuti del sito da qui</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">

            <div class="col-12 col-md-6 col-lg-4">
                <a href="add_film.php" class="text-decoration-none h-100 d-block">
                    <div class="card border-0 shadow-sm text-center p-4 h-100 card-hover">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="display-4 mb-3 text-primary">
                                <i class="bi bi-film"></i>
                            </div>
                            <h2 class="h4 fw-bold mb-3 text-dark">Aggiungi film</h2>
                            <p class="text-muted mb-0">Carica un nuovo film nel sistema</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="users.php" class="text-decoration-none h-100 d-block">
                    <div class="card border-0 shadow-sm text-center p-4 h-100 card-hover">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="display-4 mb-3 text-success">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h2 class="h4 fw-bold mb-3 text-dark">Gestione utenti</h2>
                            <p class="text-muted mb-0">Visualizza utenti registrati</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="sessions.php" class="text-decoration-none h-100 d-block">
                    <div class="card border-0 shadow-sm text-center p-4 h-100 card-hover">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="display-4 mb-3 text-warning">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <h2 class="h4 fw-bold mb-3 text-dark">Gestione sessioni</h2>
                            <p class="text-muted mb-0">Visualizza sessioni registrate</p>
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