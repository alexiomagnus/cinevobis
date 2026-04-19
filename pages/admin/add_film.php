<?php
session_start();

require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

$username = $_SESSION["username"] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}

if (isset($_POST['upload']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // gestione upload immagine
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi film - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <!-- titolo, trama, durata_minuti, data_uscita, nazione, copertina_path, trailer_id, aggiungi_persona (tom select) -->

    <div class="container mt-4 flex-grow-1">

        <form method="POST" enctype="multipart/form-data">
            <label for="image" class="form-label">Inserisci una foto:</label>
            <div class="d-flex gap-2 align-items-center">
                <input type="file" name="image" id="image" class="form-control w-auto" accept="image/*">
                <button type="submit" name="upload" class="btn btn-sm btn-brand">Carica</button>
                <button type="submit" name="cancel" class="btn btn-sm btn-secondary">Annulla</button>
            </div>
        </form>

    </div>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>