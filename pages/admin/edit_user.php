<?php
session_start();

require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

$username = $_GET['username'] ?? '';

if (!$username) {
    header("Location: admin_area.php");
    exit();
}

$errore    = '';
$messaggio = '';

if (isset($_POST['indietro'])) {
    header("Location: users.php");
    exit();
}

$nazioni = [];
try {
    $stmt    = $conn->query("SELECT iso_code, nome_nazione FROM nazioni ORDER BY nome_nazione");
    $nazioni = $stmt->fetchAll();
} catch (PDOException $e) {
    $errore = "Errore: " . $e->getMessage();
}

if (isset($_POST['salva'])) {
    $nome       = trim($_POST['nome']       ?? '');
    $cognome    = trim($_POST['cognome']    ?? '');
    $citta      = trim($_POST['citta']      ?? '');
    $email      = trim($_POST['email']      ?? '');
    $iso_code   = $_POST['iso_code']      ?? null;
    $attivo     = $_POST['attivo']          ?? 0;

    if (!$nome || !$cognome || !$email) {
        $errore = "Nome, cognome ed email sono obbligatori";
    } else {
        try {
            $userUpdate = new userObj(
                $conn, $username, null,
                $nome, $cognome, $citta, $email,
                $attivo, null, $iso_code ?: null
            );

            $userUpdate->update($username);
            $messaggio = "Utente aggiornato";
        } catch (PDOException $e) {
            $errore = "Errore: " . $e->getMessage();
        }
    }
}

if (isset($_POST['elimina'])) {
    try {
        $user = new userObj($conn, $username);
        $user->delete();

        $messaggio = "Utente eliminato";
    } catch (PDOException $e) {
        $errore = "Errore: " . $e->getMessage();
    }
}

$user   = new userObj($conn, $username);
$utente = $user->findByUsername();

if (!$utente) {
    header("Location: admin_area.php");
    exit();
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
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="card shadow-sm border-0 p-4" style="width: 100%; max-width: 640px;">
            <div class="card-body">

                <h4 class="fw-bold mb-4">Modifica utente</h4>

                <?php if ($errore): ?>
                    <div class="alert alert-danger py-2" role="alert">
                        <?= htmlspecialchars($errore) ?>
                    </div>
                <?php endif; ?>

                <?php if ($messaggio): ?>
                    <div class="alert alert-success py-2" role="alert">
                        <?= htmlspecialchars($messaggio) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">

                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($utente['username']) ?>" disabled>
                        </div>

                        <div class="col-6">
                            <label class="form-label fw-semibold">Nome</label>
                            <input type="text" name="nome" class="form-control"
                                   value="<?= htmlspecialchars($utente['nome'] ?? '') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Cognome</label>
                            <input type="text" name="cognome" class="form-control"
                                   value="<?= htmlspecialchars($utente['cognome'] ?? '') ?>" required>
                        </div>

                        <div class="col-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= htmlspecialchars($utente['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Città</label>
                            <input type="text" name="citta" class="form-control"
                                   value="<?= htmlspecialchars($utente['citta'] ?? '') ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Nazione</label>
                            <select name="iso_code" class="form-select">
                                <option value="">— Seleziona —</option>
                                <?php foreach ($nazioni as $n): ?>
                                    <option value="<?= $n['iso_code'] ?>"
                                        <?= $utente['iso_code'] == $n['iso_code'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($n['nome_nazione']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Attivo</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="attivo" id="attivoSi" value="1"
                                           <?= ($utente['attivo'] ?? 0) == 1 ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="attivoSi">Sì</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="attivo" id="attivoNo" value="0"
                                           <?= ($utente['attivo'] ?? 0) == 0 ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="attivoNo">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-flex gap-2 mt-4">
                            <button type="submit" name="salva" class="btn btn-sm btn-brand">Salva modifiche</button>
                            <button type="submit" name="indietro" class="btn btn-secondary">Indietro</button>
                            <button type="submit" name="elimina" class="btn btn-sm btn-danger">Elimina Utente</button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>