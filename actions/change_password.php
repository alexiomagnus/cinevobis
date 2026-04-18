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
    
    <style>
        .card-auth {
            border: 1px solid #dee2e6;
            border-radius: 0.75rem;
        }
        .form-label {
            font-size: 0.875rem;
            color: #495057;
            margin-bottom: 0.4rem;
        }
        .form-control {
            padding: 0.6rem 0.75rem;
            border-radius: 0.5rem;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(229, 148, 15, 0.25);
            border-color: #e5940f;
            outline: 0;
        }
        .btn-action {
            padding: 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../includes/header.php'); ?>

    <main class="container flex-grow-1 d-flex justify-content-center align-items-center py-5">
        <div class="card card-auth shadow-sm" style="width: 100%; max-width: 400px;">
            <div class="card-body p-4">
                
                <div class="mb-4">
                    <h4 class="fw-bold m-0">Cambia password</h4>
                </div>

                <?php if ($errore): ?>
                    <div class="alert alert-danger py-2 border-0 mb-3" role="alert">
                        <small><?= htmlspecialchars($errore) ?></small>
                    </div>
                <?php endif; ?>

                <?php if ($messaggio): ?>
                    <div class="alert alert-success py-2 border-0 mb-3" role="alert">
                        <small><?= htmlspecialchars($messaggio) ?></small>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password attuale</label>
                        <input type="password" name="password_attuale" class="form-control" 
                               placeholder="Password attuale" required>
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nuova password</label>
                        <input type="password" name="nuova_password" class="form-control" 
                               placeholder="Nuova password" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Conferma nuova password</label>
                        <input type="password" name="conferma_password" class="form-control" 
                               placeholder="Conferma password" required>
                    </div>

                    <button type="submit" name="cambia_password" class="btn btn-dark btn-action w-100 fw-bold">
                        Salva modifiche
                    </button>
                    
                    <div class="text-center mt-4">
                        <a href="/profilo.php" class="text-decoration-none small text-secondary">
                            Torna al profilo
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </main>

    <?php require_once(__DIR__ . '/../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>