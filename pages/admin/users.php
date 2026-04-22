<?php
session_start();

require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

$username = $_SESSION["username"] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}

$user = new userObj($conn, $username);
$utenti = $user->readAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione utenti - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 mb-5 pb-5 flex-grow-1">
        <h1 class="fs-4 fw-bold mb-4">Gestione utenti</h1>
        
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">Username</th>
                            <th>Identità</th>
                            <th>Email</th>
                            <th>Profilo</th>
                            <th class="text-center">Attivo</th>
                            <th class="text-end pe-3">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utenti as $utente): ?>
                            <tr>
                                <td class="ps-3 fw-bold"><?= htmlspecialchars($utente['username']) ?></td>
                                <td>
                                    <div class="small fw-semibold"><?= htmlspecialchars($utente['nome'] ?? '') ?> <?= htmlspecialchars($utente['cognome'] ?? '') ?></div>
                                </td>
                                <td class="small"><?= htmlspecialchars($utente['email'] ?? '') ?></td>
                                <td><span><?= htmlspecialchars($utente['nome_profilo'] ?? '') ?></span></td>
                                <td class="text-center">
                                    <?php if ($utente['attivo']): ?>
                                        <span class="text-success" title="Attivo">True</span>
                                    <?php else: ?>
                                        <span class="text-danger" title="Inattivo">False</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <form method="GET" action="edit_user.php">
                                        <input type="hidden" name="username" value="<?= htmlspecialchars($utente['username']) ?>">
                                        <button type="submit" class="btn btn-sm btn-brand">
                                            <i class="bi bi-pencil"></i> Modifica
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>