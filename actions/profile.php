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

if (isset($_POST['change_pwd'])) {
    header("Location: /actions/change_password.php");
    exit();
}

if (isset($_POST['logout'])) {
    header("Location: /actions/logout.php");
    exit();
}

$user = new userObj($conn, $username);
$userData = $user->findByUsername();

$profilo = "";
$nazione = "";
if ($userData) {
    if ($userData['id_profilo']) {
        $stmt = $conn->prepare("SELECT nome_profilo FROM profili WHERE id_profilo = ?");
        $stmt->execute([$userData['id_profilo']]);
        $profilo = $stmt->fetchColumn();
    }
    if ($userData['iso_code']) {
        $stmt = $conn->prepare("SELECT nome_nazione FROM nazioni WHERE iso_code = ?");
        $stmt->execute([$userData['iso_code']]);
        $nazione = $stmt->fetchColumn();
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo - Cinevobis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../includes/header.php'); ?>

    <div class="container flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="card shadow-sm border-0" style="width: 100%; max-width: 640px;">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4">Dettagli Utente</h4>
                <?php if ($userData): ?>
                    <p><strong>Username:</strong> <?= htmlspecialchars($userData['username']) ?></p>
                    <p><strong>Nome:</strong> <?= htmlspecialchars($userData['nome']) ?></p>
                    <p><strong>Cognome:</strong> <?= htmlspecialchars($userData['cognome']) ?></p>
                    <p><strong>Città:</strong> <?= htmlspecialchars($userData['citta']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($userData['email']) ?></p>
                    <p><strong>Profilo:</strong> <?= htmlspecialchars($profilo) ?></p>
                    <p><strong>Nazione:</strong> <?= htmlspecialchars($nazione) ?></p>
                    <p><strong>Data Registrazione:</strong> <?= htmlspecialchars($userData['data_registrazione']) ?></p>

                    <div class="mt-4">
                        <form method="POST">
                            <button class="btn btn-primary" name="change_pwd">Cambia password</button>
                            <button class="btn btn-danger" name="logout">Logout</button>
                        </form>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Utente non trovato.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . '/../includes/footer.php'); ?>
</body>
</html>