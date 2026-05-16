<?php
// Pagina profilo utente: mostra i dati dell'account e gestisce azioni sul profilo.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/functions.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');


// Controllo autenticazione
$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}


// Dichiarazione variabili
$userData = null;
$dataRegistrazione = "N/D";
$user = new userObj($conn, $username);


// Gestione Cambia Password
if (isset($_POST['change_password'])) {
    header("Location: /actions/change_password.php");
    exit();
}


// Gestione logout
if (isset($_POST['logout'])) {
    header("Location: /actions/logout.php");
    exit();
}


// Gestione Disabilitazione Account
if (isset($_POST['delete_user']) && $username) {
    try {
        if ($user->disable()) {
            session_destroy();
            header("Location: /index.php");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Errore durante l'eliminazione: " . $e->getMessage());
        $errore = "Errore durante l'eliminazione";
    }
}


// Conteggi statistiche (MariaDB)
$countWatched = 0;
$countWatchlist = 0;
$countReviews = 0;

try {
    // Film visti anno corrente
    $stmt = $conn->prepare("SELECT COUNT(*) FROM watched WHERE id_utente = :id_utente AND YEAR(data_aggiunto) = YEAR(CURRENT_DATE)");
    $stmt->execute([':id_utente' => $_SESSION['id_utente']]);
    $countWatched = $stmt->fetchColumn();

    // Watchlist
    $stmt = $conn->prepare("SELECT COUNT(*) FROM watchlist WHERE id_utente = :id_utente");
    $stmt->execute([':id_utente' => $_SESSION['id_utente']]);
    $countWatchlist = $stmt->fetchColumn();

    // Recensioni
    $stmt = $conn->prepare("SELECT COUNT(*) FROM recensioni WHERE id_utente = :id_utente");
    $stmt->execute([':id_utente' => $_SESSION['id_utente']]);
    $countReviews = $stmt->fetchColumn();

    // Ids Preferiti (per MongoDB)
    $stmt = $conn->prepare("SELECT tmdb_id FROM preferiti WHERE id_utente = :id_utente ORDER BY data_aggiunto DESC");
    $stmt->execute([':id_utente' => $_SESSION['id_utente']]);
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}


// Recupero dettagli preferiti da MongoDB
$favorites = [];
if (!empty($ids)) {
    try {
        // Controllo per evitare errori se MongoDB è offline
        if (!$collection) {
            throw new \Exception("Connessione a MongoDB non disponibile.");
        }

        $cursor = $collection->find(
            ['id' => ['$in' => $ids]]
        );

        $favorites = movie_sorting($cursor, $ids);
    } catch (\Throwable $e) { // \Throwable cattura sia Exception che Fatal Error
        error_log("Errore caricamento film preferiti: " . $e->getMessage());
    }
}


// Sezione user per diventare tester
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tester = isset($_POST['tester']) ? 1 : 0;  // ON and OFF
    $id_utente = $_SESSION['id_utente'];

    try {
        $sql = "UPDATE utenti SET tester = :tester WHERE id_utente = :id_utente";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':tester' => $tester,
            ':id_utente' => $id_utente
        ]);

        // Aggiornare la variabile tester nella sessione
        $_SESSION['tester'] = $tester;
        $userData['tester'] = $tester;

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
        $errore = "Non è stato possibile attivare la modalità tester";
    }
}


