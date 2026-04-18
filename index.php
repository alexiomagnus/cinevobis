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
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Tipografia Homepage */
        .headline {
            font-size: clamp(2.4rem, 5vw, 4rem);
            letter-spacing: -0.02em;
            font-weight: 800;
        }

        .headline em {
            font-style: italic;
            color: var(--accent-color);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include("includes/header.php"); ?>

    <main class="flex-grow-1 d-flex align-items-center">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">

                    <h1 class="headline mb-3">Cinevobis</h1>

                    <form action="/pages/public/search.php" method="GET" class="search-wrap d-flex align-items-center mx-auto mt-5">
                        <input type="text" name="search" placeholder="Cerca un film..." class="flex-grow-1" autocomplete="off">
                        <button type="submit" class="btn btn-brand rounded-3 px-4">Cerca</button>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <?php require_once('includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>