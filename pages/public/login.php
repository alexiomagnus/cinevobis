<?php
/**
 * Pagina di login. Verifica le credenziali dell'utente tramite userObj::findByUsername
 * e password_verify. Se l'autenticazione va a buon fine, rigenera l'ID di sessione
 * per prevenire la Session Fixation, popola le variabili di sessione e, se richiesto,
 * imposta il cookie HMAC "remember_me" con durata 30 giorni. Registra anche
 * la data/ora di accesso tramite userObj::createDataLogin.
 * Gli utenti già autenticati vengono reindirizzati alla home.
 *
 * @note Interagisce con la tabella MariaDB: `utenti`, `sessioni` (tramite userObj).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');

// Controllo utente
if (isset($_SESSION['username'])) {
    header("Location: /index.php");
    exit();
}

$errore = "";

if (isset($_POST['login'])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    try {
        $user   = new userObj($conn, $username, $password);
        $utente = $user->findByUsername();

        if ($utente && password_verify($password, $utente['password'])) {
            if ($utente['attivo'] != 0) {
                // Previene la Session Fixation rigenerando l'ID al cambio di privilegi (login)
                session_regenerate_id(true);

                $_SESSION['id_utente'] = $utente['id_utente'];
                $_SESSION['username']  = $utente['username'];
                $_SESSION['id_profilo'] = $utente['id_profilo'];
                $_SESSION['nome'] = $utente['nome'];

                $user->createDataLogin(date('Y-m-d H:i:s'), session_id(), $utente['id_utente']);

                // --- INIZIO NUOVA LOGICA: COOKIE "RICORDAMI" ---
                if (isset($_POST['remember_me'])) { 
                    // Creiamo una firma usando l'username e la costante SECRET_KEY (che hai messo in config.php)
                    $firma = hash_hmac('sha256', $utente['username'], SECRET_KEY);
                    
                    // Il valore del cookie sarà "username:firma"
                    $valore_cookie = $utente['username'] . ':' . $firma;
                    
                    // Impostiamo il cookie per 30 giorni (86400 secondi * 30)
                    // Il percorso '/' indica che il cookie vale su tutto il sito
                    setcookie('remember_me', $valore_cookie, time() + (86400 * 30), '/'); 
                }
                // --- FINE NUOVA LOGICA ---

                header("Location: /index.php");
                exit();
            } else { 
                $errore = "Utente non attivo"; 
            }
            
        } else { 
            $errore = "Dati non validi"; 
        }

    } catch (PDOException $e) { 
        $errore = "Errore"; 
        error_log("Errore: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
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

                <div style="max-width: 450px; width: 100%;">
                    <h1 class="display-6 fw-bolder mb-2">Accedi</h1>
                    <p class="text-secondary mb-5">Usa il tuo username</p>

                    <?php if ($errore): ?>
                        <div class="alert alert-danger border-0 small py-2 mb-4"><?= htmlspecialchars($errore) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control bg-light border-light py-3" 
                                   placeholder="Username" required>
                        </div>
                        
                        <div class="mb-3 position-relative password-wrapper">
                            <input type="password" name="password" id="password" class="form-control bg-light border-light py-3" 
                                placeholder="Password" required>
                            <i class="bi bi-eye toggle-icon" data-target="password"></i>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" name="remember_me" class="form-check-input" id="rememberMe">
                            <label class="form-check-label text-secondary" for="rememberMe">Ricordami</label>
                        </div>

                        <button type="submit" name="login" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">Accedi</button>
                    </form>

                    <p class="text-center small text-secondary">Non hai un account? <a href="signup.php" class="text-dark fw-bold text-decoration-none">Registrati</a></p>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block bg-secondary" 
                 style="background-image: url('/assets/img/breakingbad.jpeg'); background-size: cover; background-position: center;">
            </div>
        </div>
    </div>
    
    <script src="/assets/js/script.js"></script>
</body>
</html>