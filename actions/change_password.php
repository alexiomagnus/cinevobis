<?php
session_start();

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/connection.php');
require_once(__DIR__ . '/../includes/user_obj.php');

$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /pages/login.php");
    exit();
}

$errore    = '';
$messaggio = '';

if (isset($_POST['cambia_password'])) {
    $password_attuale = $_POST['password_attuale'] ?? '';
    $nuova_password   = $_POST['nuova_password']   ?? '';
    $conferma         = $_POST['conferma_password'] ?? '';

    if (!$password_attuale || !$nuova_password || !$conferma) {
        $errore = "Compila tutti i campi";
    } elseif ($nuova_password !== $conferma) {
        $errore = "Le nuove password non coincidono";
    } elseif ($password_attuale === $nuova_password) {
        $errore = "La nuova password deve essere diversa dalla attuale";
    } else {
        try {
            $user = new userObj($conn, $username);
            $risultato = $user->changePassword($password_attuale, $nuova_password);

            if ($risultato['ok']) {
                $messaggio = "Password aggiornata con successo";
            } else {
                $errore = $risultato['errore'];
            }
        } catch (PDOException $e) {
            $errore = "Errore"; 
            error_log("Errore: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambia Password - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid">

        <a href="javascript:void(0)" 
            onclick="closeAndRedirect()" 
            class="btn-close position-absolute top-0 start-0 m-4" 
            aria-label="Close">
        </a>

        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4 px-4">
                
                <div class="text-center mb-5">
                    <h1 class="display-6 fw-bolder mb-2">Sicurezza</h1>
                    <p class="text-secondary">Aggiorna la password</p>
                </div>

                <?php if ($errore): ?>
                    <div class="alert alert-danger border-0 small py-2 mb-4 text-center"><?= htmlspecialchars($errore) ?></div>
                <?php endif; ?>
                
                <?php if ($messaggio): ?>
                    <div class="alert alert-success border-0 small py-2 mb-4 text-center"><?= htmlspecialchars($messaggio) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4 position-relative password-wrapper">
                        <label class="form-label small text-secondary">Password attuale</label>
                        <input type="password" name="password_attuale" id="password_attuale" class="form-control bg-light border-light py-3" placeholder="Password attuale" required>
                        <i class="bi bi-eye toggle-icon" data-target="password_attuale"></i>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="mb-4 position-relative password-wrapper">
                        <label class="form-label small text-secondary">Nuova password</label>
                        <input type="password" name="nuova_password" id="nuova_password" class="form-control bg-light border-light py-3" placeholder="Nuova password" required>
                        <i class="bi bi-eye toggle-icon" data-target="nuova_password"></i>
                    </div>

                    <div class="mb-5 position-relative password-wrapper">
                        <label class="form-label small text-secondary">Conferma nuova password</label>
                        <input type="password" name="conferma_password" id="conferma_password" class="form-control bg-light border-light py-3" placeholder="Ripeti nuova password" required>
                        <i class="bi bi-eye toggle-icon" data-target="conferma_password"></i>
                    </div>

                    <button type="submit" name="cambia_password" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">
                        Salva modifiche
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>