<?php
session_start();

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/connection.php');

$username = $_SESSION['username'] ?? '';
$erroe = "";
$messaggio = "";

if (!$username) {
    header("Location: /index.php");
    exit();
}

if (isset($_POST['invia'])) {
    $titolo = $_POST['titolo'];
    $descrizione = $_POST['descrizione'];

    try {
        $sql = "INSERT INTO notifiche (titolo, descrizione, data_invio, id_utente) VALUES 
            (:titolo, :descrizione, :data_invio, :id_utente)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':titolo' => $titolo,
            ':descrizione' => $descrizione,
            ':data_invio' => date('Y-m-d H:i:s'),
            ':id_utente' => $_SESSION['id_utente']
        ]);
        
        $messaggio = "Messaggio inviato con successo";
    } catch (PDOException $e) {
        error_log("Errore: " . $e);
        $errore = "Si è verificato un errore durante l'invio";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contattaci - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4 px-4">

                <a href="javascript:void(0)"
                   onclick="closeAndRedirect()"
                   class="btn-close position-absolute top-0 start-0 m-4"
                   aria-label="Close">
                </a>

                <div class="text-center mb-5">
                    <h1 class="display-6 fw-bolder mb-2">Contattaci</h1>
                    <p class="text-secondary">Inviaci un messaggio per qualsiasi necessità</p>
                </div>

                <?php if (!empty($errore)): ?>
                    <div class="alert alert-danger border-0 small py-2 mb-4 text-center"><?= htmlspecialchars($errore) ?></div>
                <?php endif; ?>

                <?php if (!empty($messaggio)): ?>
                    <div class="alert alert-success border-0 small py-2 mb-4 text-center"><?= htmlspecialchars($messaggio) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label small text-secondary">Titolo</label>
                        <input type="text"
                               name="titolo"
                               id="titolo"
                               class="form-control bg-light border-light py-3"
                               placeholder="Inserisci un titolo"
                               required>
                    </div>

                    <div class="mb-5">
                        <label class="form-label small text-secondary">Descrizione</label>
                        <textarea name="descrizione"
                                  id="descrizione"
                                  class="form-control bg-light border-light py-3"
                                  rows="6"
                                  placeholder="Descrivi il tuo messaggio..."
                                  required></textarea>
                    </div>

                    <button type="submit" name="invia" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">
                        Invia
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>

</body>
</html>