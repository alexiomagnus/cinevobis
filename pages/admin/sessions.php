<?php
session_start();

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
$righe = $_GET['righe'] ?? 10;
$sessioni = $user->readAccess($righe);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area sessioni - Cinevobis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 flex-grow-1">
        <p class="fs-5 fw-bold mb-3">Sessioni registrate</p>

        <form method="GET" class="d-flex align-items-center gap-2 mb-3">
            <label class="mb-0">Righe</label>
            <input type="number" name="righe" class="form-control form-control-sm" style="width: 80px;" min="1" value="<?= htmlspecialchars($righe) ?>">
            <button type="submit" class="btn btn-primary btn-sm">Invia</button>
        </form>

        <table class="table table-sm mb-5">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Data Login</th>
                    <th>Data Logout</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sessioni as $sessione): ?>
                    <tr>
                        <td><?= htmlspecialchars($sessione['username']) ?></td>
                        <td><?= htmlspecialchars($sessione['data_login'] ?? '') ?></td>
                        <td><?= htmlspecialchars($sessione['data_logout'] ?? '—') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
</body>
</html>