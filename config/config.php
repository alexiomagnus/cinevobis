<?php
// 1. Nascondi gli errori all'utente
ini_set('display_errors', 0);

// 2. Attiva il log su file
ini_set('log_errors', 1);

// 3. Salva il log nella stessa cartella di questo config.php
ini_set('error_log', __DIR__ . '/php_errors.log');

// 4. Riporta tutti gli errori
error_reporting(E_ALL);