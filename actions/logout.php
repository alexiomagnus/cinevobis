<?php
session_start();
require_once(__DIR__ . '/../config/connection.php');
require_once(__DIR__ . '/../includes/user_obj.php');

if (!isset($_SESSION['username'])) {
    header("Location: /index.php");
    exit();
}

try {
    $id_sessione = session_id();
    $username = $_SESSION['username'];

    $user = new userObj($conn, $username);
    $user->setDataLogout(date('Y-m-d H:i:s'), $id_sessione);

    session_destroy();
    header("Location: /index.php");
    exit();
} catch (PDOException $e) {
    session_destroy();
    header("Location: /index.php");
    exit();
}