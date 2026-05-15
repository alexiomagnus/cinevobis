<?php
// File di configurazione
require_once(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;

// Inizializza Dotenv puntando alla cartella del progetto
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

// --- Cookie di sessione ---
ini_set('session.cookie_httponly', 1);      // JS non può leggere il cookie
ini_set('session.cookie_samesite', 'Lax');  // Protezione CSRF base
session_start();

// Chiave segreta per firmare i cookie HMAC
define('SECRET_KEY', $_ENV['SECRET_KEY']);

// --- Controllo Auto-Login (Remember Me) ---
if (!isset($_SESSION['username']) && isset($_COOKIE['remember_me'])) {
    $valore_cookie = $_COOKIE['remember_me'];
    $parti = explode('|', $valore_cookie);
    
    if (count($parti) === 2) {
        $username_cookie = $parti[0];
        $firma_cookie = $parti[1];
        
        $firma_ricalcolata = hash_hmac('sha256', $username_cookie, SECRET_KEY);
        
        if (hash_equals($firma_ricalcolata, $firma_cookie)) {
            require_once(__DIR__ . '/connection.php');
            require_once(__DIR__ . '/../includes/user_obj.php');
            
            try {
                $user = new userObj($conn, $username_cookie);
                $utente = $user->findByUsername();
                
                if ($utente && $utente['attivo'] != 0) {
                    session_regenerate_id(true);
                    $_SESSION['id_utente'] = $utente['id_utente'];
                    $_SESSION['username']  = $utente['username'];
                    $_SESSION['id_profilo'] = $utente['id_profilo'];
                    $_SESSION['nome'] = $utente['nome'];
                    $_SESSION['tester'] = $utente['tester'];
                    
                    $user->createDataLogin(date('Y-m-d H:i:s'), session_id(), $utente['id_utente']);
                } else {
                    setcookie('remember_me', '', time() - 3600, '/');
                }
            } catch (Exception $e) {
                error_log("Errore auto-login: " . $e->getMessage());
            }
        } else {
            setcookie('remember_me', '', time() - 3600, '/');
        }
    } else {
        setcookie('remember_me', '', time() - 3600, '/');
    }
}

// --- Gestione errori ---
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');
error_reporting(E_ALL);

// --- Scadenza sessione per inattività ---
define('SESSION_TIMEOUT', 1800);  // 30 minuti

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