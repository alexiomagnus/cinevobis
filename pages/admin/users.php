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

$user     = new userObj($conn, $username);
$utenti   = $user->readAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione utenti - Cinevobis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 flex-grow-1">
        <p class="fs-5 fw-bold mb-3">Utenti registrati</p>
        <table class="table table-sm mb-5">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Città</th>
                    <th>Email</th>
                    <th>Profilo</th>
                    <th>Nazione</th>
                    <th>Attivo</th>
                    <th>Modifica</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utenti as $utente): ?>
                    <tr>
                        <td><?= htmlspecialchars($utente['username']) ?></td>
                        <td><?= htmlspecialchars($utente['nome'] ?? '') ?></td>
                        <td><?= htmlspecialchars($utente['cognome'] ?? '') ?></td>
                        <td><?= htmlspecialchars($utente['citta'] ?? '') ?></td>
                        <td><?= htmlspecialchars($utente['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($utente['nome_profilo'] ?? '') ?></td>
                        <td><?= htmlspecialchars($utente['nome_nazione'] ?? '') ?></td>
                        <td><?= $utente['attivo'] ? 'Sì' : 'No' ?></td>
                        <td>
                            <form method="GET" action="edit_user.php">
                                <input type="hidden" name="username" value="<?= htmlspecialchars($utente['username']) ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Modifica</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
</body>
</html>