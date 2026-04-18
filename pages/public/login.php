<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');

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
                $_SESSION['id_utente'] = $utente['id_utente'];
                $_SESSION['username']  = $utente['username'];
                $_SESSION['id_profilo'] = $utente['id_profilo'];

                $user->createDataLogin(date('Y-m-d H:i:s'), session_id(), $utente['id_utente']);

                header("Location: " . ($utente['id_profilo'] == 1 ? "/pages/admin/admin_area.php" : "/index.php"));
                exit();
            } else { $errore = "Utente non attivo"; }
        } else { $errore = "Dati non validi"; }
    } catch (PDOException $e) { $errore = "Errore: " . $e->getMessage(); }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>

    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 vh-100">
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center position-relative px-4">

                <a href="javascript:void(0)" 
                    onclick="closeAndRedirect()" 
                    class="btn-close position-absolute top-0 start-0 m-4" 
                    aria-label="Close">
                </a>

                <div style="max-width: 400px; width: 100%;">
                    <h1 class="display-6 fw-bolder mb-2">Accedi</h1>
                    <p class="text-secondary mb-5">Usa il tuo username</p>

                    <?php if ($errore): ?>
                        <div class="alert alert-danger border-0 small py-2 mb-4"><?= htmlspecialchars($errore) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-4">
                            <input type="text" name="username" class="form-control bg-light border-light py-3" 
                                   placeholder="Username" required>
                        </div>
                        
                        <div class="mb-5">
                            <input type="password" name="password" class="form-control bg-light border-light py-3" 
                                   placeholder="Password" required>
                        </div>

                        <button type="submit" name="login" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">Accedi</button>
                    </form>

                    <div class="text-center small">
                        <p class="text-secondary">Non hai un account? <a href="signup.php" class="text-dark text-decoration-none fw-bold">Registrati</a></p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block bg-secondary" 
                 style="background-image: url('/assets/img/interstellar.jpg'); background-size: cover; background-position: center;">
            </div>
        </div>
    </div>
    
    <script src="/assets/js/script.js"></script>
</body>
</html>