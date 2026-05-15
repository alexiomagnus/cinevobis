<?php
// Bacheca globale: mostra le ultime recensioni con i dettagli dei film.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/functions.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Dichiarazione variabili
$limit = 20;
$ids = [];
$films = [];
$recensioni_list = [];
$films_map = [];

try {
    // Recupero le ultime 20 recensioni globali includendo nome, cognome e username
    $sql = "SELECT tmdb_id, commento, voto, nome, cognome, u.id_utente, u.username
            FROM recensioni r
            JOIN utenti u ON r.id_utente = u.id_utente
            ORDER BY data_aggiunto DESC 
            LIMIT :limit";
            
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $tmdb_id = (int) $row['tmdb_id'];
        $ids[] = $tmdb_id;

        $recensioni_list[] = [
            'tmdb_id' => $tmdb_id,
            'voto' => $row['voto'],
            'commento' => $row['commento'],
            'nome_completo' => $row['nome'] . ' ' . $row['cognome'],
            'id_utente' => $row['id_utente'],
            'username' => $row['username']
        ];
    }

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}


if (!empty($ids)) {
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db = $mongoClient->selectDatabase("cinevobis");
        $collection = $db->selectCollection("films");

        $cursor = $collection->find(
            ['id' => ['$in' => $ids]],
            [
                'sort' => ['vote_average' => -1],
                'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array'],
            ]
        );

        // Creiamo una mappa veloce per accedere ai film
        foreach ($cursor as $film) {
            $films_map[$film['id']] = $film;
        }

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
        $films_map = [];
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bacheca Recensioni - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root { --accent-color: #ffc107; }
        .text-justify { text-align: justify; }
        .review-poster {
            width: 120px;
            min-width: 120px;
            aspect-ratio: 2/3;
            object-fit: cover;
            border-radius: 0.5rem 0 0 0.5rem;
        }
        .transition-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <div class="d-flex align-items-center mb-4">
            <h1 class="fw-bold m-0">Bacheca</h1>
        </div>

        <?php if (empty($films_map) || empty($recensioni_list)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non ci sono ancora recensioni in bacheca
            </div>
        <?php else: ?>
            <p class="text-muted mb-4">Le ultime recensioni della community</p>
            
            <div class="row row-cols-1 row-cols-md-2 g-3">
                <?php
                foreach ($recensioni_list as $rec):
                    $id = $rec['tmdb_id'];

                    $film = $films_map[$id] ?? []; 
                    
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : "https://via.placeholder.com/500x750?text=No+Poster";
                    
                    $voto = isset($rec['voto']) ? (float) $rec['voto'] : null;
                    $commento = $rec['commento'] ?? '';
                    $nome_autore = $rec['nome_completo'] ?? 'Utente Anonimo';
                    $id_autore = $rec['id_utente'] ?? 0;
                    $username = $rec['username'] ?? '';
                ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover">
                        <div class="d-flex">
                            <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="poster-link">
                                <img src="<?= htmlspecialchars($poster) ?>"
                                     alt="<?= htmlspecialchars($titolo) ?>"
                                     class="review-poster">
                            </a>

                            <div class="card-body d-flex flex-column justify-content-between p-3">
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        <a href="/pages/public/film.php?tmdb_id=<?= urldecode($id) ?>" class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($titolo) ?>
                                        </a>
                                    </h5>
                                    
                                    <div class="small mb-2">
                                        <i class="bi bi-person-circle me-1" style="color: var(--accent);"></i>
                                        <a href="/pages/public/users_profiles.php?id=<?= urlencode($id_autore) ?>&username=<?= urlencode($username) ?>" 
                                           class="text-decoration-none fw-semibold text-dark">
                                            <?= htmlspecialchars($nome_autore) ?>
                                        </a>
                                    </div>

                                    <?php if (!empty($commento)): ?>
                                        <p class="text-muted small mb-2 text-justify text-break">
                                            "<?= nl2br(htmlspecialchars($commento)) ?>"
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <?php if ($voto !== null): ?>
                                    <div class="d-flex align-items-center gap-1 mt-1">
                                        <i class="bi bi-star-fill" style="color: var(--accent-color); font-size: 1rem;"></i>
                                        <span class="fw-bold fs-5"><?= $voto ?></span>
                                        <span class="text-muted small">/10</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
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