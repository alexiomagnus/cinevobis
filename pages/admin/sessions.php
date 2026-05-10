<?php
// Pagina admin che mostra le ultime sessioni di accesso al sito.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}

$user = new userObj($conn, $username);

// Recupero il numero di righe dal parametro GET, validandolo come intero (minimo 1)
$righe = (int)($_GET['righe'] ?? 15);
$sessioni = $user->readAccess($righe);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area sessioni - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 mb-5 pb-5 flex-grow-1">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fs-3 fw-bold mb-0">Log accessi</h1>
            
            <form method="GET" class="d-flex align-items-center gap-2">
                <label for="righe" class="text-muted small fw-medium mb-0 d-none d-sm-block">Mostra righe:</label>
                <div class="input-group input-group-sm" style="width: 130px;">
                    <input type="number" name="righe" id="righe" class="form-control text-center" min="1" max="1000" value="<?= htmlspecialchars($righe) ?>">
                    <button type="submit" class="btn btn-outline-secondary" title="Aggiorna">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Utente</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Data Login</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Data Logout</th>
                            <th class="text-center pe-4 py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Stato</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php foreach ($sessioni as $sessione): ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 42px; height: 42px; background-color: var(--bg-muted); color: var(--accent);">
                                            <i class="bi bi-person-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($sessione['username']) ?></div>
                                            <div class="small text-muted">
                                                <?= htmlspecialchars(trim(($sessione['nome'] ?? '') . ' ' . ($sessione['cognome'] ?? ''))) ?: '<span class="fst-italic">Nessun nome</span>' ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-3 text-secondary">
                                    <i class="bi bi-box-arrow-in-right text-success me-1 opacity-75"></i> 
                                    <?= htmlspecialchars($sessione['data_login'] ?? 'N/D') ?>
                                </td>
                                
                                <td class="py-3 text-secondary">
                                    <?php if (!empty($sessione['data_logout'])): ?>
                                        <i class="bi bi-box-arrow-left text-danger me-1 opacity-75"></i> 
                                        <?= htmlspecialchars($sessione['data_logout']) ?>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic ms-4">-</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="text-center pe-4 py-3">
                                    <?php if (empty($sessione['data_logout'])): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 fw-normal d-inline-flex align-items-center">
                                            <i class="bi bi-circle-fill me-2" style="font-size: 0.45rem;"></i>
                                            Attiva
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-2 fw-normal">
                                            Terminata
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($sessioni)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
                                    Nessuna sessione registrata.
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