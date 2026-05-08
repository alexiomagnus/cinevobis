<?php
// --- Cookie di sessione ---
ini_set('session.cookie_httponly', 1);      // JS non può leggere il cookie
ini_set('session.cookie_samesite', 'Lax');  // Protezione CSRF base
session_start();

// --- Gestione errori ---
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');
error_reporting(E_ALL);

// --- Scadenza sessione per inattività ---
define('SESSION_TIMEOUT', 3600);

if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        
        // Aggiorna il DB se c'è un utente loggato
        if (isset($_SESSION['username'])) {
            require_once(__DIR__ . '/connection.php');
            require_once(__DIR__ . '/../includes/user_obj.php');

            try {
                $user = new userObj($conn, $_SESSION['username']);
                $user->setDataLogout(date('Y-m-d H:i:s'), session_id());

            } catch (Exception $e) {
                error_log("Errore logout automatico: " . $e->getMessage());
            }
        }
        
        session_unset();
        session_destroy();
        header("Location: /login.php?error=session_expired");
        exit();
    }
}

$_SESSION['last_activity'] = time();