<?php
session_start();

require_once(__DIR__ . '/config/connection.php');
require_once(__DIR__ . '/includes/user_obj.php');
require_once(__DIR__ . '/includes/header_logic.php');

$username = $_SESSION["username"] ?? '';

if (isset($_SESSION['id_profilo']) && $_SESSION['id_profilo'] == 1) {
    header("Location: /pages/admin/admin_area.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinevobis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once("includes/header.php"); ?>

    <main class="home-main flex-grow-1">

        <h1 class="headline">Scopri. Organizza. <em>Condividi.</em></h1>
        <p class="subtext">La tua cineteca virtuale</p>

        <form action="/pages/public/search.php" method="GET" class="search-wrap d-flex w-100 mb-5">
            <input type="text" name="search" placeholder="Cerca un film..." class="flex-grow-1" autocomplete="off">
            <button type="submit" class="btn btn-brand px-4">Cerca</button>
        </form>
    </main>

    <?php require_once("includes/footer.php"); ?>

</body>
</html>