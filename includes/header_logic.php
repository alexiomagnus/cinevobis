<?php
$currentPage = basename($_SERVER['SCRIPT_NAME']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        header("Location: /actions/logout.php");
        exit();
    }
    if (isset($_POST['login']) && $currentPage !== 'login.php') {
        header("Location: /actions/login.php");
        exit();
    }
    if (isset($_POST['signup']) && $currentPage !== 'signup.php') {
        header("Location: /actions/signup.php");
        exit();
    }
    if (isset($_POST['profile'])) {
        header("Location: /actions/settings.php");
        exit();
    }
}