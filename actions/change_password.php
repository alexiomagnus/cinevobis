<?php
session_start();
require_once(__DIR__ . '/../config/connection.php');
require_once(__DIR__ . '/../includes/user_obj.php');

$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}

$errore    = '';
$messaggio = '';

if (isset($_POST['cambia_password'])) {
    $password_attuale = $_POST['password_attuale']       ?? '';
    $nuova_password = $_POST['nuova_password']         ?? '';
    $conferma = $_POST['conferma_password']      ?? '';

    if (!$password_attuale || !$nuova_password || !$conferma) {
        $errore = "Compila tutti i campi";
    } elseif ($nuova_password !== $conferma) {
        $errore = "La nuova password e la conferma non coincidono";
    } elseif ($password_attuale === $nuova_password) {
        $errore = "La nuova password deve essere diversa da quella attuale";
    } else {
        try {
            $user = new userObj($conn, $username);
            $risultato = $user->changePassword($password_attuale, $nuova_password);

            if ($risultato['ok']) {
                $messaggio = "Password aggiornata";
            } else {
                $errore = $risultato['errore'];
            }
        } catch (PDOException $e) {
            $errore = "Errore: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambia password - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../includes/header.php'); ?>

    <div class="container flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="card shadow-sm border-0 p-4" style="width: 100%; max-width: 480px;">
            <div class="card-body">

                <h4 class="fw-bold mb-4">Cambia password</h4>

                <?php if ($errore): ?>
                    <div class="alert alert-danger py-2 small" role="alert">
                        <?= htmlspecialchars($errore) ?>
                    </div>
                <?php endif; ?>

                <?php if ($messaggio): ?>
                    <div class="alert alert-success py-2 small" role="alert">
                        <?= htmlspecialchars($messaggio) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password attuale</label>
                        <input type="password" name="password_attuale" class="form-control"
                               placeholder="••••••••" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nuova password</label>
                        <input type="password" name="nuova_password" class="form-control"
                               placeholder="••••••••" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Conferma nuova password</label>
                        <input type="password" name="conferma_password" class="form-control"
                               placeholder="••••••••" required>
                    </div>
                    <button type="submit" name="cambia_password" class="btn btn-dark w-100">Aggiorna password</button>
                </form>

            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . '/../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>