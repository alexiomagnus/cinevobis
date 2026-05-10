<?php
// Connessione PDO a MariaDB per il database 'cinevobis'.
// Imposta ERRMODE_EXCEPTION e FETCH_ASSOC per ottenere risultati consistenti.
// In caso di errore logga il problema e mostra un messaggio generico all'utente.
require_once(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;

// Inizializza Dotenv puntando alla cartella del progetto
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Errore critico DB: " . $e->getMessage());
    
    // Mostrare all'utente un messaggio generico
    die("Spiacenti, il servizio è momentaneamente non disponibile");
}