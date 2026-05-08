<?php
/**
 * Gestisce il processo di logout: registra la data/ora di uscita nel DB,
 * invalida la sessione e cancella il cookie "remember_me" se presente.
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
    // Esegui sempre la distruzione, indipendentemente dal successo del DB
    destroy_session_and_redirect();
}

/**
 * Cancella completamente la sessione e il cookie "remember_me", quindi
 * reindirizza l'utente alla home con il flag di logout avvenuto.
 * Viene chiamata nel blocco `finally` per garantire l'esecuzione in ogni caso.
 *
 * @return void
 */
function destroy_session_and_redirect() {

    // AGGIUNTA: Cancella il cookie "Ricordami" impostando la scadenza nel passato
    if (isset($_COOKIE['remember_me'])) {
        setcookie('remember_me', '', time() - 3600, '/');
    }

    // Cancella il cookie di sessione nel browser
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        
        setcookie(session_name(), '', time() - 42000, 
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_unset();                                
    session_destroy();                              
    header("Location: /index.php?logout=success");
    exit();
}