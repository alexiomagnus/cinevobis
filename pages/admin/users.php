<?php
// Pagina admin per la lista degli utenti registrati.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

// Controllo autenticazione
$username   = $_SESSION['username'] ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}

$user = new userObj($conn, $username);
$utenti = $user->readAll();


// Conteggio utenti totali
$totaleUtenti = 0;

try {
    $sql = "SELECT COUNT(*) FROM utenti";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $totaleUtenti = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Errore DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione utenti - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 mb-5 pb-5 flex-grow-1">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="fs-3 fw-bold mb-0">Utenti</h1>
            </div>

            <small class='text-uppercase fw-bold text-muted d-block mb-3' style='letter-spacing:1px'><?php echo htmlspecialchars($totaleUtenti); ?> Utenti totali</small>
        
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Utente</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Email</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Profilo</th>
                            <th class="text-center py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Stato</th>
                            <th class="text-end pe-4 py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php foreach ($utenti as $utente): ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 42px; height: 42px; background-color: var(--bg-muted); color: var(--accent);">
                                            <i class="bi bi-person-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($utente['username']) ?></div>
                                            <div class="small text-muted">
                                                <?= htmlspecialchars(trim(($utente['nome'] ?? '') . ' ' . ($utente['cognome'] ?? ''))) ?: '<span class="fst-italic">Nessun nome</span>' ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-3 text-secondary">
                                    <?= htmlspecialchars($utente['email'] ?? 'N/D') ?>
                                </td>
                                
                                <td class="py-3">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-2 fw-normal">
                                        <i class="bi bi-shield-check me-1"></i> <?= htmlspecialchars($utente['nome_profilo'] ?? 'N/D') ?>
                                    </span>
                                </td>
                                
                                <td class="text-center py-3">
                                    <?php if ($utente['attivo']): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 fw-normal">
                                            <i class="bi bi-check2-circle me-1"></i> Attivo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-2 fw-normal">
                                            <i class="bi bi-x-circle me-1"></i> Inattivo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="text-end pe-4 py-3">
                                    <a href="edit_user.php?username=<?= urlencode($utente['username']) ?>" 
                                       class="btn btn-sm btn-outline-secondary rounded-pill px-3 d-inline-flex align-items-center">
                                        <i class="bi bi-pencil-square me-2"></i> Modifica
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($utenti)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Nessun utente trovato nel database.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> 
    
    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>