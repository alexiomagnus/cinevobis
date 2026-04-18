<?php
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');

$errore = ""; 
$messaggio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nome = trim($_POST['nome']);
    $cognome = trim($_POST['cognome']);
    $email = trim($_POST['email']);
    $attivo = 1;                            // Default: account attivo
    $id_profilo = 2;                        // Default: ruolo utente
    
    try {  
        $user = new userObj($conn, $username, $password, $nome, $cognome, $email, $attivo, $id_profilo);
        $user->create();
        $messaggio = "Account creato con successo";
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
    <title>Sign up - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .ts-wrapper .ts-control {
            min-height: calc(1.5em + 1rem + 2px) !important;
            padding: 1rem 0.75rem !important;
            background-color: #f8f9fa !important;
            border-color: #f8f9fa !important;
            border-radius: 0.375rem !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            box-shadow: none !important;
        }
        .ts-wrapper .ts-control .item { line-height: 1.5 !important; }
        .ts-wrapper .ts-control .dropdown-indicator { padding-top: 0.25rem !important; }
        .ts-wrapper.focus .ts-control {
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.05) !important;
            border-color: #dee2e6 !important;
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 vh-100">
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center position-relative px-4 py-5 overflow-auto">

                <a href="javascript:void(0)" 
                    onclick="closeAndRedirect()" 
                    class="btn-close position-absolute top-0 start-0 m-4" 
                    aria-label="Close">
                </a>

                <div style="max-width: 450px; width: 100%;">
                    <h1 class="display-6 fw-bolder mb-2">Crea il tuo account</h1>
                    <p class="text-secondary mb-5">Unisciti alla community</p>

                    <?php if ($errore): ?>
                        <div class="alert alert-danger border-0 small py-2 mb-4"><?= htmlspecialchars($errore) ?></div>
                    <?php endif; ?>
                    <?php if ($messaggio): ?>
                        <div class="alert alert-success border-0 small py-2 mb-4"><?= htmlspecialchars($messaggio) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <input type="text" name="nome" class="form-control bg-light border-light py-3" placeholder="Nome" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="cognome" class="form-control bg-light border-light py-3" placeholder="Cognome" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="email" name="email" class="form-control bg-light border-light py-3" placeholder="Email" required>
                        </div>
                        
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control bg-light border-light py-3" placeholder="Username" required>
                        </div>
                        
                        <div class="mb-5">
                            <input type="password" name="password" class="form-control bg-light border-light py-3" placeholder="Password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">Crea un account</button>
                    </form>

                    <p class="text-center small text-secondary">Hai un account? <a href="login.php" class="text-dark fw-bold text-decoration-none">Accedi</a></p>
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