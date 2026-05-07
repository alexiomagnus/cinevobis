<?php
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

// Pulisce completamente la sessione e reindirizza
function destroy_session_and_redirect() {

    // Cancella il cookie di sessione nel browser
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        
        setcookie(session_name(), '', time() - 42000,  // 11 ore e mezza 
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_unset();                                // Libera tutte le variabili di sessione
    session_destroy();                              // Distruzione della sessione
    header("Location: /index.php?logout=success");
    exit();
}