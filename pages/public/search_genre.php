<?php
/**
 * Pagina di esplorazione per genere. Riceve l'ID e il nome del genere tramite
 * i parametri GET (?id=...&name=...), interroga MongoDB per trovare tutti i film
 * che contengono quel genere nell'array 'genres', e li mostra in una griglia di card.
 *
 * @note Interagisce con la collezione MongoDB: `films` (query su campo `genres.id`).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Connessione a MongoDB e ricerca film per genere
$id_genere = isset($_GET['id']) ? (int)$_GET['id'] : null;
$nome_genere = isset($_GET['name']) ? $_GET['name'] : null;
$cursor = [];

if (!empty($id_genere)) {
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db = $mongoClient->selectDatabase('cinevobis');
        $collection = $db->selectCollection('films');

        $cursor = $collection->find(['genres.id' => $id_genere])->toArray();
        $count = count($cursor);
        
    } catch(Exception $e) {
        error_log("Errore: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ricerca genere - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <?php if(!empty($nome_genere)): ?>
            <h1 class="fw-bold mb-4"><?= htmlspecialchars($nome_genere) ?></h1>
            
            <?php if($count > 0): ?>
                <small class="text-uppercase fw-bold text-muted d-block mb-4" style="letter-spacing: 1px;">
                    <?= htmlspecialchars($count) ?> Film presenti
                </small>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (empty($cursor)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non ci sono film di questo genere salvati nel Database 
            </div>
        <?php else: ?>

            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                
                <?php 
                /** * Iterazione della collezione di film.
                 * Ogni elemento $film è un array associativo contenente i dati del database.
                 */
                foreach ($cursor as $film): 
                    
                    // 1. Recupero dell'ID (fondamentale per link o operazioni specifiche)
                    $id = $film['id'] ?? '';

                    // 2. Gestione del Titolo con valore di fallback (default) se mancante
                    $titolo = $film['title'] ?? 'Titolo non disponibile';

                    /**
                     * 3. Costruzione dell'URL del Poster
                     * Se il percorso esiste, concateniamo l'URL base di TMDB.
                     * Altrimenti, usiamo un'immagine segnaposto (placeholder) per non rompere il layout.
                     */
                    $baseUrl = "https://image.tmdb.org/t/p/w500";
                    $placeholderUrl = "https://via.placeholder.com/500x750?text=Immagine+non+disponibile";

                    $posterPath = $film['poster_path'] ?? '';
                    $poster = !empty($posterPath) ? $baseUrl . $posterPath : $placeholderUrl;

                    /**
                     * 4. Estrazione dell'Anno
                     * La data arriva solitamente in formato 'YYYY-MM-DD'.
                     * Usiamo substr per prendere solo i primi 4 caratteri (l'anno).
                     */
                    $dataRilascio = $film['release_date'] ?? '';
                    $anno = !empty($dataRilascio) ? substr($dataRilascio, 0, 4) : 'N.D.';
                ?>

                <div class="col">
                    <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                            <div class="position-relative">
                                <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" style="object-fit: cover; aspect-ratio: 2/3;">
                            </div>
                            <div class="card-body p-2 d-flex flex-column bg-white">
                                <h6 class="card-title fw-bold text-truncate mb-1" style="font-size: 0.95rem;" title="<?= htmlspecialchars($titolo) ?>"><?= htmlspecialchars($titolo) ?></h6>
                                <div class="mt-auto">
                                    <small class="text-muted fw-medium" style="font-size: 0.85rem;"><?= htmlspecialchars($anno) ?></small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>