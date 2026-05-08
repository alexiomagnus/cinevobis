<?php
/**
 * Gestisce il processo di logout: registra la data/ora di uscita nel DB
 * e invalida la sessione.
 * Al termine reindirizza l'utente alla home con il parametro ?logout=success.
 *
 * @note Interagisce con la tabella MariaDB: `sessioni` (tramite userObj::setDataLogout).
 */
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/connection.php');
require_once(__DIR__ . '/../includes/user_obj.php');

// Controllo accesso
if (!isset($_SESSION['username'])) {
    header("Location: /index.php");
    exit();
}

try {
    $id_sessione = session_id();
    $username = $_SESSION['username'];

    $user = new userObj($conn, $username);
    $user->setDataLogout(date('Y-m-d H:i:s'), $id_sessione);

} catch (Exception $e) {
    error_log("Errore durante il logout: " . $e->getMessage());

} finally {
    // Cancella completamente la sessione e reindirizza l'utente alla home con il flag di logout avvenuto
    // Viene chiamata nel blocco `finally` per garantire l'esecuzione in ogni caso
    session_unset();                                
    session_destroy();                              
    header("Location: /index.php?logout=success");
    exit();
}