// Recuperiamo i dati utente
$userData = $user->findByUsername();
if ($userData && $userData['data_registrazione']) {
    $date = new DateTime($userData['data_registrazione']);
    $dataRegistrazione = $date->format('Y');
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

            <div class="col-lg-6 d-flex flex-column position-relative px-4 py-5 vh-100 overflow-y-auto">

                <a href="javascript:void(0)" 
                   onclick="closeAndRedirect()" 
                   class="btn-close position-absolute top-0 start-0 m-4" 
                   aria-label="Close">
                </a>

                <div style="max-width: 550px; width: 100%; margin: 0 auto;">

                    <h1 class="fw-bolder text-center mb-5" style="letter-spacing: -0.5px;">Il tuo profilo</h1>

                    <?php if (isset($errore)): ?>
                        <div class="alert alert-danger mb-4" role="alert">
                            <?= htmlspecialchars($errore) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($userData): ?>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 rounded-4 h-100" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background-color: rgba(99, 102, 241, 0.1); color: var(--accent);">
                                            <i class="bi bi-film fs-5"></i>
                                        </div>
                                        <div>
                                            <p class="small fw-bold mb-0" style="color: var(--text-muted);">Film visti nel <?= date('Y') ?></p>
                                            <h4 class="mb-0 fw-bolder"><?= htmlspecialchars($countWatched); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-4 h-100" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background-color: rgba(99, 102, 241, 0.1); color: var(--accent);">
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

                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 rounded-4 h-100" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background-color: rgba(99, 102, 241, 0.1); color: var(--accent);">
                                            <i class="bi bi-bookmark fs-5"></i>
                                        </div>
                                        <div>
                                            <p class="small fw-bold mb-0" style="color: var(--text-muted);">Film in watchlist</p>
                                            <h4 class="mb-0 fw-bolder"><?= htmlspecialchars($countWatchlist); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-4 h-100" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background-color: rgba(99, 102, 241, 0.1); color: var(--accent);">
                                            <i class="bi bi-chat-square-text fs-5"></i>
                                        </div>
                                        <div>
                                            <p class="small fw-bold mb-0" style="color: var(--text-muted);">Recensioni</p>
                                            <h4 class="mb-0 fw-bolder"><?= htmlspecialchars($countReviews); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($favorites)): ?>
                            <div class="mb-4">
                                <p class="small fw-bold mb-2" style="color: var(--text-muted);">Ultimi preferiti</p>
                                <div class="row g-2">
                                    <?php for ($i = 0; $i < min(4, count($favorites)); $i++): ?>
                                    <div class="col-3">
                                        <a href="/pages/public/film.php?tmdb_id=<?= htmlspecialchars($favorites[$i]['id']) ?>" class="d-block text-decoration-none">
                                            <img src="https://image.tmdb.org/t/p/w200<?= htmlspecialchars($favorites[$i]['poster_path'] ?? '') ?>"
                                                alt="<?= htmlspecialchars($favorites[$i]['title'] ?? '') ?>"
                                                class="img-fluid rounded-3 w-100 card-hover"
                                                style="aspect-ratio: 2/3; object-fit: cover; border: 1px solid var(--border);"
                                                onerror="this.style.visibility='hidden'">
                                        </a>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="rounded-4 mb-4" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom gap-3">
                                <span class="d-flex align-items-center gap-2" style="color: var(--text-muted); white-space: nowrap;">
                                    <i class="bi bi-person"></i> Profilo
                                </span>
                                <?php 
                                $profilo = '';

                                if($userData['id_profilo'] == 1) {
                                    $profilo = 'Admin';

                                } elseif($userData['id_profilo'] == 2) {
                                    $profilo = 'User';
                                }
                                ?>
                                <span class="fw-medium text-end text-truncate"><?= htmlspecialchars($profilo) ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom gap-3">
                                <span class="d-flex align-items-center gap-2" style="color: var(--text-muted); white-space: nowrap;">
                                    <i class="bi bi-person"></i> Username
                                </span>
                                <span class="fw-medium text-end text-truncate"><?= htmlspecialchars($userData['username']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom gap-3">
                                <span class="d-flex align-items-center gap-2" style="color: var(--text-muted); white-space: nowrap;">
                                    <i class="bi bi-envelope"></i> Email
                                </span>
                                <span class="fw-medium text-end text-truncate"><?= htmlspecialchars($userData['email']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom gap-3">
                                <span class="d-flex align-items-center gap-2" style="color: var(--text-muted); white-space: nowrap;">
                                    <i class="bi bi-person-vcard"></i> Nome
                                </span>
                                <span class="fw-medium text-end text-truncate"><?= htmlspecialchars($userData['nome'] ?? 'Non inserito') ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom gap-3">
                                <span class="d-flex align-items-center gap-2" style="color: var(--text-muted); white-space: nowrap;">
                                    <i class="bi bi-person-vcard"></i> Cognome
                                </span>
                                <span class="fw-medium text-end text-truncate"><?= htmlspecialchars($userData['cognome'] ?? 'Non inserito') ?></span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center p-3 gap-3">
                                <span class="d-flex align-items-center gap-2" style="color: var(--text-muted); white-space: nowrap;">
                                    <i class="bi bi-person-check"></i> Tester
                                </span>
                                <?php
                                $tester = '';
                                
                                if($userData['tester'] == 1)
                                    $tester = 'Attivo';
                                else
                                    $tester = 'Disattivato';
                                ?>
                                <span class="fw-medium text-end text-truncate"><?= htmlspecialchars($tester) ?></span>
                            </div>
                        </div>
                        

                        <form method="POST">
                            <div class="rounded-4 overflow-hidden mb-5" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                                <div class="p-1 border-bottom">
                                    <button type="submit" name="change_password" class="btn w-100 d-flex justify-content-between align-items-center text-start border-0" style="color: var(--text); padding: 12px; border-radius: 8px;">
                                        <span class="fw-medium">Modifica la password</span>
                                        <i class="bi bi-chevron-right" style="color: var(--text-muted);"></i>
                                    </button>
                                </div>
                                <div class="p-1 bg-danger bg-opacity-10">
                                    <button type="submit" name="delete_user" class="btn w-100 d-flex justify-content-between align-items-center text-start text-danger border-0" 
                                            style="padding: 12px; border-radius: 8px;" 
                                            onclick="return confirm('Stai per eliminare il tuo account su Cinevobis. Confermi?');">
                                        <span class="fw-bold">Elimina account</span>
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <form method="POST">
                            <div class="mb-4">

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input"
                                        type="checkbox" 
                                        name="tester" 
                                        id="tester"
                                        <?= ($userData['tester'] == 1) ? 'checked' : '' ?>>

                                    <label class="form-check-label fw-semibold" for="tester">
                                        Tester
                                    </label>
                                </div>

                                <p class="small text-start mb-4" style="color: var(--text-muted);">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Attivando questa modalità, accetti la presenza di contenuti aggiuntivi utilizzati per verificare il funzionamento del sistema (ad esempio annunci e nuove implementazioni). 
                                    Il comportamento del sito potrebbe non essere quello definitivo. Clicca salva per applicare le modifiche.
                                </p>

                                <div class="d-flex justify-content-between align-items-center">
                                    
                                    <button type="submit" name="update_tester"
                                        class="btn btn-outline-secondary px-4">
                                        Salva
                                    </button>

                                    <button type="submit" name="logout"
                                        class="btn btn-outline-danger px-4">
                                        Logout
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