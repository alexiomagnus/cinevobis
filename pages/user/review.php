<?php
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');

$id_utente = $_SESSION['id_utente'] ?? null;
$username = $_SESSION['username']  ?? null;

// Autenticazione
if (!$id_utente) {
    header("Location: /index.php");
    exit();
}

$tmdb_id = $_GET['tmdb_id'] ?? null;

if (!$tmdb_id) {
    header("Location: /index.php");
    exit();
}

$errore = '';
$messaggio = '';
$recensione_esistente = null;


// Recupera la recensione esistente (se c'è)
try {
    $sql = "SELECT * FROM recensioni WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id_utente' => $id_utente, 
        ':tmdb_id' => $tmdb_id
    ]);

    $recensione_esistente = $stmt->fetch();
    
} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}


// Gestione POST
if (isset($_POST['write_review'])) {
    $voto = $_POST['rating'] ?? null;
    $commento = $_POST['commento'] ?? null;

    if (!$voto || !$commento) {
        $errore = "Compila tutti i campi";

    } elseif ($voto < 1 || $voto > 10) {
        $errore = "Il voto deve essere compreso tra 1 e 10";

    } else {
        try {
            if ($recensione_esistente) {
                // Aggiorna recensione esistente
                $sql = "UPDATE recensioni SET voto = :voto, commento = :commento, data_aggiunto = :data_aggiunto
                        WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

            } else {
                // Inserisce nuova recensione
                $sql = "INSERT INTO recensioni (tmdb_id, id_utente, data_aggiunto, commento, voto)
                        VALUES (:tmdb_id, :id_utente, :data_aggiunto, :commento, :voto)";
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':tmdb_id' => $tmdb_id,
                ':id_utente' => $id_utente,
                ':data_aggiunto' => date('Y-m-d H:i:s'),
                ':commento' => trim($commento),
                ':voto' => (float)$voto
            ]);

            $messaggio = $recensione_esistente
                ? "Recensione aggiornata"
                : "Recensione pubblicata";

            // Segna automaticamente come visto (watched)
            $sql_check_watched = "SELECT 1 FROM watched WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";
            $stmt_check_watched = $conn->prepare($sql_check_watched);
            $stmt_check_watched->execute([
                ':id_utente' => $id_utente,
                ':tmdb_id' => $tmdb_id
            ]);

            if (!$stmt_check_watched->fetch()) {
                $sql_insert_watched = "INSERT INTO watched (tmdb_id, id_utente, data_aggiunto) 
                                     VALUES (:tmdb_id, :id_utente, :data_aggiunto)";
                $stmt_insert_watched = $conn->prepare($sql_insert_watched);
                $stmt_insert_watched->execute([
                    ':tmdb_id' => $tmdb_id,
                    ':id_utente' => $id_utente,
                    ':data_aggiunto' => date('Y-m-d H:i:s')
                ]);
            }

            // Inizializza per pulire la pagina dopo l'invio della recensione
            $recensione_esistente = [];

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nel salvataggio della recensione";
        }
    }
}

// Eliminare film
if (isset($_POST['delete_review'])) {
    try {
        $sql = "DELETE FROM recensioni WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_utente' => $id_utente, 
            ':tmdb_id' => $tmdb_id
        ]);

        header("Location: /pages/public/film.php?tmdb_id=" . urlencode($tmdb_id));
        exit();

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
        $errore = "Errore nella cancellazione della recensione";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $recensione_esistente ? 'Modifica recensione' : 'Scrivi recensione' ?> - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 px-4">

                <a href="/pages/public/film.php?tmdb_id=<?= urldecode($tmdb_id) ?>" 
                    class="btn-close position-absolute top-0 start-0 m-4" 
                    aria-label="Close">
                </a>

                <div class="text-center mb-4">
                    <h1 class="display-6 fw-bolder mb-2">
                        <?= $recensione_esistente ? 'Modifica recensione' : 'Scrivi recensione' ?>
                    </h1>
                    <p class="text-secondary mb-3">
                        <?= $recensione_esistente
                            ? 'Aggiorna la tua opinione'
                            : 'Condividi la tua opinione' ?>
                    </p>
                    
                    <div class="d-inline-flex align-items-center text-muted opacity-75" style="font-size: 0.85rem;">
                        <i class="bi bi-info-circle me-2"></i>
                        <span>Il film verrà aggiunto automaticamente alla tua <strong>watched</strong></span>
                    </div>
                </div>

                <?php if ($errore): ?>
                    <div class="alert alert-danger border-0 small py-2 mb-4 text-center">
                        <?= htmlspecialchars($errore) ?>
                    </div>
                <?php endif; ?>

                <?php if ($messaggio): ?>
                    <div class="alert alert-success border-0 small py-2 mb-4 text-center">
                        <?= htmlspecialchars($messaggio) ?>
                        <a href="/pages/user/reviews.php" class="fw-bold text-decoration-none">vedi</a>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="tmdb_id" value="<?= htmlspecialchars($tmdb_id) ?>">

                    <div class="mb-4">
                        <label class="form-label small text-secondary">Voto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light">
                                <i class="bi bi-star-fill text-warning"></i>
                            </span>
                            <input
                                type="number"
                                name="rating"
                                step="0.1"
                                id="rating"
                                class="form-control bg-light border-light py-3"
                                min="1"
                                max="10"
                                placeholder="Da 1 a 10"
                                value="<?= htmlspecialchars($recensione_esistente['voto'] ?? '') ?>"
                                required>
                            <span class="input-group-text bg-light border-light text-secondary">/ 10</span>
                        </div>
                    </div>

                    <hr class="my-3 opacity-25">

                    <div class="mb-4">
                        <label class="form-label small text-secondary">Commento</label>
                        <textarea
                            name="commento"
                            id="commento"
                            class="form-control bg-light border-light"
                            rows="6"
                            maxlength="200"
                            placeholder="Scrivi qui la tua recensione..."
                            required><?= htmlspecialchars($recensione_esistente['commento'] ?? '') ?></textarea>
                            <div class="form-text text-end">
                                Limite massimo: 200 caratteri
                            </div>
                    </div>

                    <button type="submit" name="write_review" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-3">
                        <?= $recensione_esistente ? 'Aggiorna recensione' : 'Pubblica recensione' ?>
                    </button>

                    <?php if ($recensione_esistente): ?>
                        <button type="submit" onclick="closeAndRedirect()" name="delete_review" class="btn btn-outline-danger btn-lg w-100 py-3 fw-bold">
                            Elimina recensione
                        </button>
                    <?php endif; ?>

                </form>
            </div>
        </div>
    </div>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>