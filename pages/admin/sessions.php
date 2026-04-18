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
$righe = $_GET['righe'] ?? 15;
$sessioni = $user->readAccess($righe);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area sessioni - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 mb-5 pb-5 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fs-4 fw-bold mb-0">Sessioni registrate</h1>
            
            <form method="GET" class="d-flex align-items-center gap-2">
                <label class="small text-muted mb-0">Righe:</label>
                <input type="number" name="righe" class="form-control form-control-sm" style="width: 70px;" min="1" value="<?= htmlspecialchars($righe) ?>">
                <button type="submit" class="btn btn-sm btn-brand">Aggiorna</button>
            </form>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="border-0 ps-3">Username</th>
                            <th class="border-0">Data Login</th>
                            <th class="border-0">Data Logout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessioni as $sessione): ?>
                            <tr>
                                <td><?= htmlspecialchars($sessione['username']) ?></td>
                                <td><span class="badge bg-light text-dark fw-normal border"><?= htmlspecialchars($sessione['data_login'] ?? '') ?></span></td>
                                <td>
                                    <?php if (!empty($sessione['data_logout'])): ?>
                                        <span class="badge bg-light text-muted fw-normal border"><?= htmlspecialchars($sessione['data_logout']) ?></span>
                                    <?php else: ?>
                                        <span class="text-primary small italic">In corso...</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>