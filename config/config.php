<?php
// Impedisce a JS di leggere il cookie di sessione
ini_set('session.cookie_httponly', 1);

// Avvia la sessione
session_start();

// Chiave segreta
define('SECRET_KEY', 'secret_key_for_cinevobis_project_2000');

// Logica cookie remember me
if (!isset($_SESSION['username']) && isset($_COOKIE['remember_me'])) {
    
    // Dividiamo il cookie in due parti usando i due punti (:) come separatore
    $parti_cookie = explode(':', $_COOKIE['remember_me']);
    
    // Assicuriamoci che ci siano esattamente 2 parti (username e firma)
    if (count($parti_cookie) === 2) {
        $cookie_user = $parti_cookie[0];
        $cookie_firma = $parti_cookie[1];
        
        // Ricalcoliamo la firma per vedere se l'username è stato manomesso
        $firma_attesa = hash_hmac('sha256', $cookie_user, SECRET_KEY);
        
        // Se le firme combaciano, l'utente è autentico, allora ricreiamo la sessione
        if (hash_equals($firma_attesa, $cookie_firma)) {
            $_SESSION['username'] = $cookie_user;
            $_SESSION['last_update'] = time();
        } else {
            // Qualcuno ha cercato di manomettere il cookie, lo si cancella
            setcookie('remember_me', '', time() - 3600, '/');
        }
    }
}

// Gestione Errori 
ini_set('display_errors', 0);                       // Nascondi gli errori all'utente
ini_set('log_errors', 1);                           // Attiva il log su file
ini_set('error_log', __DIR__ . '/php_errors.log');  // Salva il log nella stessa cartella di questo config.php
error_reporting(E_ALL);                             // Riporta tutti gli errori

$scadenza = 1800;                                   // 30 minuti

if (isset($_SESSION['last_update']) && (time() - $_SESSION['last_update'] > $scadenza)) {
    
    // Se la sessione è scaduta MA l'utente NON ha il cookie remember me, lo buttiamo fuori
    // Se invece ha il cookie, ignoriamo l'if e il suo tempo verrà aggiornato in automatico
    if (!isset($_COOKIE['remember_me'])) {
        session_unset();                                // Libera tutte le variabili di sessione
        session_destroy();                              // Distruzione della sessione
        header("Location: login.php?error=timeout");
        exit();
    }
}

// Aggiorna il tempo dell'ultima attività (così chi ha il cookie o è attivo rinnova il timer)
$_SESSION['last_update'] = time();
?>