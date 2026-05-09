<?php
/**
 * Pagina del profilo utente (riservata agli utenti autenticati).
 * Mostra le informazioni anagrafiche dell'account, l'anno di registrazione
 * e il numero di film visti nell'anno corrente. Gestisce due azioni POST:
 * - change_password: reindirizza alla pagina di cambio password.
 * - delete_user: elimina l'account tramite userObj::delete, distrugge
 *   la sessione e reindirizza alla home.
 *
 * @note Interagisce con le tabelle MariaDB: `utenti` (tramite userObj), `watched`.
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');

$username = $_SESSION['username'] ?? '';

// Se non c'è l'utente in sessione
if (!$username) {
    header("Location: /index.php");
    exit();
}

$userData = null;
$dataRegistrazione = "N/D";
$user = new userObj($conn, $username);

// Recuperiamo i dati utente
$userData = $user->findByUsername();
if ($userData && $userData['data_registrazione']) {
    $date = new DateTime($userData['data_registrazione']);
    $dataRegistrazione = $date->format('Y');
}

// Gestione Cambia Password
if (isset($_POST['change_password'])) {
    header("Location: /actions/change_password.php");
    exit();
}

// Gestione Eliminazione Account (Porta subito alla Home)
if (isset($_POST['delete_user']) && $username) {
    try {
        if ($user->delete()) {
            // Distruggiamo la sessione per sicurezza prima del redirect
            session_destroy();
            header("Location: /index.php");
            exit();
        }
    } catch (PDOException $e) {
        $errore = "Errore durante l'eliminazione: " . $e->getMessage();
    }
}

// Film visti nell'anno corrente
$count = 0;

try {
    $sql = "SELECT COUNT(*) FROM watched WHERE id_utente = :id_utente AND YEAR(data_aggiunto) = YEAR(CURRENT_DATE)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_utente' => $_SESSION['id_utente']]);

    $count = $stmt->fetchColumn();

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body style="background-color: var(--bg);">

    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 vh-100">
            
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center position-relative px-4 vh-100 overflow-hidden">
                
                <a href="javascript:void(0)"
                   onclick="closeAndRedirect()"
                   class="btn-close position-absolute top-0 start-0 m-4"
                   aria-label="Close">
                </a>

                <div style="max-width: 550px; width: 100%;">
                    
                    <?php if (isset($errore)): ?>
                        <div class="alert alert-danger mb-4" role="alert">
                            <?= htmlspecialchars($errore) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($userData): ?>
                        
                        <div class="mb-5">
                            <h1 class="display-6 fw-bolder mb-1">Profilo</h1>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 rounded-4" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: rgba(99, 102, 241, 0.1); color: var(--accent);">
                                            <i class="bi bi-film fs-5"></i>
                                        </div>
                                        <div>
                                            <p class="small fw-bold mb-0" style="color: var(--text-muted);">Film visti nel <?= date('Y') ?></p>
                                            <h4 class="mb-0 fw-bolder"><?= htmlspecialchars($count); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-4" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: rgba(99, 102, 241, 0.1); color: var(--accent);">
                                            <i class="bi bi-calendar-check fs-5"></i>
                                        </div>
                                        <div>
                                            <p class="small fw-bold mb-0" style="color: var(--text-muted);">Membro dal</p>
                                            <h4 class="mb-0 fw-bolder"><?= htmlspecialchars($dataRegistrazione); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-4 mb-4" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
                            <div class="d-flex justify-content-between p-3 border-bottom">
                                <span style="color: var(--text-muted);">Username</span>
                                <span class="fw-medium text-end"><?= htmlspecialchars($userData['username']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between p-3 border-bottom">
                                <span style="color: var(--text-muted);">Email</span>
                                <span class="fw-medium text-end"><?= htmlspecialchars($userData['email']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between p-3 border-bottom">
                                <span style="color: var(--text-muted);">Nome</span>
                                <span class="fw-medium text-end"><?= htmlspecialchars($userData['nome'] ?? 'Non inserito') ?></span>
                            </div>
                            <div class="d-flex justify-content-between p-3">
                                <span style="color: var(--text-muted);">Cognome</span>
                                <span class="fw-medium text-end"><?= htmlspecialchars($userData['cognome'] ?? 'Non inserito') ?></span>
                            </div>
                        </div>
                        
                        <form method="POST">
                            <div class="rounded-4 overflow-hidden" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                                <div class="p-1 border-bottom">
                                    <button type="submit" name="change_password" class="btn w-100 d-flex justify-content-between align-items-center text-start border-0" style="color: var(--text); padding: 12px;">
                                        <span class="fw-medium">Modifica la password</span>
                                        <i class="bi bi-chevron-right" style="color: var(--text-muted);"></i>
                                    </button>
                                </div>
                                <div class="p-1 bg-danger bg-opacity-10">
                                    <button type="submit" name="delete_user" class="btn w-100 d-flex justify-content-between align-items-center text-start text-danger border-0" style="padding: 12px;" onclick="return confirm('Stai per eliminare definitivamente il tuo account su Cinevobis. Confermi?');">
                                        <span class="fw-bold">Elimina account</span>
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                            
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block" 
                 style="background-image: url('/assets/img/astronaut.jpeg'); background-size: cover; background-position: center; border-left: 1px solid var(--border);">
            </div>

        </div>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>