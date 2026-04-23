<?php
session_start();

require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');

$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}

$user = new userObj($conn, $username);
$userData = $user->findByUsername();

$dataRegistrazione = "N/D";

if ($userData && $userData['data_registrazione']) {
    $date = new DateTime($userData['data_registrazione']);
    $dataRegistrazione = $date->format('Y');
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
<body>

    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 vh-100">
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center position-relative px-4">
                
                <a href="javascript:void(0)" 
                    onclick="history.back()"
                    class="btn-close position-absolute top-0 start-0 m-4"
                    aria-label="Close">
                </a>

                <div style="max-width: 500px; width: 100%;">
                    <h1 class="display-6 fw-bolder mb-2">Profilo</h1>
                    <p class="text-secondary mb-5">Informazioni del tuo account Cinevobis</p>

                    <?php if ($userData): ?>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="small text-uppercase text-muted fw-bold">Username</label>
                                <p class="fs-6 mb-0 border-bottom pb-2"><?= htmlspecialchars($userData['username']) ?></p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="small text-uppercase text-muted fw-bold">Email</label>
                                <p class="fs-6 mb-0 border-bottom pb-2"><?= htmlspecialchars($userData['email']) ?></p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small text-uppercase text-muted fw-bold">Nome</label>
                                <p class="fs-6 mb-0 border-bottom pb-2"><?= htmlspecialchars($userData['nome'] ?? 'Non specificato') ?></p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="small text-uppercase text-muted fw-bold">Cognome</label>
                                <p class="fs-6 mb-0 border-bottom pb-2"><?= htmlspecialchars($userData['cognome'] ?? 'Non specificato') ?></p>
                            </div>

                            <div class="col-12 mb-5">
                                <label class="small text-uppercase text-muted fw-bold">Membro dal</label>
                                <p class="fs-6 mb-0 border-bottom pb-2"><?= htmlspecialchars($dataRegistrazione); ?></p>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-3">
                            <a href="/actions/change_password.php" class="btn btn-dark py-3 fw-bold">Cambia password</a>
                            <a href="/actions/logout.php" class="btn btn-outline-danger py-3 fw-bold">Logout</a>
                        </div>

                    <?php else: ?>
                        <div class="alert alert-warning text-center">Utente non trovato.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block bg-secondary" 
                 style="background-image: url('/assets/img/ocean.jpeg'); background-size: cover; background-position: center;">
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>