<?php
session_start();

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/connection.php');
require_once(__DIR__ . '/../includes/user_obj.php');

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

    <div class="container-fluid p-0">
        <div class="row g-0 vh-100 justify-content-center align-items-center position-relative">
            
            <a href="javascript:void(0)" 
                onclick="history.back()"
                class="btn-close position-absolute top-0 start-0 m-4"
                aria-label="Close">
            </a>

            <div class="col-12 col-md-10 col-lg-8 col-xl-6 px-4">
                <div class="text-center mb-5">
                    <h1 class="display-6 fw-bolder mb-2">Profilo</h1>
                    <p class="text-secondary">Informazioni del tuo account Cinevobis</p>
                </div>

                <?php if ($userData): ?>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="small text-uppercase text-muted fw-bold">Username</label>
                            <p class="fs-5 mb-4 border-bottom pb-2"><?= htmlspecialchars($userData['username']) ?></p>
                            
                            <label class="small text-uppercase text-muted fw-bold">Nome</label>
                            <p class="fs-5 mb-4 border-bottom pb-2"><?= htmlspecialchars($userData['nome'] ?? 'Non specificato') ?></p>
                            
                            <label class="small text-uppercase text-muted fw-bold">Cognome</label>
                            <p class="fs-5 mb-4 border-bottom pb-2"><?= htmlspecialchars($userData['cognome'] ?? 'Non specificato') ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-uppercase text-muted fw-bold">Email</label>
                            <p class="fs-5 mb-4 border-bottom pb-2"><?= htmlspecialchars($userData['email']) ?></p>
                            
                            <label class="small text-uppercase text-muted fw-bold">Membro dal</label>
                            <p class="fs-5 mb-4 border-bottom pb-2"><?= htmlspecialchars($dataRegistrazione); ?></p>
                        </div>
                    </div>
                    
                    <div class="mt-5 text-center">
                        <a href="logout.php" class="btn btn-outline-danger px-5">Logout</a>
                    </div>

                <?php else: ?>
                    <div class="alert alert-warning text-center">Utente non trovato.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>