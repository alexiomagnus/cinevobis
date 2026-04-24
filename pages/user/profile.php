<?php
session_start();

require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');

$username = $_SESSION['username'] ?? '';
$isDeletedMessage = isset($_GET['msg']) && $_GET['msg'] === 'eliminato';

// Se non c'è l'utente in sessione e NON stiamo mostrando il messaggio di eliminazione, vai alla home
if (!$username && !$isDeletedMessage) {
    header("Location: /index.php");
    exit();
}

$userData = null;
$dataRegistrazione = "N/D";

// Recuperiamo i dati utente solo se c'è un utente loggato (evita errori se l'account è appena stato eliminato)
if ($username) {
    $user = new userObj($conn, $username);
    $userData = $user->findByUsername();

    if ($userData && $userData['data_registrazione']) {
        $date = new DateTime($userData['data_registrazione']);
        $dataRegistrazione = $date->format('Y');
    }
}

if (isset($_POST['change_password'])) {
    header("Location: /actions/change_password.php");
    exit();
}

if (isset($_POST['delete_account']) && $username) {
    try {
        $user->delete();

        // Svuota e distruggi la sessione correttamente PRIMA del redirect
        session_unset();
        session_destroy();

        header("Location: profile.php?msg=eliminato");
        exit();
    } catch (PDOException $e) {
        $errore = "Errore durante l'eliminazione: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
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

                <div style="max-width: 500px; width: 100%;">
                    <h1 class="display-6 fw-bolder mb-2">Profilo</h1>
                    <p class="text-secondary mb-4">Informazioni del tuo account Cinevobis</p>

                    <?php if ($isDeletedMessage): ?>
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            Il tuo account è stato eliminato con successo.
                        </div>
                        <div class="d-grid gap-3">
                            <a href="/index.php" class="btn btn-dark py-3 fw-bold">Torna alla Home</a>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($errore)): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <?= htmlspecialchars($errore) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($userData && !$isDeletedMessage): ?>
                        <!-- RIGA USERNAME / EMAIL -->
                        <div class="row mb-3 pb-2 border-bottom">
                            <div class="col-md-6">
                                <label class="small text-uppercase text-muted fw-bold">Username</label>
                                <p class="fs-6 mb-0 text-break"><?= htmlspecialchars($userData['username']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-uppercase text-muted fw-bold">Email</label>
                                <p class="fs-6 mb-0 text-break"><?= htmlspecialchars($userData['email']) ?></p>
                            </div>
                        </div>

                        <!-- RIGA NOME / COGNOME -->
                        <div class="row mb-3 pb-2 border-bottom">
                            <div class="col-md-6">
                                <label class="small text-uppercase text-muted fw-bold">Nome</label>
                                <p class="fs-6 mb-0 text-break"><?= htmlspecialchars($userData['nome'] ?? 'Non specificato') ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-uppercase text-muted fw-bold">Cognome</label>
                                <p class="fs-6 mb-0 text-break"><?= htmlspecialchars($userData['cognome'] ?? 'Non specificato') ?></p>
                            </div>
                        </div>

                        <!-- RIGA MEMBRO DAL (larghezza piena, senza linea sotto) -->
                        <div class="row mb-5">
                            <div class="col-12">
                                <label class="small text-uppercase text-muted fw-bold">Membro dal</label>
                                <p class="fs-6 mb-0 text-break"><?= htmlspecialchars($dataRegistrazione); ?></p>
                            </div>
                        </div>
                        
                        
                            <form method="POST" class="d-flex gap-3 mt-4">
                                <button type="submit"
                                    name="change_password"
                                    class="btn btn-dark btn-lg flex-fill py-3 fw-bold">
                                    Cambia password
                                </button>

                                <button type="submit"
                                        name="delete_user"
                                        class="btn btn-outline-danger btn-lg flex-fill py-3 fw-bold"
                                        onclick="return confirm('Sei sicuro?');">
                                    Elimina account
                                </button>
                            </form>
                            
                            
                    <?php elseif (!$isDeletedMessage): ?>
                        <div class="alert alert-warning text-center">Utente non trovato.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block bg-secondary" 
                 style="background-image: url('/assets/img/astronaut.jpeg'); background-size: cover; background-position: center;">
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>