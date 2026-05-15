<?php
// Pagina di esplorazione per genere. Riceve id e nome via GET, trova i film
// con quel genere in MongoDB e li mostra in una griglia di card.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');


// Connessione a MongoDB e ricerca film per genere
$id_genere = isset($_GET['id']) ? (int)$_GET['id'] : null;
$nome_genere = isset($_GET['name']) ? $_GET['name'] : null;
$cursor = [];

if (!empty($id_genere)) {
    try {
        // Controllo per evitare errori se MongoDB è offline
        if (!$collection) {
            throw new \Exception("Connessione a MongoDB non disponibile.");
        }

        $cursor = $collection->find(['genres.id' => $id_genere])->toArray();
        $count = count($cursor);
        
    } catch (\Throwable $e) { // \Throwable cattura sia Exception che Fatal Error
        error_log("Errore caricamento ricerca genere: " . $e->getMessage());
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
                // Iterazione della lista di film recuperata da MongoDB.
                foreach ($cursor as $film): 
                    
                    // Recupero dell'ID per generare il link alla pagina del film.
                    $id = $film['id'] ?? '';

                    // Titolo con valore di fallback se il campo non è presente.
                    $titolo = $film['title'] ?? 'Titolo non disponibile';

                    // Costruzione dell'URL del poster o fallback placeholder.
                    $baseUrl = "https://image.tmdb.org/t/p/w500";
                    $placeholderUrl = "https://via.placeholder.com/500x750?text=Immagine+non+disponibile";

                    $posterPath = $film['poster_path'] ?? '';
                    $poster = !empty($posterPath) ? $baseUrl . $posterPath : $placeholderUrl;

                    // Estraggo l'anno dalla data di rilascio nel formato YYYY-MM-DD.
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