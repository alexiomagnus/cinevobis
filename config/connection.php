<?php
/**
 * Stabilisce la connessione PDO al database MariaDB 'cinevobis'.
 * Configura la modalità di errore su ERRMODE_EXCEPTION e il fetch mode
 * predefinito su FETCH_ASSOC. In caso di errore critico, logga il messaggio
 * e mostra un messaggio generico all'utente terminando l'esecuzione.
 * Espone la variabile $conn disponibile per tutti i file che includono questo script.
 *
 * @note Interagisce con il database MariaDB: `cinevobis`.
 */
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