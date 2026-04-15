<?php
session_start();

require_once(__DIR__ . '/config/connection.php');
require_once(__DIR__ . '/includes/user_obj.php');
require_once(__DIR__ . '/includes/header_logic.php');

$username = $_SESSION["username"] ?? '';
 
if (isset($_SESSION['id_profilo'])) {
    if ($_SESSION['id_profilo'] == 1) {
        header("Location: /pages/admin/admin_area.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinevobis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-dark text-white">
    <?php require_once("includes/header.php"); ?>

    <main class="container flex-grow-1 d-flex flex-column justify-content-start align-items-center pt-5 mt-4">

        <div class="text-center w-100" style="max-width: 620px;">

            <!-- Titolo e sottotitolo -->
            <h1 class="display-3 fw-bold mb-2">Cinevobis</h1>

            <!-- Barra di ricerca -->
            <form action="/pages/public/search.php" method="GET" class="mb-5 position-relative" style="margin-top: 6rem;">
                <i class="bi bi-search position-absolute text-white-50" style="top: 50%; left: 20px; transform: translateY(-50%); pointer-events: none;"></i>
                <input
                    type="text"
                    name="search"
                    class="form-control bg-transparent border-secondary text-white shadow-none py-3 ps-5 rounded-pill">
            </form>

        </div>

    </main>

    <?php require_once("includes/footer.php"); ?>
</body>
</html>