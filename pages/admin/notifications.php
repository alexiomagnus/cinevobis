<?php
session_start();

require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id_notifica'] ?? 0);

    if($id > 0) {
        try {
            $sql = "UPDATE notifiche SET letta = 1 WHERE id_notifica = :id_n";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id_n' => $id]);

        } catch (PDOException $e) {
            error_log("Errore: " . $e->getMessage());
        }
    }
}


$notifiche = "";

try {
    $sql = "SELECT * 
            FROM notifiche n
            JOIN utenti u ON n.id_utente = u.id_utente
            ORDER BY n.data_invio DESC";
             
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $notifiche = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Errore: " . $e->getMessage());
}


if (isset($_POST['delete'])) {
    try {
        $sql = "DELETE FROM notifiche WHERE letta = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        header("Location: /pages/admin/notifications.php");
        exit();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifiche - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 mb-5 pb-5 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fs-4 fw-bold mb-0">Notifiche</h1>
        </div>

        <?php
        $lette = [];
        $nonLette = [];

        foreach ($notifiche as $n) {
            if ($n['letta']) 
                $lette[] = $n;
            else 
                $nonLette[] = $n;        
        }
        ?>

        <?php if (empty($notifiche)): ?>
            <div class="text-center text-muted py-5">
                <p class="mb-0">Nessuna notifica disponibile</p>
            </div>
        <?php else: ?>

            <h6 class="text-uppercase text-muted fw-semibold small mb-3">Non lette</h6>
            <?php if (empty($nonLette)): ?>
                <p class="text-muted small mb-4">Nessuna notifica da leggere</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-3 mb-5">
                    <?php foreach ($nonLette as $notifica): ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body px-4 py-3">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div class="d-flex flex-column gap-1 flex-grow-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-semibold"><?= htmlspecialchars($notifica['titolo'] ?? '—') ?></span>
                                            <span class="text-muted small">·</span>
                                            <span class="text-muted small"><?= htmlspecialchars($notifica['username']) ?></span>
                                        </div>
                                        <p class="mb-0 text-muted small"><?= htmlspecialchars($notifica['descrizione'] ?? '') ?></p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-1 text-nowrap">
                                        <span class="badge bg-light text-muted fw-normal border small">
                                            <?= htmlspecialchars($notifica['data_invio'] ?? '') ?>
                                        </span>
                                        <form method="POST">
                                            <input type="hidden" name="id_notifica" value="<?= $notifica['id_notifica'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Segna come letta">&#10003;</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h6 class="text-uppercase text-muted fw-semibold small mb-3">Lette</h6>

            <form method="POST" class="mb-3">
                <button type="submit" name="delete" class="btn btn-outline-danger btn-sm px-3 d-flex align-items-center gap-2">
                    <i class="bi bi-trash3"></i>
                </button>
            </form>

            <?php if (empty($lette)): ?>
                <p class="text-muted small">Nessuna notifica letta</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($lette as $notifica): ?>
                        <div class="card border-0 shadow-sm opacity-50">
                            <div class="card-body px-4 py-3">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div class="d-flex flex-column gap-1 flex-grow-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-semibold text-muted"><?= htmlspecialchars($notifica['titolo'] ?? '—') ?></span>
                                            <span class="text-muted small">·</span>
                                            <span class="text-muted small"><?= htmlspecialchars($notifica['username']) ?></span>
                                        </div>
                                        <p class="mb-0 text-muted small"><?= htmlspecialchars($notifica['descrizione'] ?? '') ?></p>
                                    </div>
                                    <span class="badge bg-light text-muted fw-normal border small mt-1 text-nowrap">
                                        <?= htmlspecialchars($notifica['data_invio'] ?? '') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>