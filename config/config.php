<?php
// Impedisce a JS di leggere il cookie di sessione
ini_set('session.cookie_httponly', 1);

// Avvia la sessione
session_start();

// --- Logica scadenza sessioni ---
$timeout = 3600;  // 3600 secondi = 1 ora

if (isset($_SESSION['last_activity'])) {
    // Calcola quanto tempo è passato dall'ultima azione
    $inattivita = time() - $_SESSION['last_activity'];

    if ($inattivita > $timeout) {
        // Se è passato troppo tempo, distruggi la sessione e vai al login
        session_unset();  // Pulisce l'array $_SESSION eliminando le variabili di sessione
        session_destroy();
        header("Location: login.php?error=session_expired");
        exit();
    }
}

// Aggiorna il timestamp dell'ultima attività a ogni caricamento di pagina
$_SESSION['last_activity'] = time();


// Gestione Errori 
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');
error_reporting(E_ALL);