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
$host = '127.0.0.1';
$dbname = 'cinevobis';
$user = 'root';
$password = 'root';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Scrivere l'errore dettagliato nel log (grazie al config.php)
    error_log("Errore critico DB: " . $e->getMessage());
    
    // Mostrare all'utente un messaggio generico
    die("Spiacenti, il servizio è momentaneamente non disponibile.");
}