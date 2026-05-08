<?php
// Impedisce a JS di leggere il cookie di sessione
ini_set('session.cookie_httponly', 1);

// Avvia la sessione
session_start();

// Gestione Errori 
ini_set('display_errors', 0);                       // Nascondi gli errori all'utente
ini_set('log_errors', 1);                           // Attiva il log su file
ini_set('error_log', __DIR__ . '/php_errors.log');  // Salva il log nella stessa cartella di questo config.php
error_reporting(E_ALL);                             // Riporta tutti gli errori

// LOGICA DI SCADENZA
$scadenza = 1800; // 30 minuti

if (isset($_SESSION['last_update']) && (time() - $_SESSION['last_update'] > $scadenza)) {
    session_unset();                                // Libera tutte le variabili di sessione
    session_destroy();                              // Distruzione della sessione
    header("Location: login.php?error=timeout");
    exit();
}

// Aggiorna il tempo dell'ultima attività
$_SESSION['last_update'] = time();