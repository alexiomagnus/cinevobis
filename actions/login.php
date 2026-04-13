<?php
session_start();
require_once(__DIR__ . '/../config/connection.php');
require_once(__DIR__ . '/../includes/user_obj.php');

$errore = "";

if (isset($_POST['login'])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    try {
        $user   = new userObj($conn, $username, $password);
        $utente = $user->findByUsername();

        if ($utente && password_verify($password, $utente['password'])) {
            if ($utente['attivo'] != 0) {
                session_regenerate_id(true);

                $id_sessione = session_id();
                $_SESSION['id_utente'] = $utente['id_utente'];
                $_SESSION['username']  = $utente['username'];
                $_SESSION['id_profilo'] = $utente['id_profilo'];

                $user->createDataLogin(date('Y-m-d H:i:s'), $id_sessione, $utente['id_utente']);

                if ($utente['id_profilo'] == 1) {
                    header("Location: /pages/admin/admin_area.php");
                } else {
                    header("Location: /index.php");
                }
                exit();
            } else {
                $errore = "Utente non attivo";
            }
        } else {
            $errore = "Dati non validi";
        }
    } catch (PDOException $e) {
        $errore = "Errore: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login -  Cinevobis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../includes/header.php'); ?>

    <div class="container flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="card shadow-sm border-0 p-4" style="max-width: 520px; width: 100%;">
            <div class="card-body">
                <h2 class="text-center mb-4 fw-bold text-primary">Login</h2>

                <?php if ($errore): ?>
                    <div class="alert alert-danger py-2 small text-center" role="alert">
                        <?= htmlspecialchars($errore) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label small fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control"
                               placeholder="Inserisci username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label small fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control"
                               placeholder="••••••••" required>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="login" class="btn btn-primary py-2">Accedi</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <span class="text-muted small">Sei nuovo?</span>
                    <a href="signup.php" class="small text-decoration-none fw-bold">Registrati</a>
                </div>
            </div>
        </div>
    </div>
    
    <?php require_once(__DIR__ . '/../includes/footer.php'); ?>
</body>
</html>