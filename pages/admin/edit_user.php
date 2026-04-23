<?php
session_start();

require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

$username = $_GET['username'] ?? '';

if (!$username) {
    header("Location: admin_area.php");
    exit();
}

$errore = '';
$messaggio = '';

// Carichiamo i dati attuali dell'utente
$user = new userObj($conn, $username);
$utente = $user->findByUsername();

if (!$utente) {
    header("Location: admin_area.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) {
        $nome    = trim($_POST['nome'] ?? '');
        $cognome = trim($_POST['cognome'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $attivo  = isset($_POST['attivo']) ? (int) $_POST['attivo'] : 0;

        if (!$nome || !$cognome || !$email) {
            $errore = "Nome, cognome ed email sono obbligatori";
        } else {
            try {
                $userUpdate = new userObj(
                    $conn,
                    $username,
                    null,
                    $nome,
                    $cognome,
                    $email,
                    $attivo,
                    $utente['id_profilo']
                );

                $userUpdate->update($username);
                $messaggio = "Utente aggiornato con successo";

                // Ricarichiamo i dati aggiornati
                $utente = $userUpdate->findByUsername();
            } catch (PDOException $e) {
                $errore = "Errore durante l'aggiornamento";
                error_log("Errore update utente: " . $e->getMessage());
            }
        }
    }

    if (isset($_POST['delete_user'])) {
        try {
            $user->delete();
            header("Location: users.php?msg=eliminato");
            exit();
        } catch (PDOException $e) {
            $errore = "Errore durante l'eliminazione";
            error_log("Errore delete utente: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica utente - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
        <div style="max-width: 450px; width: 100%;">

            <a href="users.php"
            class="btn-close position-absolute top-0 start-0 m-4"
            aria-label="Chiudi"></a>

            <h1 class="display-6 fw-bolder mb-2 text-center">Modifica utente</h1>
            <p class="text-secondary mb-5 text-center">Aggiorna i dati dell'account</p>

            <?php if ($errore): ?>
                <div class="alert alert-danger border-0 small py-2 mb-4">
                    <?= htmlspecialchars($errore) ?>
                </div>
            <?php endif; ?>

            <?php if ($messaggio): ?>
                <div class="alert alert-success border-0 small py-2 mb-4">
                    <?= htmlspecialchars($messaggio) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <input type="text"
                            name="nome"
                            class="form-control bg-light border-light py-3"
                            placeholder="Nome"
                            value="<?= htmlspecialchars($utente['nome'] ?? '') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <input type="text"
                            name="cognome"
                            class="form-control bg-light border-light py-3"
                            placeholder="Cognome"
                            value="<?= htmlspecialchars($utente['cognome'] ?? '') ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <input type="email"
                        name="email"
                        class="form-control bg-light border-light py-3"
                        placeholder="Email"
                        value="<?= htmlspecialchars($utente['email'] ?? '') ?>"
                        required>
                </div>

                <div class="mb-3">
                    <input type="text"
                        class="form-control bg-light border-light py-3"
                        value="<?= htmlspecialchars($utente['username'] ?? '') ?>"
                        readonly>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">Attivo</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="attivo" value="1"
                                <?= (int)($utente['attivo'] ?? 0) === 1 ? 'checked' : '' ?>>
                            <label class="form-check-label">Sì</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="attivo" value="0"
                                <?= (int)($utente['attivo'] ?? 0) === 0 ? 'checked' : '' ?>>
                            <label class="form-check-label">No</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit"
                            name="save"
                            class="btn btn-dark btn-lg flex-fill py-3 fw-bold">
                        Salva
                    </button>

                    <button type="submit"
                            name="delete_user"
                            class="btn btn-outline-danger btn-lg flex-fill py-3 fw-bold"
                            onclick="return confirm('Sei sicuro?');">
                        Elimina
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>