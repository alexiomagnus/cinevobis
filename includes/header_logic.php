<?php
/**
 * Logica di routing lato server per le azioni inviate tramite i form dell'header
 * (navbar). Intercetta le richieste POST e reindirizza alle pagine appropriate:
 * logout, login, signup e profilo. Viene incluso in ogni pagina che usa l'header
 * per garantire che i pulsanti della navbar funzionino correttamente.
 */
$currentPage = basename($_SERVER['SCRIPT_NAME']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        header("Location: /actions/logout.php");
        exit();
    }
    if (isset($_POST['login']) && $currentPage !== 'login.php') {
        header("Location: /pages/public/login.php");
        exit();
    }
    if (isset($_POST['signup']) && $currentPage !== 'signup.php') {
        header("Location: /pages/public/signup.php");
        exit();
    }
    if (isset($_POST['profile'])) {
        header("Location: /actions/settings.php");
        exit();
    }
}