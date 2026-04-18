<?php
session_start();

require_once(__DIR__ . '/../config/connection.php');
require_once(__DIR__ . '/../includes/user_obj.php');
require_once(__DIR__ . '/../includes/header_logic.php');

$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}

$user = new userObj($conn, $username);
$userData = $user->findByUsername();

$nazione = "";

if ($userData) {
    if ($userData['data_registrazione']) {
        $date = new DateTime($userData['data_registrazione']);
        $dataRegistrazione = $date->format('Y');
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../includes/header.php'); ?>

    <div class="container flex-grow-1 d-flex justify-content-center align-items-center my-5">
        <div class="w-100" style="max-width: 900px;"> 
            <div class="p-5 bg-white shadow-sm" style="border-radius: 1.5rem; border: 1px solid #eee;">
                <h2 class="fw-bold mb-4 text">Dettagli Utente</h2>
                <hr class="mb-4">
                
                <?php if ($userData): ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Username:</strong><br> <?= htmlspecialchars($userData['username']) ?></p>
                            <p class="mb-2"><strong>Nome:</strong><br> <?= htmlspecialchars($userData['nome']) ?></p>
                            <p class="mb-2"><strong>Cognome:</strong><br> <?= htmlspecialchars($userData['cognome']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Email:</strong><br> <?= htmlspecialchars($userData['email']) ?></p>
                            <p class="mb-2"><strong>Data Registrazione:</strong><br> <?= htmlspecialchars($dataRegistrazione); ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Utente non trovato.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . '/../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>