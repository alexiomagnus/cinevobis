<?php
// Controlla se l'estensione è caricata
if (extension_loaded("mongodb")) {
    echo "Estensione MongoDB attivata con successo<br>";
} else {
    echo "Errore: Estensione MongoDB non trovata. Controlla il file php.ini e riavvia Apache.<br>";
}

// Controlla se la libreria di Composer è accessibile
require_once __DIR__ . '/vendor/autoload.php';
if (class_exists('MongoDB\Client')) {
    echo "Libreria MongoDB installata tramite Composer<br>";
} else {
    echo "Errore: Libreria non trovata. Hai eseguito 'composer require mongodb/mongodb'?";
}
