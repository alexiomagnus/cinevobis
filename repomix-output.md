This file is a merged representation of a subset of the codebase, containing files not matching ignore patterns, combined into a single document by Repomix.

# File Summary

## Purpose
This file contains a packed representation of a subset of the repository's contents that is considered the most important context.
It is designed to be easily consumable by AI systems for analysis, code review,
or other automated processes.

## File Format
The content is organized as follows:
1. This summary section
2. Repository information
3. Directory structure
4. Repository files (if enabled)
5. Multiple file entries, each consisting of:
  a. A header with the file path (## File: path/to/file)
  b. The full contents of the file in a code block

## Usage Guidelines
- This file should be treated as read-only. Any changes should be made to the
  original repository files, not this packed version.
- When processing this file, use the file path to distinguish
  between different files in the repository.
- Be aware that this file may contain sensitive information. Handle it with
  the same level of security as you would the original repository.

## Notes
- Some files may have been excluded based on .gitignore rules and Repomix's configuration
- Binary files are not included in this packed representation. Please refer to the Repository Structure section for a complete list of file paths, including binary files
- Files matching these patterns are excluded: **/node_modules/**, **/dist/**, **/.git/**, **/package-lock.json/**, **/database
- Files matching patterns in .gitignore are excluded
- Files matching default ignore patterns are excluded
- Files are sorted by Git change count (files with more changes are at the bottom)

# Directory Structure
```
actions/
  change_password.php
  contact.php
  logout.php
assets/
  css/
    style.css
  img/
    astronaut.jpeg
    breakingbad.jpeg
    interstellar.jpg
  js/
    script.js
config/
  config.php
  connection.php
includes/
  footer.php
  header_logic.php
  header.php
  movie_obj.php
  user_obj.php
pages/
  admin/
    dashboard.php
    edit_user.php
    film_db.php
    films.php
    notifications.php
    sessions.php
    users.php
  public/
    film.php
    login.php
    privacy.php
    search_genre.php
    search.php
    signup.php
    terms.php
    users_reviews.php
  user/
    favorites.php
    notice_board.php
    profile.php
    review.php
    reviews.php
    watched.php
    watchlist.php
.gitignore
composer.json
index.php
package.json
README.md
```

# Files

## File: pages/user/notice_board.php
```php
<?php
/**
 * Pagina bacheca globale che mostra le ultime 20 recensioni inserite nel sistema.
 * Recupera i dati testuali (voto, commento, autore) da MariaDB e i dettagli 
 * tecnici del film (titolo, locandina, ecc.) da MongoDB.
 * * Il recupero da MongoDB avviene tramite una query batch ($in) su TMDB ID,
 * con successivo riordinamento manuale per preservare la cronologia (data_aggiunto).
 *
 * @note Interagisce con:
 * - MariaDB: tabelle `recensioni` e `utenti` (per i dati sociali).
 * - MongoDB: collezione `films` (per i metadati dei media).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Controllo autenticazione
$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}

// Configurazione query
$limit = 20;
$ids = [];
$recensioni_map = [];
$films = [];

try {
    // Recupero le ultime 20 recensioni globali includendo nome e cognome
    $sql = "SELECT tmdb_id, commento, voto, nome, cognome
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

        $recensioni_map[$tmdb_id] = [
            'voto' => $row['voto'],
            'commento' => $row['commento'],
            'autore' => $row['nome'] . ' ' . $row['cognome'] // Mappatura nome e cognome
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
            ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
        );
        
        // Da puntatore ad array, si estraggono i dati da MongoDB come array
        $raw_films = iterator_to_array($cursor);

        // --- Riordinamento manuale ---
        $films_map = [];

        foreach ($raw_films as $f) {
            $films_map[$f['id']] = $f;
        }

        // Ricostruiamo la lista $films seguendo l'ordine esatto di $ids
        $films = [];

        foreach ($ids as $id) {
            if (isset($films_map[$id])) {
                $films[] = $films_map[$id];
            }
        }

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
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
            <i class="bi bi-journal-text fs-2 me-3 text-warning"></i>
            <h1 class="fw-bold m-0">Bacheca</h1>
        </div>

        <p class="text-muted mb-4">Le ultime recensioni della community</p>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non ci sono ancora recensioni in bacheca
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-3">
                <?php
                foreach ($films as $film):
                    $id = (int) ($film['id'] ?? 0);
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path'])
                        ? "https://image.tmdb.org/t/p/w500" . $film['poster_path']
                        : "https://via.placeholder.com/500x750?text=No+Poster";
                    
                    $rec = $recensioni_map[$id] ?? [];
                    $voto = isset($rec['voto']) ? (float) $rec['voto'] : null;
                    $commento = $rec['commento'] ?? '';
                    $autore = $rec['autore'] ?? 'Utente Anonimo';
                ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover position-relative">
                        <div class="d-flex">
                            <img src="<?= htmlspecialchars($poster) ?>"
                                 alt="<?= htmlspecialchars($titolo) ?>"
                                 class="review-poster">

                            <div class="card-body d-flex flex-column justify-content-between p-3">
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark stretched-link">
                                            <?= htmlspecialchars($titolo) ?>
                                        </a>
                                    </h5>
                                    
                                    <div class="small text-primary mb-2">
                                        <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($autore) ?>
                                    </div>

                                    <?php if (!empty($commento)): ?>
                                        <p class="text-muted small mb-2 text-justify">
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
```

## File: README.md
```markdown
PHP project with a MySQL Database
```

## File: package.json
```json
{
  "name": "cinevobis",
  "version": "1.0.0",
  "description": "PHP project with a MySQL Database",
  "main": "index.js",
  "scripts": {
    "test": "echo \"Error: no test specified\" && exit 1"
  },
  "repository": {
    "type": "git",
    "url": "git+https://github.com/alexiomagnus/cinevobis.git"
  },
  "keywords": [],
  "author": "",
  "license": "ISC",
  "type": "commonjs",
  "bugs": {
    "url": "https://github.com/alexiomagnus/cinevobis/issues"
  },
  "homepage": "https://github.com/alexiomagnus/cinevobis#readme",
  "dependencies": {
    "bootstrap": "^5.3.8",
    "bootstrap-icons": "^1.13.1",
    "tom-select": "^2.5.2"
  }
}
```

## File: pages/public/search_genre.php
```php
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
                /** @var array $film */
                foreach ($cursor as $film):
                    $id = $film['id'] ?? '';
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) 
                        ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] 
                        : "https://via.placeholder.com/500x750?text=No+Poster";
                    $anno = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
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
```

## File: .gitignore
```
/vendor/
/node_modules/
.env
config/php_errors.log
```

## File: config/connection.php
```php
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
```

## File: includes/header_logic.php
```php
<?php
/**
 * Logica di routing lato server per le azioni inviate tramite i form dell'header
 * (navbar). Intercetta le richieste POST e reindirizza alle pagine appropriate:
 * logout, login, signup e profilo. Viene incluso in ogni pagina che usa l'header
 * per garantire che i pulsanti della navbar funzionino correttamente.
 */
$currentPage = basename($_SERVER['SCRIPT_NAME']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        header("Location: /actions/logout.php");
        exit();
    }
    if (isset($_POST['login']) && $currentPage !== 'login.php') {
        header("Location: /pages/public/login.php");
        exit();
    }
    if (isset($_POST['signup']) && $currentPage !== 'signup.php') {
        header("Location: /pages/public/signup.php");
        exit();
    }
    if (isset($_POST['profile'])) {
        header("Location: /actions/settings.php");
        exit();
    }
}
```

## File: pages/public/terms.php
```php
<?php
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termini di Servizio - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>
    
    <main class="container flex-grow-1 py-5">
        <div class="card shadow-sm border-0 p-4 p-md-5">
            <h1 class="fw-bold mb-4">Termini di Servizio</h1>
            
            <p class="text-muted">Ultimo aggiornamento: 25 Marzo 2026</p>
            
            <section class="mb-4 mt-4">
                <h2 class="h4 fw-bold">1. Accettazione dei Termini</h2>
                <p>Creando un account su Cinevobis o utilizzando il nostro servizio, accetti di essere vincolato dai presenti Termini di Servizio. Se non accetti queste condizioni, ti invitiamo a non utilizzare la piattaforma.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">2. Account Utente</h2>
                <p>Dichiari di avere almeno 16 anni per utilizzare il servizio, dato che alcuni contenuti possono essere soggetti a restrizione di età. Sei responsabile del mantenimento della riservatezza delle credenziali del tuo account e di tutte le attività che avvengono sotto il tuo profilo. Ti impegni a fornirci informazioni accurate e complete al momento della registrazione.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">3. Contenuti e Comportamento</h2>
                <p>Gli utenti possono inserire recensioni e valutazioni. È severamente vietato pubblicare contenuti offensivi, illegali, diffamatori o che violano i diritti di copyright di terzi. Cinevobis si riserva il diritto di rimuovere tali contenuti e di sospendere gli account responsabili.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">4. Modifiche al Servizio</h2>
                <p>Ci riserviamo il diritto di modificare, sospendere o interrompere il servizio in qualsiasi momento, con o senza preavviso. Non saremo responsabili verso di te o terze parti per qualsiasi modifica o interruzione del servizio.</p>
            </section>
        </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    
    <script src="/assets/js/script.js"></script>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## File: composer.json
```json
{
    "name": "alexio/cinevobis",
    "description": "PHP project with a MySQL Database",
    "require": {
        "vlucas/phpdotenv": "^5.6",
        "kiwilan/php-tmdb": "^0.1.12",
        "mongodb/mongodb": "^2.2"
    },
    "config": {
        "allow-plugins": {
            "php-http/discovery": true
        }
    }
}
```

## File: actions/contact.php
```php
<?php
/**
 * Pagina di contatto: permette a qualsiasi visitatore (anche non autenticato)
 * di inviare un messaggio di supporto all'amministratore. Il messaggio viene
 * salvato come notifica nel database con il riferimento all'utente se loggato.
 *
 * @note Interagisce con la tabella MariaDB: `notifiche`.
 */
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/connection.php');

$erroe = "";
$messaggio = "";

if (isset($_POST['invia'])) {
    $titolo = $_POST['titolo'];
    $descrizione = $_POST['descrizione'];

    try {
        $sql = "INSERT INTO notifiche (titolo, descrizione, data_invio, id_utente) VALUES 
            (:titolo, :descrizione, :data_invio, :id_utente)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':titolo' => $titolo,
            ':descrizione' => $descrizione,
            ':data_invio' => date('Y-m-d H:i:s'),
            ':id_utente' => empty($_SESSION['id_utente']) ? null : $_SESSION['id_utente']
        ]);
        
        $messaggio = "Messaggio inviato con successo";
    } catch (PDOException $e) {
        error_log("Errore: " . $e);
        $errore = "Si è verificato un errore durante l'invio";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contattaci - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4 px-4">

                <a href="javascript:void(0)"
                   onclick="closeAndRedirect()"
                   class="btn-close position-absolute top-0 start-0 m-4"
                   aria-label="Close">
                </a>

                <div class="text-center mb-5">
                    <h1 class="display-6 fw-bolder mb-2">Contattaci</h1>
                    <p class="text-secondary">Inviaci un messaggio per qualsiasi necessità</p>
                </div>

                <?php if (!empty($errore)): ?>
                    <div class="alert alert-danger border-0 small py-2 mb-4 text-center"><?= htmlspecialchars($errore) ?></div>
                <?php endif; ?>

                <?php if (!empty($messaggio)): ?>
                    <div class="alert alert-success border-0 small py-2 mb-4 text-center"><?= htmlspecialchars($messaggio) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label small text-secondary">Titolo</label>
                        <input type="text"
                               name="titolo"
                               id="titolo"
                               maxlength="50"
                               class="form-control bg-light border-light py-3"
                               placeholder="Inserisci un titolo"
                               required>
                    </div>

                    <div class="mb-5">
                        <label class="form-label small text-secondary">Descrizione</label>
                        <textarea name="descrizione"
                                  id="descrizione"
                                  class="form-control bg-light border-light py-3"
                                  rows="6"
                                  maxlength="200"
                                  placeholder="Descrivi il tuo messaggio..."
                                  required></textarea>
                        <div class="form-text text-end">
                            Limite massimo: 200 caratteri
                        </div>
                    </div>

                    <button type="submit" name="invia" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">
                        Invia
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>

</body>
</html>
```

## File: pages/admin/film_db.php
```php
<?php
/**
 * Pagina di dettaglio film per l'area admin. A differenza della versione pubblica,
 * recupera i dati esclusivamente da MongoDB (senza interrogare l'API TMDB)
 * e li visualizza in sola lettura con poster, trama, cast, registi e trailer.
 * Riceve il TMDB ID tramite GET (?tmdb_id=...).
 *
 * @note Interagisce con la collezione MongoDB: `films` (findOne).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/movie_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Dichiarazione variabili
$movie_db = null;
$errore = "";

$movie_id = $_GET['tmdb_id'] ?? null;

if (empty($movie_id)) {
    $errore = "Nessun film selezionato";
} else {
    // Connessione a MongoDB e recupero diretto
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db = $mongoClient->selectDatabase('cinevobis');
        $collection = $db->selectCollection('films');

        $movie_db = $collection->findOne(
            ['id' => (int)$movie_id],
            ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
        );

        if ($movie_db === null) {
            $errore = "Film non trovato nel database";
        }

    } catch (Exception $e) {
        error_log("Errore MongoDB: " . $e->getMessage());
        $errore = "Errore di connessione al database";
    }
}

// 3. Estrazione dati
$titolo = $trama = $poster_path = $trailerKey = $paese = '';
$voto = 0;
$durata = $anno = '';
$generi = $cast = $registi = [];

if ($movie_db) {
    $movieObj = new movieObj($movie_db);
    $data = $movieObj->toArray();

    $titolo = $data['titolo'];
    $titolo_orig = $data['titolo_orig'];

    $trama = $data['trama'];
    $poster_path = $data['poster_path'];

    $voto = $data['voto'];
    $trailerKey = $data['trailer_key'];

    $durata = $data['durata'];
    $anno = $data['anno'];

    $generi = $data['generi'];
    $paese = $data['paese'];

    $cast = $data['cast'];
    $registi = $data['registi'];
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $movie_db ? htmlspecialchars($titolo) : 'Film' ?> - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root { --accent-color: #ffc107; }

        .text-justify   { text-align: justify; }

        .cast-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container my-5 flex-grow-1">
        <?php if ($movie_db): ?>
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white">

                        <div class="row g-5 mb-5">
                            <div class="col-md-4">
                                <?php if($poster_path): ?>
                                    <img src="https://image.tmdb.org/t/p/w500<?= $poster_path ?>" class="img-fluid rounded-4 shadow-sm w-100" alt="Poster">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded-4 shadow-sm w-100" style="aspect-ratio: 2/3; border: 2px dashed #dee2e6;">
                                        <div class="text-center">
                                            <i class="bi bi-film text-muted" style="font-size: 4rem;"></i>
                                            <p class="text-muted small mt-2">Poster non disponibile</p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($trailerKey): ?>
                                    <div class="mt-3">
                                        <button type="button"
                                            class="btn btn-dark w-100 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#trailerModal">
                                            <i class="bi bi-play-fill fs-4 me-2"></i> Trailer
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-8">
                                <h1 class="fw-bold text-dark display-5 mb-3"><?= htmlspecialchars($titolo) ?></h1>

                                <?php if (!empty($titolo_orig) && strcasecmp(trim($titolo_orig), trim($titolo)) !== 0): ?>
                                    <p class="text-muted fs-5 mb-4"><?= htmlspecialchars($titolo_orig) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($registi)): ?>
                                    <div class="mb-4">
                                        <small class="text-uppercase fw-bold text-muted d-block mb-2" style="letter-spacing:1px">Regia</small>
                                        <p class="fs-5 fw-medium mb-0">
                                            <?php 
                                            $registi_links = [];
                                            foreach ($registi as $regista) {
                                                $name = htmlspecialchars($regista['name']);
                                                $id = urlencode($regista['id']);
                                                $registi_links[] = "<a href='https://www.themoviedb.org/person/$id' class='text-decoration-none link-dark'>$name</a>";
                                            }
                                            echo implode(', ', $registi_links);
                                            ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($generi as $genre): ?>
                                        <span class="badge bg-white text-dark border rounded-pill px-3 py-2">
                                            <?= htmlspecialchars($genre['name']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>

                                <div class="border-top pt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="fw-bold m-0">Trama</h4>
                                        <div class="d-flex align-items-center fs-4 fw-bold">
                                            <i class="bi bi-star-fill text-warning me-2"></i>
                                            <span>
                                                <?= number_format($voto, 1) ?>
                                                <small class="text-muted fw-normal fs-6">/ 10</small>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-justify lh-lg text-dark fs-6 mb-4"><?= nl2br(htmlspecialchars($trama)) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row text-center py-4 bg-light rounded-4 mb-5 border mx-0">
                            <div class="col-4 border-end">
                                <div class="small text-muted text-uppercase fw-bold">Durata</div>
                                <div class="fw-bold"><?= $durata ?> min</div>
                            </div>
                            <div class="col-4 border-end">
                                <div class="small text-muted text-uppercase fw-bold">Anno</div>
                                <div class="fw-bold"><?= $anno ?></div>
                            </div>
                            <div class="col-4">
                                <div class="small text-muted text-uppercase fw-bold">Paese</div>
                                <div class="fw-bold"><?= $paese ?></div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <h4 class="fw-bold mb-4">Cast Principale</h4>
                            <div class="row g-3">
                                <?php foreach ($cast as $actor):
                                    $profile = $actor['profile_path']
                                        ? "https://image.tmdb.org/t/p/w185" . $actor['profile_path']
                                        : "https://ui-avatars.com/api/?name=" . urlencode($actor['name']) . "&background=random";
                                ?>
                                    <div class="col-12 col-sm-6 col-lg-4">
                                        <a href="https://www.themoviedb.org/person/<?= $actor['id'] ?>"
                                           class="text-decoration-none d-block">
                                            <div class="d-flex align-items-center p-2 border rounded-3 bg-light shadow-sm transition-hover">
                                                <img src="<?= $profile ?>"
                                                     class="cast-avatar rounded-circle border border-2 border-white shadow-sm me-3"
                                                     loading="lazy"
                                                     alt="<?= htmlspecialchars($actor['name']) ?>">
                                                <div class="overflow-hidden">
                                                    <p class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.9rem;">
                                                        <?= htmlspecialchars($actor['name']) ?>
                                                    </p>
                                                    <p class="mb-0 text-muted text-truncate" style="font-size: 0.8rem;">
                                                        <?= htmlspecialchars($actor['character']) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?php if ($trailerKey): ?>
            <div class="modal fade" id="trailerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content bg-transparent border-0">
                        <div class="d-flex justify-content-end mb-4">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="ratio ratio-16x9 shadow-lg rounded-4 overflow-hidden">
                                <iframe id="trailerVideo"
                                    data-src="https://www.youtube.com/embed/<?= $trailerKey ?>?rel=0&autoplay=1"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-warning shadow-sm rounded-4"><?= htmlspecialchars($errore) ?></div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: config/config.php
```php
<?php
// --- Cookie di sessione ---
ini_set('session.cookie_httponly', 1);      // JS non può leggere il cookie
ini_set('session.cookie_samesite', 'Lax');  // Protezione CSRF base
session_start();

// --- Gestione errori ---
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');
error_reporting(E_ALL);

// --- Scadenza sessione per inattività ---
define('SESSION_TIMEOUT', 3600);

if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        
        // Aggiorna il DB se c'è un utente loggato
        if (isset($_SESSION['username'])) {
            require_once(__DIR__ . '/connection.php');
            require_once(__DIR__ . '/../includes/user_obj.php');

            try {
                $user = new userObj($conn, $_SESSION['username']);
                $user->setDataLogout(date('Y-m-d H:i:s'), session_id());

            } catch (Exception $e) {
                error_log("Errore logout automatico: " . $e->getMessage());
            }
        }
        
        session_unset();
        session_destroy();
        header("Location: /login.php?error=session_expired");
        exit();
    }
}

$_SESSION['last_activity'] = time();
```

## File: pages/public/privacy.php
```php
<?php
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informativa sulla Privacy - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>
    
    <main class="container flex-grow-1 py-5">
        <div class="card shadow-sm border-0 p-4 p-md-5">
            <h1 class="fw-bold mb-4">Informativa sulla Privacy</h1>
            
            <p class="text-muted">Ultimo aggiornamento: 25 Marzo 2026</p>
            
            <section class="mb-4 mt-4">
                <h2 class="h4 fw-bold">1. Raccolta dei dati</h2>
                <p>Quando ti registri su Cinevobis, raccogliamo informazioni come il tuo nome, cognome, indirizzo email e il paese di residenza, necessari per fornirti il nostro servizio di tracciamento e recensione dei film.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">2. Utilizzo dei dati</h2>
                <p>I tuoi dati vengono utilizzati esclusivamente per gestire il tuo profilo utente, personalizzare la tua esperienza sul sito, permetterti di salvare le tue preferenze cinematografiche e garantire la sicurezza del tuo account.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">3. Conservazione e Sicurezza</h2>
                <p>Non vendiamo né condividiamo i tuoi dati personali con terze parti per scopi commerciali o di marketing. Adottiamo misure di sicurezza standard per proteggere le tue informazioni (come le password crittografate) da accessi non autorizzati.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">4. I tuoi diritti</h2>
                <p>Hai il diritto in qualsiasi momento di richiedere la visualizzazione, la modifica o la cancellazione permanente del tuo account e dei dati ad esso associati tramite la tua area profilo.</p>
            </section>
        </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    
    <script src="/assets/js/script.js"></script>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## File: pages/public/users_reviews.php
```php
<?php
/**
 * Pagina pubblica che mostra le recensioni scritte dagli utenti per un film specifico.
 * Riceve il TMDB ID del film tramite GET (?tmdb_id=...) e recupera commento, voto
 * e dati anagrafici di ogni recensore tramite JOIN tra le tabelle recensioni e utenti.
 *
 * @note Interagisce con le tabelle MariaDB: `recensioni`, `utenti` (JOIN).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

$recensioni_altri = [];
$movie_id = $_GET['tmdb_id'] ?? null;

// Recuperiamo le recensioni degli altri utenti
try {   
    $sql = "SELECT r.commento, r.voto, u.nome, u.cognome
            FROM recensioni r
            JOIN utenti u ON r.id_utente = u.id_utente 
            WHERE tmdb_id = :tmdb_id
            ORDER BY r.tmdb_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':tmdb_id' => $movie_id]);

    $recensioni_altri = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recensioni della Community - Cinevobis</title>
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
        <h1 class="fw-bold mb-2">Recensioni della Community</h1>
        <p class="text-muted mb-4">Scopri cosa pensano gli altri utenti</p>

            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($recensioni_altri as $r): 
                    $poster_url = !empty($r['poster'])
                        ? "https://image.tmdb.org/t/p/w500" . $r['poster']
                        : "https://via.placeholder.com/500x750?text=No+Poster";
                ?>
                <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover">
                            <div class="d-flex h-100">

                                <div class="card-body d-flex flex-column justify-content-between p-3">
                                    <div>
                                        <h5 class="fw-bold mb-1 text-dark">
                                            <?= htmlspecialchars($r['titolo']) ?>
                                        </h5>
                                        
                                        <div class="small text-primary mb-2">
                                            <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($r['nome']) . " " . htmlspecialchars($r['cognome'])?>
                                        </div>

                                        <?php if (!empty($r['commento'])): ?>
                                            <p class="text-muted small mb-2 text-justify">
                                                "<?= nl2br(htmlspecialchars($r['commento'])) ?>"
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex align-items-center gap-1 mt-2">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span class="fw-bold fs-5"><?= number_format($r['voto'], 1) ?></span>
                                        <span class="text-muted small">/10</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## File: includes/movie_obj.php
```php
<?php
/**
 * Rappresenta un film e si occupa di normalizzare i dati grezzi provenienti
 * dall'API TMDB o da MongoDB in un formato strutturato e uniforme.
 */
class movieObj
{
    private string $titolo;
    private string $titolo_orig;
    private string $trama;
    private ?string $poster_path;
    private float $voto;
    private $durata;
    private $anno;
    private array $generi;
    private array $cast;
    private ?string $trailer_key;
    private string $paese;
    private array $registi;

    
    /**
     * Popola le proprietà del film a partire da un array grezzo (TMDB o MongoDB).
     * Applica valori di fallback per i campi mancanti e limita il cast ai primi 12 attori.
     *
     * @param array $data Array associativo con i dati del film (struttura TMDB).
     */
    public function __construct(array $data)
    {
        $this->titolo = $data['title'] ?? 'Titolo non disponibile';
        $this->titolo_orig = $data['original_title'] ?? '';

        $this->trama = !empty($data['overview']) ? $data['overview'] : 'Nessuna trama disponibile';        
        $this->poster_path = !empty($data['poster_path']) ? $data['poster_path'] : null;

        $this->voto = (float)($data['vote_average'] ?? 0);
        $this->trailer_key = $data['videos']['results'][0]['key'] ?? null;

        $this->durata = $data['runtime'] ?? 'N/A';
        $this->anno = !empty($data['release_date']) ? substr($data['release_date'], 0, 4) : 'N/A';

        $this->generi = $data['genres'] ?? [];
        $this->paese = $data['production_countries'][0]['name'] ?? 'Nessun paese';
        
        $this->cast = array_slice($data['credits']['cast'] ?? [], 0, 12);
        $this->registi = $this->searchDirectors($data);
    }


    /**
     * Filtra il crew del film per estrarre solo i membri con job === 'Director'.
     * Reindizza l'array risultante per rimuovere i gap numerici lasciati da array_filter.
     *
     * @param array $data Array grezzo del film contenente la chiave 'credits.crew'.
     * @return array Array dei registi con i loro dati TMDB.
     */
    private function searchDirectors(array $data): array
    {
        $crew = $data['credits']['crew'] ?? [];

        $directors = array_filter($crew, function ($persona) {
            return ($persona['job'] ?? '') === 'Director';
        });

        return array_values($directors);
    }


    /**
     * Converte un array di risultati di ricerca TMDB in un formato semplificato
     * adatto alla visualizzazione nelle liste (id, titolo, anno, URL poster thumbnail).
     *
     * @param array $movies Array di film nel formato restituito dall'endpoint /search/movie di TMDB.
     * @return array Array semplificato con id, titolo, anno e URL poster (w92).
     */
    public static function search(array $movies): array
    {
        $moviesList = [];
        foreach ($movies as $movie) {
            $moviesList[] = [
                'id' => $movie['id'],
                'titolo' => $movie['title'] ?? 'Titolo non disponibile.',
                'anno'   => !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : null,
                'poster' => !empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w92' . $movie['poster_path'] : null
            ];
        }
        return $moviesList;
    }


    /**
     * Serializza tutte le proprietà del film in un array associativo.
     * Utile per passare i dati alle view senza esporre l'oggetto direttamente.
     *
     * @return array Array associativo con tutti i campi del film (titolo, trama, cast, ecc.).
     */
    public function toArray(): array
    {
        return [
            'titolo' => $this->titolo,
            'titolo_orig' => $this->titolo_orig,
            'trama' => $this->trama,
            'poster_path' => $this->poster_path,
            'voto' => $this->voto,
            'durata' => $this->durata,
            'anno' => $this->anno,
            'generi' => $this->generi,
            'paese' => $this->paese,
            'cast' => $this->cast,
            'registi' => $this->registi,
            'trailer_key' => $this->trailer_key
        ];
    }
}
```

## File: includes/user_obj.php
```php
<?php
/**
 * Rappresenta un utente del sistema e raggruppa tutte le operazioni CRUD
 * relative agli account e alle sessioni di accesso.
 */
class userObj {
    private string $username;
    private ?string $password;
    private ?string $nome;
    private ?string $cognome;
    private ?string $email;
    private ?int $id_profilo;
    private ?int $attivo;
    private PDO $db;


    /**
     * Inizializza l'oggetto utente con i dati forniti.
     * La password, se presente, viene subito sottoposta a hashing con PASSWORD_DEFAULT.
     *
     * @param PDO         $db          Connessione al database MariaDB.
     * @param string      $username    Nome utente univoco.
     * @param string|null $password    Password in chiaro (verrà hashata).
     * @param string|null $nome        Nome anagrafico.
     * @param string|null $cognome     Cognome anagrafico.
     * @param string|null $email       Indirizzo email.
     * @param int|null    $attivo      Flag di attivazione account (1 = attivo, 0 = disabilitato).
     * @param int|null    $id_profilo  Identificativo del ruolo (es. 1 = admin, 2 = utente).
     */
    public function __construct(PDO $db, string $username, ?string $password = null, ?string $nome = null, ?string $cognome = null,
                            ?string $email = null, ?int $attivo = null, ?int $id_profilo = null) {
        $this->db           = $db;
        $this->username     = $username;
        $this->password     = $password ? password_hash($password, PASSWORD_DEFAULT) : null;
        $this->nome         = $nome;
        $this->cognome      = $cognome;
        $this->email        = $email;
        $this->attivo       = $attivo;
        $this->id_profilo   = $id_profilo;
    }


    /**
     * Inserisce un nuovo utente nel database con la data di registrazione corrente.
     * Se il campo `attivo` non è specificato nel costruttore, viene impostato a 1 (attivo).
     *
     * @return bool True se l'inserimento è andato a buon fine, false altrimenti.
     * @note Interagisce con la tabella MariaDB: `utenti`.
     */
    public function create() {
        $sql = "INSERT INTO utenti 
                    (username, password, nome, cognome, email, attivo, id_profilo, data_registrazione)
                VALUES 
                    (:username, :password, :nome, :cognome, :email, :attivo, :id_profilo, :data_registrazione)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':username'           => $this->username,
            ':password'           => $this->password,
            ':nome'               => $this->nome,
            ':cognome'            => $this->cognome,
            ':email'              => $this->email,
            ':attivo'             => $this->attivo ?? 1,
            ':id_profilo'         => $this->id_profilo,
            ':data_registrazione' => date('Y-m-d H:i:s')
        ]);
    }


    /**
     * Cerca e restituisce il record completo dell'utente corrispondente all'username
     * impostato nel costruttore.
     *
     * @return array|false Array associativo con i dati dell'utente, o false se non trovato.
     * @note Interagisce con la tabella MariaDB: `utenti`.
     */
    public function findByUsername() {
        $sql = "SELECT id_utente, username, password, nome, cognome, email,
                       attivo, id_profilo, data_registrazione
                FROM utenti WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        return $stmt->fetch();
    }


    /**
     * Restituisce l'elenco completo di tutti gli utenti registrati, incluso il nome
     * del profilo/ruolo, ordinati alfabeticamente per username.
     *
     * @return array Array di array associativi con i dati di ciascun utente.
     * @note Interagisce con le tabelle MariaDB: `utenti`, `profili` (JOIN).
     */
    public function readAll() {
        $sql = "SELECT u.id_utente, u.username, u.nome, u.cognome, u.email,
                       u.attivo, p.nome_profilo
                FROM utenti u
                LEFT JOIN profili p ON p.id_profilo = u.id_profilo
                ORDER BY u.username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    /**
     * Aggiorna i dati anagrafici, lo stato e il ruolo dell'utente identificato
     * da `$usernameOriginale` (utile quando si consente il cambio username).
     *
     * @param string $usernameOriginale Username usato come chiave di ricerca nel WHERE.
     * @return bool True se l'aggiornamento è andato a buon fine, false altrimenti.
     * @note Interagisce con la tabella MariaDB: `utenti`.
     */
    public function update(string $usernameOriginale) {
        $sql = "UPDATE utenti SET
                    nome       = :nome,
                    cognome    = :cognome,
                    email      = :email,
                    attivo     = :attivo,
                    id_profilo = :id_profilo
                WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome'       => $this->nome,
            ':cognome'    => $this->cognome,
            ':email'      => $this->email,
            ':attivo'     => $this->attivo,
            ':id_profilo' => $this->id_profilo,
            ':username'   => $usernameOriginale
        ]);
    }


    /**
     * Cambia la password dell'utente dopo aver verificato che quella attuale sia corretta.
     * Restituisce un array con la chiave 'ok' (bool) e, in caso di errore, 'errore' (string).
     *
     * @param string $passwordAttuale Password corrente in chiaro per la verifica.
     * @param string $nuovaPassword   Nuova password in chiaro che verrà hashata.
     * @return array{ok: bool, errore?: string} Esito dell'operazione.
     * @note Interagisce con la tabella MariaDB: `utenti`.
     */
    public function changePassword(string $passwordAttuale, string $nuovaPassword) {
        $utente = $this->findByUsername();

        if (!$utente) {
            return ['ok' => false, 'errore' => 'Utente non trovato'];
        }

        if (!password_verify($passwordAttuale, $utente['password'])) {
            return ['ok' => false, 'errore' => 'Password attuale non corretta'];
        }

        $hash = password_hash($nuovaPassword, PASSWORD_DEFAULT);
        $sql  = "UPDATE utenti SET password = :password WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':password' => $hash, ':username' => $this->username]);

        return ['ok' => true];
    }


    /**
     * Elimina definitivamente l'utente dal database tramite il suo username.
     *
     * @return bool True se la riga è stata eliminata con successo, false altrimenti.
     * @note Interagisce con la tabella MariaDB: `utenti`.
     */
    public function delete() {
        $sql  = "DELETE FROM utenti WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':username' => $this->username]);
    }


    /**
     * Registra l'accesso dell'utente creando un nuovo record di sessione con
     * la data e l'ora di login.
     *
     * @param string $value       Data e ora del login nel formato 'Y-m-d H:i:s'.
     * @param string $id_sessione ID di sessione PHP corrente.
     * @param int    $id_utente   ID numerico dell'utente che ha effettuato il login.
     * @return bool True se il record è stato inserito, false altrimenti.
     * @note Interagisce con la tabella MariaDB: `sessioni`.
     */
    public function createDataLogin(string $value, string $id_sessione, int $id_utente) {
        $sql = "INSERT INTO sessioni (id_sessione, id_utente, data_login)
                VALUES (:id_s, :id_u, :data_login)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_s'       => $id_sessione,
            ':id_u'       => $id_utente,
            ':data_login' => $value
        ]);
    }


    /**
     * Registra la data e l'ora di logout aggiornando il record di sessione
     * corrispondente all'ID di sessione fornito.
     *
     * @param string $value       Data e ora del logout nel formato 'Y-m-d H:i:s'.
     * @param string $id_sessione ID di sessione PHP da aggiornare.
     * @return bool True se l'aggiornamento è andato a buon fine, false altrimenti.
     * @note Interagisce con la tabella MariaDB: `sessioni`.
     */
    public function setDataLogout(string $value, string $id_sessione) {
        $sql = "UPDATE sessioni SET data_logout = :value WHERE id_sessione = :id_s";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':value' => $value, 
            ':id_s'  => $id_sessione
        ]);
    }

    
    /**
     * Restituisce le ultime N sessioni di accesso al sito, ordinate dalla più recente,
     * con username, data di login e data di logout di ciascun accesso.
     *
     * @param int $num Numero massimo di righe da restituire.
     * @return array Array di array associativi con i dettagli delle sessioni.
     * @note Interagisce con le tabelle MariaDB: `sessioni`, `utenti` (JOIN).
     */
    public function readAccess(int $num) {
        $sql = "SELECT u.username, s.data_login, s.data_logout
                FROM sessioni s
                JOIN utenti u ON u.id_utente = s.id_utente
                ORDER BY s.data_login DESC
                LIMIT :num";
        $stmt = $this->db->prepare($sql);
        $num  = (int)$num;
        $stmt->bindParam(':num', $num, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
```

## File: pages/admin/dashboard.php
```php
<?php
/**
 * Dashboard amministrativa (riservata al profilo admin, id_profilo = 1).
 * Raccoglie e mostra quattro contatori statistici: numero di film nel catalogo
 * MongoDB, numero di utenti registrati, numero totale di sessioni e numero di
 * notifiche non lette. Fornisce link rapidi alle sezioni di gestione.
 *
 * @note Interagisce con la collezione MongoDB: `films` (countDocuments).
 * @note Interagisce con le tabelle MariaDB: `utenti`, `sessioni`, `notifiche`.
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;


// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}


// Dichiarazione variabili
$totaleFilm = 0;
$totaleUtenti = 0;
$totaleSessioni = 0;
$totaleNotifiche = 0;


// Connessione a MongoDB
try {
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

    // Conteggio documenti
    $totaleFilm = $collection->countDocuments([]);
} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}


// Conteggio utenti
try {
    $sql = "SELECT COUNT(*) FROM utenti";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $totaleUtenti = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Errore DB: " . $e->getMessage());
}


// Conteggio sessioni
try {
    $sql = "SELECT COUNT(*) FROM sessioni";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $totaleSessioni = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Errore DB: " . $e->getMessage());
}


// Conteggio notifiche
try {
    $sql = "SELECT COUNT(*) FROM notifiche WHERE letta = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $totaleNotifiche = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Errore DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container flex-grow-1 py-5">

        <!-- Titolo dashboard -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="fw-bold mb-0">Dashboard</h1>
            </div>
        </div>

        <!-- Statistiche -->
        <div class="row g-3 mb-5">
            <div class="col-6 col-lg-3">
                <div class="p-3 rounded-2 bg-body-secondary">
                    <div class="text-muted small mb-1">Film nel catalogo</div>
                    <div class="fs-3 fw-medium"><?= $totaleFilm ?></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="p-3 rounded-2 bg-body-secondary">
                    <div class="text-muted small mb-1">Utenti</div>
                    <div class="fs-3 fw-medium"><?= $totaleUtenti ?></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="p-3 rounded-2 bg-body-secondary">
                    <div class="text-muted small mb-1">Sessioni</div>
                    <div class="fs-3 fw-medium"><?= $totaleSessioni ?></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="p-3 rounded-2 bg-body-secondary">
                    <div class="text-muted small mb-1">Notifiche da leggere</div>
                    <div class="fs-3 fw-medium"><?= $totaleNotifiche ?></div>
                </div>
            </div>
        </div>

        <!-- Sezioni di gestione -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h5 fw-semibold text-uppercase text-muted mb-3">
                    Gestione
                </h2>
            </div>
        </div>

        <div class="row g-4">
            <!-- Gestione film -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="films.php" class="text-decoration-none h-100 d-block">
                    <div class="card border-0 shadow-sm text-center p-4 h-100 card-hover">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="display-4 mb-3 text-warning">
                                <i class="bi bi-film"></i>
                            </div>
                            <h2 class="h4 fw-bold mb-2 text-dark">Archivio Film</h2>
                            <p class="text-muted mb-0 small">Visualizza i film presenti nel database</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Gestione utenti -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="users.php" class="text-decoration-none h-100 d-block">
                    <div class="card border-0 shadow-sm text-center p-4 h-100 card-hover">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="display-4 mb-3 text-success">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h2 class="h4 fw-bold mb-2 text-dark">Gestione Utenti</h2>
                            <p class="text-muted mb-0 small">Visualizza e gestisci gli utenti</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Gestione sessioni -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="sessions.php" class="text-decoration-none h-100 d-block">
                    <div class="card border-0 shadow-sm text-center p-4 h-100 card-hover">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="display-4 mb-3 text-primary">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <h2 class="h4 fw-bold mb-2 text-dark">Gestione Sessioni</h2>
                            <p class="text-muted mb-0 small">Visualizza le sessioni</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Gestione notifiche -->
            <div class="col-12 col-md-6 col-lg-3">
                <a href="notifications.php" class="text-decoration-none h-100 d-block">
                    <div class="card border-0 shadow-sm text-center p-4 h-100 card-hover">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="display-4 mb-3 text-danger">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                            <h2 class="h4 fw-bold mb-2 text-dark">Gestione Notifiche</h2>
                            <p class="text-muted mb-0 small">Visualizza i report inviati dagli utenti</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/admin/notifications.php
```php
<?php
/**
 * Gestione notifiche (area admin). Mostra tutte le notifiche inviate dagli utenti
 * tramite la pagina di contatto, divise in "non lette" e "lette". Permette di
 * segnare una notifica come letta (POST con id_notifica) e di eliminare in blocco
 * tutte le notifiche già lette (POST con campo delete).
 *
 * @note Interagisce con le tabelle MariaDB: `notifiche`, `utenti` (LEFT JOIN).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}


// Aggiornare notifica letta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id_notifica'] ?? 0);

    if($id > 0) {
        try {
            $sql = "UPDATE notifiche SET letta = 1 WHERE id_notifica = :id_n";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id_n' => $id]);

        } catch (PDOException $e) {
            error_log("Errore: " . $e->getMessage());
        }
    }
}


$notifiche = "";

try {
    $sql = "SELECT * 
            FROM notifiche n
            LEFT JOIN utenti u ON n.id_utente = u.id_utente
            ORDER BY n.data_invio DESC";
             
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $notifiche = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Errore: " . $e->getMessage());
}


if (isset($_POST['delete'])) {
    try {
        $sql = "DELETE FROM notifiche WHERE letta = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        header("Location: /pages/admin/notifications.php");
        exit();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifiche - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 mb-5 pb-5 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fs-4 fw-bold mb-0">Gestione Notifiche</h1>
        </div>

        <?php
        $lette = [];
        $nonLette = [];

        foreach ($notifiche as $n) {
            if ($n['letta']) 
                $lette[] = $n;
            else 
                $nonLette[] = $n;        
        }
        ?>

        <?php if (empty($notifiche)): ?>
            <div class="text-center text-muted py-5">
                <p class="mb-0">Nessuna notifica disponibile</p>
            </div>
        <?php else: ?>

            <h6 class="text-uppercase text-muted fw-semibold small mb-3">Non lette</h6>
            <?php if (empty($nonLette)): ?>
                <p class="text-muted small mb-4">Nessuna notifica da leggere</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-3 mb-5">
                    <?php foreach ($nonLette as $notifica): ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body px-4 py-3">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div class="d-flex flex-column gap-1 flex-grow-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-semibold"><?= htmlspecialchars($notifica['titolo'] ?? '—') ?></span>
                                            <span class="text-muted small">·</span>
                                            <span class="text-muted small"><?= htmlspecialchars($notifica['username'] ?? 'Sconosciuto') ?></span>
                                        </div>
                                        <p class="mb-0 text-muted small"><?= nl2br(htmlspecialchars($notifica['descrizione'] ?? '')) ?></p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-1 text-nowrap">
                                        <span class="badge bg-light text-muted fw-normal border small">
                                            <?= htmlspecialchars($notifica['data_invio'] ?? '') ?>
                                        </span>
                                        <form method="POST">
                                            <input type="hidden" name="id_notifica" value="<?= $notifica['id_notifica'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Segna come letta">&#10003;</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h6 class="text-uppercase text-muted fw-semibold small mb-3">Lette</h6>

            <form method="POST" class="mb-3">
                <button type="submit" name="delete" class="btn btn-outline-danger btn-sm px-3 d-flex align-items-center gap-2">
                    <i class="bi bi-trash3"></i>
                </button>
            </form>

            <?php if (empty($lette)): ?>
                <p class="text-muted small">Nessuna notifica letta</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($lette as $notifica): ?>
                        <div class="card border-0 shadow-sm opacity-50">
                            <div class="card-body px-4 py-3">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div class="d-flex flex-column gap-1 flex-grow-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-semibold text-muted"><?= htmlspecialchars($notifica['titolo'] ?? '—') ?></span>
                                            <span class="text-muted small">·</span>
                                            <span class="text-muted small"><?= htmlspecialchars($notifica['username'] ?? 'Sconosciuto')?></span>
                                        </div>
                                        <p class="mb-0 text-muted small"><?= nl2br(htmlspecialchars($notifica['descrizione'] ?? '')) ?></p>
                                    </div>
                                    <span class="badge bg-light text-muted fw-normal border small mt-1 text-nowrap">
                                        <?= htmlspecialchars($notifica['data_invio'] ?? '') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/user/review.php
```php
<?php
/**
 * Pagina di scrittura/modifica recensione (riservata agli utenti autenticati).
 * Riceve il TMDB ID del film tramite GET e carica l'eventuale recensione
 * già scritta dall'utente. Gestisce tre azioni POST:
 * - write_review: inserisce o aggiorna la recensione e segna automaticamente
 *   il film come "watched" se non lo era già.
 * - delete_review: elimina la recensione e reindirizza alla pagina del film.
 * Il form mostra il titolo dinamico "Scrivi" o "Modifica" in base allo stato.
 *
 * @note Interagisce con le tabelle MariaDB: `recensioni`, `watched`.
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');

$id_utente = $_SESSION['id_utente'] ?? null;
$username = $_SESSION['username']  ?? null;

// Autenticazione
if (!$id_utente) {
    header("Location: /index.php");
    exit();
}

$tmdb_id = $_GET['tmdb_id'] ?? null;

if (!$tmdb_id) {
    header("Location: /index.php");
    exit();
}

$errore = '';
$messaggio = '';
$recensione_esistente = null;


// Recupera la recensione esistente (se c'è)
try {
    $sql = "SELECT * FROM recensioni WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id_utente' => $id_utente, 
        ':tmdb_id' => $tmdb_id
    ]);

    $recensione_esistente = $stmt->fetch();
    
} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}


// Gestione POST
if (isset($_POST['write_review'])) {
    $voto = $_POST['rating'] ?? null;
    $commento = $_POST['commento'] ?? null;

    if (!$voto || !$commento) {
        $errore = "Compila tutti i campi";

    } elseif ($voto < 1 || $voto > 10) {
        $errore = "Il voto deve essere compreso tra 1 e 10";

    } else {
        try {
            if ($recensione_esistente) {
                // Aggiorna recensione esistente
                $sql = "UPDATE recensioni SET voto = :voto, commento = :commento, data_aggiunto = :data_aggiunto
                        WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

            } else {
                // Inserisce nuova recensione
                $sql = "INSERT INTO recensioni (tmdb_id, id_utente, data_aggiunto, commento, voto)
                        VALUES (:tmdb_id, :id_utente, :data_aggiunto, :commento, :voto)";
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':tmdb_id' => $tmdb_id,
                ':id_utente' => $id_utente,
                ':data_aggiunto' => date('Y-m-d H:i:s'),
                ':commento' => trim($commento),
                ':voto' => (float)$voto
            ]);

            $messaggio = $recensione_esistente
                ? "Recensione aggiornata"
                : "Recensione pubblicata";

            // Segna automaticamente come visto (watched)
            $sql_check_watched = "SELECT 1 FROM watched WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";
            $stmt_check_watched = $conn->prepare($sql_check_watched);
            $stmt_check_watched->execute([
                ':id_utente' => $id_utente,
                ':tmdb_id' => $tmdb_id
            ]);

            if (!$stmt_check_watched->fetch()) {
                $sql_insert_watched = "INSERT INTO watched (tmdb_id, id_utente, data_aggiunto) 
                                     VALUES (:tmdb_id, :id_utente, :data_aggiunto)";
                $stmt_insert_watched = $conn->prepare($sql_insert_watched);
                $stmt_insert_watched->execute([
                    ':tmdb_id' => $tmdb_id,
                    ':id_utente' => $id_utente,
                    ':data_aggiunto' => date('Y-m-d H:i:s')
                ]);
            }

            // Inizializza per pulire la pagina dopo l'invio della recensione
            $recensione_esistente = [];

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nel salvataggio della recensione";
        }
    }
}

// Eliminare film
if (isset($_POST['delete_review'])) {
    try {
        $sql = "DELETE FROM recensioni WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_utente' => $id_utente, 
            ':tmdb_id' => $tmdb_id
        ]);

        header("Location: /pages/public/film.php?tmdb_id=" . urlencode($tmdb_id));
        exit();

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
        $errore = "Errore nella cancellazione della recensione";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $recensione_esistente ? 'Modifica recensione' : 'Scrivi recensione' ?> - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 px-4">

                <a href="/pages/public/film.php?tmdb_id=<?= urldecode($tmdb_id) ?>" 
                    class="btn-close position-absolute top-0 start-0 m-4" 
                    aria-label="Close">
                </a>

                <div class="text-center mb-4">
                    <h1 class="display-6 fw-bolder mb-2">
                        <?= $recensione_esistente ? 'Modifica recensione' : 'Scrivi recensione' ?>
                    </h1>
                    <p class="text-secondary mb-3">
                        <?= $recensione_esistente
                            ? 'Aggiorna la tua opinione'
                            : 'Condividi la tua opinione' ?>
                    </p>
                    
                    <div class="d-inline-flex align-items-center text-muted opacity-75" style="font-size: 0.85rem;">
                        <i class="bi bi-info-circle me-2"></i>
                        <span>Il film verrà aggiunto automaticamente alla tua <strong>watched</strong></span>
                    </div>
                </div>

                <?php if ($errore): ?>
                    <div class="alert alert-danger border-0 small py-2 mb-4 text-center">
                        <?= htmlspecialchars($errore) ?>
                    </div>
                <?php endif; ?>

                <?php if ($messaggio): ?>
                    <div class="alert alert-success border-0 small py-2 mb-4 text-center">
                        <?= htmlspecialchars($messaggio) ?>
                        <a href="/pages/user/reviews.php" class="fw-bold text-decoration-none">vedi</a>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="tmdb_id" value="<?= htmlspecialchars($tmdb_id) ?>">

                    <div class="mb-4">
                        <label class="form-label small text-secondary">Voto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light">
                                <i class="bi bi-star-fill text-warning"></i>
                            </span>
                            <input
                                type="number"
                                name="rating"
                                step="0.1"
                                id="rating"
                                class="form-control bg-light border-light py-3"
                                min="1"
                                max="10"
                                placeholder="Da 1 a 10"
                                value="<?= htmlspecialchars($recensione_esistente['voto'] ?? '') ?>"
                                required>
                            <span class="input-group-text bg-light border-light text-secondary">/ 10</span>
                        </div>
                    </div>

                    <hr class="my-3 opacity-25">

                    <div class="mb-4">
                        <label class="form-label small text-secondary">Commento</label>
                        <textarea
                            name="commento"
                            id="commento"
                            class="form-control bg-light border-light"
                            rows="6"
                            maxlength="200"
                            placeholder="Scrivi qui la tua recensione..."
                            required><?= htmlspecialchars($recensione_esistente['commento'] ?? '') ?></textarea>
                            <div class="form-text text-end">
                                Limite massimo: 200 caratteri
                            </div>
                    </div>

                    <button type="submit" name="write_review" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-3">
                        <?= $recensione_esistente ? 'Aggiorna recensione' : 'Pubblica recensione' ?>
                    </button>

                    <?php if ($recensione_esistente): ?>
                        <button type="submit" onclick="closeAndRedirect()" name="delete_review" class="btn btn-outline-danger btn-lg w-100 py-3 fw-bold">
                            Elimina recensione
                        </button>
                    <?php endif; ?>

                </form>
            </div>
        </div>
    </div>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: actions/logout.php
```php
<?php
/**
 * Gestisce il processo di logout: registra la data/ora di uscita nel DB
 * e invalida la sessione.
 * Al termine reindirizza l'utente alla home con il parametro ?logout=success.
 *
 * @note Interagisce con la tabella MariaDB: `sessioni` (tramite userObj::setDataLogout).
 */
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/connection.php');
require_once(__DIR__ . '/../includes/user_obj.php');

// Controllo accesso
if (!isset($_SESSION['username'])) {
    header("Location: /index.php");
    exit();
}

try {
    $id_sessione = session_id();
    $username = $_SESSION['username'];

    $user = new userObj($conn, $username);
    $user->setDataLogout(date('Y-m-d H:i:s'), $id_sessione);

} catch (Exception $e) {
    error_log("Errore durante il logout: " . $e->getMessage());

} finally {
    // Cancella completamente la sessione e reindirizza l'utente alla home con il flag di logout avvenuto
    // Viene chiamata nel blocco `finally` per garantire l'esecuzione in ogni caso
    session_unset();                                
    session_destroy();                              
    header("Location: /index.php?logout=success");
    exit();
}
```

## File: includes/footer.php
```php
<?php
// Session_start() deve essere chiamato all'inizio di ogni files
$currentPage = basename($_SERVER['SCRIPT_NAME']);

$adminPages = ['add_film.php', 'dashboard.php', 'sessions.php', 'users.php', 'edit_user.php', 'notifications.php', 'films.php', 'film_db.php'];

$isAdminPage = in_array($currentPage, $adminPages);
?>
<footer class="text-center py-4 border-top">
    <div class="mb-2">
        <span class="text-secondary small">
            © <?= date("Y"); ?> <span class="fw-bold text-dark">Cinevobis</span>
        </span>
    </div>
    
    <div class="d-flex justify-content-center gap-3">
        <a href="/pages/public/terms.php" class="text-secondary small text-decoration-none">Termini di servizio</a>
        <a href="/pages/public/privacy.php" class="text-secondary small text-decoration-none">Informativa sulla privacy</a>
        <a href="/actions/contact.php" class="text-secondary small text-decoration-none">Contattaci</a>
    </div>
</footer>
```

## File: pages/admin/films.php
```php
<?php
/**
 * Archivio film (area admin, riservata al profilo id_profilo = 1).
 * Mostra la lista completa dei film presenti in MongoDB ordinati per data
 * di ultimo aggiornamento. Permette l'eliminazione di un singolo documento
 * tramite il suo ObjectId MongoDB ricevuto via POST.
 *
 * @note Interagisce con la collezione MongoDB: `films` (find, deleteOne).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}


$mongoClient = null;
$db = null;
$collection = [];

// Connessione a MongoDB
try {
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

} catch (PDOException $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}


$cursor = [];

// Recuperiamo i film (ordinati per data di aggiunta)
try {
    $cursor = $collection->find([], [
        'sort' => ['last_updated' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
        ]); 

} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}


// Eliminazione film
if (isset($_POST['delete'])) {
    $id = $_POST['_id'] ?? '';

    try {
        $objectId = new MongoDB\BSON\ObjectId($id);
        $collection->deleteOne(['_id' => $objectId]);

        header("Location: films.php");
        exit();

    } catch (Exception $e) {
        error_log("Errore MongoDB: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Film - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* Bootstrap non ha row-cols-xl-10 di default, lo aggiungiamo */
        @media (min-width: 1200px) {
            .row-cols-xl-10 > * {
                flex: 0 0 auto;
                width: 10%;
            }
        }
        @media (min-width: 992px) {
            .row-cols-lg-8 > * {
                flex: 0 0 auto;
                width: 12.5%;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fs-4 fw-bold mb-0">Archivio Film</h1>
        </div>

        <?php if (!empty($cursor)): ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 row-cols-lg-8 row-cols-xl-10 g-2">
                <?php foreach($cursor as $movie): 
                    $titolo = $movie['title'] ?? 'Senza titolo';
                    $anno = !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '';
                    $poster = !empty($movie['poster_path'])
                        ? "https://image.tmdb.org/t/p/w185" . $movie['poster_path']
                        : null;
                ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden transition-hover">
                        <a href="/pages/admin/film_db.php?tmdb_id=<?= urlencode($movie['id']) ?>" class="text-decoration-none text-dark d-block">
                            <?php if ($poster): ?>
                                <img src="<?= $poster ?>" 
                                     alt="<?= htmlspecialchars($titolo) ?>" 
                                     class="card-img-top w-100"
                                     style="object-fit: cover; aspect-ratio: 2/3;">
                            <?php else: ?>
                                <div class="bg-secondary d-flex align-items-center justify-content-center w-100" style="aspect-ratio: 2/3;">
                                    <i class="bi bi-film text-white fs-4"></i>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="card-body p-1">
                            <h6 class="card-title mb-1 text-truncate" style="font-size: 0.75rem;" title="<?= htmlspecialchars($titolo) ?>">
                                <?= htmlspecialchars($titolo) ?>
                            </h6>
                            
                            <form method="POST" class="mt-0">
                                <input type="hidden" name="_id" value="<?= (string)$movie['_id'] ?>">
                                <button type="submit" name="delete"
                                        class="btn btn-link p-0 text-danger"
                                        style="font-size: 0.8rem;"
                                        title="Elimina">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center rounded-3 border-0 shadow-sm">
                <i class="bi bi-info-circle me-2"></i> Nessun film trovato nel database
            </div>
        <?php endif; ?>

    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/user/profile.php
```php
<?php
/**
 * Pagina del profilo utente (riservata agli utenti autenticati).
 * Mostra le informazioni anagrafiche dell'account, l'anno di registrazione
 * e il numero di film visti nell'anno corrente. Gestisce due azioni POST:
 * - change_password: reindirizza alla pagina di cambio password.
 * - delete_user: elimina l'account tramite userObj::delete, distrugge
 *   la sessione e reindirizza alla home.
 *
 * @note Interagisce con le tabelle MariaDB: `utenti` (tramite userObj), `watched`.
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');

$username = $_SESSION['username'] ?? '';

// Se non c'è l'utente in sessione
if (!$username) {
    header("Location: /index.php");
    exit();
}

$userData = null;
$dataRegistrazione = "N/D";
$user = new userObj($conn, $username);

// Recuperiamo i dati utente
$userData = $user->findByUsername();
if ($userData && $userData['data_registrazione']) {
    $date = new DateTime($userData['data_registrazione']);
    $dataRegistrazione = $date->format('Y');
}

// Gestione Cambia Password
if (isset($_POST['change_password'])) {
    header("Location: /actions/change_password.php");
    exit();
}

// Gestione Eliminazione Account (Porta subito alla Home)
if (isset($_POST['delete_user']) && $username) {
    try {
        if ($user->delete()) {
            // Distruggiamo la sessione per sicurezza prima del redirect
            session_destroy();
            header("Location: /index.php");
            exit();
        }
    } catch (PDOException $e) {
        $errore = "Errore durante l'eliminazione: " . $e->getMessage();
    }
}

// Film visti nell'anno corrente
$numeroFilmVisti = 0;
try {
    $sql = "SELECT COUNT(*) FROM watched WHERE id_utente = :id_utente AND YEAR(data_aggiunto) = YEAR(CURRENT_DATE)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_utente' => $_SESSION['id_utente']]);

    $numeroFilmVisti = $stmt->fetchColumn();

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 vh-100">
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center position-relative px-4">
                
                <a href="javascript:void(0)"
                   onclick="closeAndRedirect()"
                   class="btn-close position-absolute top-0 start-0 m-4"
                   aria-label="Close">
                </a>

                <div style="max-width: 500px; width: 100%;">
                    <h1 class="display-6 fw-bolder mb-2">Profilo</h1>
                    <p class="text-secondary mb-4">Informazioni account Cinevobis</p>

                    <?php if (isset($errore)): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <?= htmlspecialchars($errore) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($userData): ?>
                        <div class="row mb-3 pb-2 border-bottom">
                            <div class="col-md-6">
                                <label class="small text-uppercase text-muted fw-bold">Username</label>
                                <p class="fs-6 mb-0 text-break"><?= htmlspecialchars($userData['username']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-uppercase text-muted fw-bold">Email</label>
                                <p class="fs-6 mb-0 text-break"><?= htmlspecialchars($userData['email']) ?></p>
                            </div>
                        </div>

                        <div class="row mb-3 pb-2 border-bottom">
                            <div class="col-md-6">
                                <label class="small text-uppercase text-muted fw-bold">Nome</label>
                                <p class="fs-6 mb-0 text-break"><?= htmlspecialchars($userData['nome'] ?? 'Non specificato') ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-uppercase text-muted fw-bold">Cognome</label>
                                <p class="fs-6 mb-0 text-break"><?= htmlspecialchars($userData['cognome'] ?? 'Non specificato') ?></p>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-12">
                                <label class="small text-uppercase text-muted fw-bold">Membro dal</label>
                                <p class="fs-6 mb-0 text-break"><?= htmlspecialchars($dataRegistrazione); ?></p>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-12">
                                <label class="small text-uppercase text-muted fw-bold">Film visti nel <?php echo date('Y'); ?></label>
                                <p class="fs-6 mb-0 text-break"><?= htmlspecialchars($numeroFilmVisti); ?></p>
                            </div>
                        </div>
                        
                        <form method="POST">
                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" name="change_password" class="btn btn-dark btn-lg flex-fill py-3 fw-bold">
                                    Cambia password
                                </button>
                            </div>
                            
                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" name="delete_user" class="btn btn-outline-danger btn-lg flex-fill py-3 fw-bold"
                                        onclick="return confirm('Sei sicuro? Questa azione è irreversibile.');">
                                    Elimina account
                                </button>
                            </div>
                        </form>
                            
                    <?php else: ?>
                        <div class="alert alert-warning text-center">Utente non trovato.</div>
                        <div class="d-grid">
                            <a href="/index.php" class="btn btn-dark">Torna alla Home</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block bg-secondary" 
                 style="background-image: url('/assets/img/astronaut.jpeg'); background-size: cover; background-position: center;">
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: assets/js/script.js
```javascript
document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. SALVATAGGIO PROVENIENZA (Login/Signup/Profile) ---
    const paginaAttuale = window.location.pathname;
    const provenienza = document.referrer;
    
    // Raggruppiamo le pagine che condividono questa logica
    const pagineTracciate = ['login.php', 'signup.php', 'change_password.php', 'profile.php', 'contact.php'];

    // Controlliamo se siamo in una di queste pagine
    const isPaginaTracciata = pagineTracciate.some(pagina => paginaAttuale.includes(pagina));

    if (isPaginaTracciata) {
        // Controlliamo se arriviamo da una delle altre pagine tracciate
        const arrivoDaPaginaInterna = pagineTracciate.some(pagina => provenienza.includes(pagina));

        // Sovrascriviamo l'URL di origine SOLO se arriviamo da una pagina esterna a questo gruppo
        if (!arrivoDaPaginaInterna) {
            sessionStorage.setItem('origin_url', provenienza !== "" ? provenienza : '/index.php');
        }
    }

    // --- 2. MOSTRA/NASCONDI PASSWORD ---
    const iconePassword = document.querySelectorAll('.toggle-icon');
    
    iconePassword.forEach(function(icona) {
        icona.addEventListener('click', function() {

            const inputId = this.getAttribute('data-target');
            const inputField = document.getElementById(inputId);

            if (inputField.type === 'password') {
                inputField.type = 'text';
                this.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                inputField.type = 'password';
                this.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });

    // --- 3. GESTIONE TRAILER (MODAL) ---
    const trailerModal = document.getElementById('trailerModal');
    const container = document.querySelector('#trailerModal .ratio'); 

    if (trailerModal && container) {
        const iframeOriginale = container.querySelector('iframe');
        const videoUrlBase = iframeOriginale.getAttribute('data-src');
        const classiIframe = iframeOriginale.className;

        // Creiamo la struttura dell'iframe una volta sola, SENZA autoplay
        const iframeHTML = `<iframe src="${videoUrlBase}" class="${classiIframe}" allowfullscreen></iframe>`;

        // Svuotiamo il contenitore all'inizio
        container.innerHTML = '';

        // Quando la modale si apre: inseriamo l'iframe pulito
        trailerModal.addEventListener('show.bs.modal', function() {
            container.innerHTML = iframeHTML;
        });

        // Quando la modale si chiude: distruggiamo l'iframe per fermare l'audio/video
        trailerModal.addEventListener('hidden.bs.modal', function() {
            container.innerHTML = '';
        });
    }
});

// --- 4. FUNZIONE PER TORNARE INDIETRO ---
function closeAndRedirect() {
    const destinazione = sessionStorage.getItem('origin_url');
    sessionStorage.removeItem('origin_url');
    window.location.href = destinazione || '/index.php';
}
```

## File: pages/public/signup.php
```php
<?php
/**
 * Pagina di registrazione. Raccoglie nome, cognome, email, username e password
 * dell'utente, crea un nuovo account tramite userObj::create con ruolo utente
 * (id_profilo = 2) e stato attivo. In caso di username duplicato, mostra
 * un messaggio di errore. Gli utenti già autenticati vengono reindirizzati alla home.
 *
 * @note Interagisce con la tabella MariaDB: `utenti` (tramite userObj::create).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');

// Controllo utente
if (isset($_SESSION['username'])) {
    header("Location: /index.php");
    exit();
}

$errore = ""; 
$messaggio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nome = trim($_POST['nome']);
    $cognome = trim($_POST['cognome']);
    $email = trim($_POST['email']);
    $attivo = 1;                            // Default: account attivo
    $id_profilo = 2;                        // Default: ruolo utente
    
    try {  
        $user = new userObj($conn, $username, $password, $nome, $cognome, $email, $attivo, $id_profilo);
        $user->create();
        $messaggio = "Account creato con successo";
    } catch (PDOException $e) { 
        $errore = "Username non disponibile";
        error_log("Username non disponibile: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .ts-wrapper .ts-control {
            min-height: calc(1.5em + 1rem + 2px) !important;
            padding: 1rem 0.75rem !important;
            background-color: #f8f9fa !important;
            border-color: #f8f9fa !important;
            border-radius: 0.375rem !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            box-shadow: none !important;
        }
        .ts-wrapper .ts-control .item { line-height: 1.5 !important; }
        .ts-wrapper .ts-control .dropdown-indicator { padding-top: 0.25rem !important; }
        .ts-wrapper.focus .ts-control {
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.05) !important;
            border-color: #dee2e6 !important;
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 vh-100">
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center position-relative px-4 py-5 overflow-auto">

                <a href="javascript:void(0)" 
                    onclick="closeAndRedirect()" 
                    class="btn-close position-absolute top-0 start-0 m-4" 
                    aria-label="Close">
                </a>

                <div style="max-width: 450px; width: 100%;">
                    <h1 class="display-6 fw-bolder mb-2">Crea il tuo account</h1>
                    <p class="text-secondary mb-5">Unisciti alla community</p>

                    <?php if ($errore): ?>
                        <div class="alert alert-danger border-0 small py-2 mb-4"><?= htmlspecialchars($errore) ?></div>
                    <?php endif; ?>
                    <?php if ($messaggio): ?>
                        <div class="alert alert-success border-0 small py-2 mb-4"><?= htmlspecialchars($messaggio) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <input type="text" name="nome" class="form-control bg-light border-light py-3" placeholder="Nome" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="cognome" class="form-control bg-light border-light py-3" placeholder="Cognome" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="email" name="email" class="form-control bg-light border-light py-3" placeholder="Email" required>
                        </div>
                        
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control bg-light border-light py-3" placeholder="Username" required>
                        </div>
                        
                        <div class="mb-5 position-relative password-wrapper">
                            <input type="password" name="password" id="password" class="form-control bg-light border-light py-3" 
                                placeholder="Password" required>
                            <i class="bi bi-eye toggle-icon" data-target="password"></i>
                        </div>
                        
                        <button type="submit" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">Crea un account</button>
                    </form>

                    <p class="text-center small text-secondary">Hai un account? <a href="login.php" class="text-dark fw-bold text-decoration-none">Accedi</a></p>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block bg-secondary" 
                 style="background-image: url('/assets/img/interstellar.jpg'); background-size: cover; background-position: center;">
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/public/login.php
```php
<?php
/**
 * Pagina di login. Verifica le credenziali dell'utente tramite userObj::findByUsername
 * e password_verify. Se l'autenticazione va a buon fine, rigenera l'ID di sessione
 * per prevenire la Session Fixation e popola le variabili di sessione.
 * Registra anche la data/ora di accesso tramite userObj::createDataLogin.
 * Gli utenti già autenticati vengono reindirizzati alla home.
 *
 * @note Interagisce con la tabella MariaDB: `utenti`, `sessioni` (tramite userObj).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');

// Controllo utente
if (isset($_SESSION['username'])) {
    header("Location: /index.php");
    exit();
}

$errore = "";

if (isset($_POST['login'])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    try {
        $user   = new userObj($conn, $username, $password);
        $utente = $user->findByUsername();

        if ($utente && password_verify($password, $utente['password'])) {
            if ($utente['attivo'] != 0) {
                // Previene la Session Fixation rigenerando l'ID al cambio di privilegi (login)
                session_regenerate_id(true);

                $_SESSION['id_utente'] = $utente['id_utente'];
                $_SESSION['username']  = $utente['username'];
                $_SESSION['id_profilo'] = $utente['id_profilo'];
                $_SESSION['nome'] = $utente['nome'];

                $user->createDataLogin(date('Y-m-d H:i:s'), session_id(), $utente['id_utente']);

                header("Location: /index.php");
                exit();
            } else { 
                $errore = "Utente non attivo"; 
            }
            
        } else { 
            $errore = "Dati non validi"; 
        }

    } catch (PDOException $e) { 
        $errore = "Errore"; 
        error_log("Errore: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 vh-100">
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center position-relative px-4">

                <a href="javascript:void(0)" 
                    onclick="closeAndRedirect()" 
                    class="btn-close position-absolute top-0 start-0 m-4" 
                    aria-label="Close">
                </a>

                <div style="max-width: 450px; width: 100%;">
                    <h1 class="display-6 fw-bolder mb-2">Accedi</h1>
                    <p class="text-secondary mb-5">Usa il tuo username</p>

                    <?php if ($errore): ?>
                        <div class="alert alert-danger border-0 small py-2 mb-4"><?= htmlspecialchars($errore) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control bg-light border-light py-3" 
                                   placeholder="Username" required>
                        </div>
                        
                        <div class="mb-5 position-relative password-wrapper">
                            <input type="password" name="password" id="password" class="form-control bg-light border-light py-3" 
                                placeholder="Password" required>
                            <i class="bi bi-eye toggle-icon" data-target="password"></i>
                        </div>

                        <button type="submit" name="login" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">Accedi</button>
                    </form>

                    <p class="text-center small text-secondary">Non hai un account? <a href="signup.php" class="text-dark fw-bold text-decoration-none">Registrati</a></p>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block bg-secondary" 
                 style="background-image: url('/assets/img/breakingbad.jpeg'); background-size: cover; background-position: center;">
            </div>
        </div>
    </div>
    
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/user/watched.php
```php
<?php
/**
 * Pagina dei film visti ("Watched"), riservata agli utenti autenticati.
 * Recupera da MariaDB i TMDB ID dei film segnati come visti dall'utente,
 * poi interroga MongoDB per ottenere titolo, poster e anno. I risultati
 * sono ordinati per voto medio decrescente e presentati in griglia.
 *
 * @note Interagisce con la tabella MariaDB: `watched`.
 * @note Interagisce con la collezione MongoDB: `films` (query con operatore $in).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Controllo autenticazione
$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}


// Estrazione tmdb_id
$ids = [];
$id_utente = $_SESSION['id_utente'] ?? '';

try {
    $sql = "SELECT tmdb_id FROM watched WHERE id_utente = :id_u ORDER BY data_aggiunto DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_u' => $id_utente]);

    // Prende l'intera colonna e la mette dentro un array
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
    
} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
    $ids = [];
}


// Connessione a MongoDB e ricerca film
$films = [];
$numeroWatched = 0;

if (!empty($ids)) {

    // Conteggio film nel DB corretto in 'watched'
    try {
        $sql = "SELECT COUNT(*) FROM watched WHERE id_utente = :id_u";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_u' => $id_utente]);

        $numeroWatched = $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }


    // Connessione a MongoDB e ricerca film
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

        // Da puntatore ad array, si estraggono i dati da MongoDB come array
        $raw_films = iterator_to_array($cursor);

        // --- Riordinamento manuale ---
        $films_map = [];

        foreach ($raw_films as $f) {
            $films_map[$f['id']] = $f;
        }

        // Ricostruiamo la lista $films seguendo l'ordine esatto di $ids
        $films = [];

        foreach ($ids as $id) {
            if (isset($films_map[$id])) {
                $films[] = $films_map[$id];
            }
        }

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watched - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-4">Watched</h1>

        <?php 
            if ($numeroWatched > 0) {
                echo "<div class='mb-4'>";
                echo "<small class='text-uppercase fw-bold text-muted d-block mb-2' style='letter-spacing:1px'>Hai visto " . htmlspecialchars($numeroWatched) . " Film</small>";
                echo "</div>";
            }
        ?>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non hai ancora aggiunto film ai tuoi visti
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                
                <?php 
                /** @var array $film */
                foreach ($films as $film):
                    $id = $film['id'] ?? '';
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) 
                        ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] 
                        : "https://via.placeholder.com/500x750?text=No+Poster";
                    $anno = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
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
```

## File: pages/user/watchlist.php
```php
<?php
/**
 * Pagina della watchlist personale (riservata agli utenti autenticati).
 * Recupera da MariaDB i TMDB ID dei film che l'utente intende guardare,
 * poi interroga MongoDB con l'operatore $in per ottenere titolo, poster e
 * anno di ciascun film. I risultati sono ordinati per voto medio decrescente
 * e presentati in una griglia di card cliccabili.
 *
 * @note Interagisce con la tabella MariaDB: `watchlist`.
 * @note Interagisce con la collezione MongoDB: `films` (query con operatore $in).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Controllo autenticazione
$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}


// Estrazione tmdb_id
$ids = [];
$id_utente = $_SESSION['id_utente'] ?? '';

try {
    $sql = "SELECT tmdb_id FROM watchlist WHERE id_utente = :id_u ORDER BY data_aggiunto DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_u' => $id_utente]);

    // Prende l'intera colonna e la mette dentro un array
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
    
} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
    $ids = [];
}



$films = [];
$numeroWatchlist = 0;

if (!empty($ids)) {

    // Conteggio film nel DB
    try {
        $sql = "SELECT COUNT(*) FROM watchlist WHERE id_utente = :id_u";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_u' => $id_utente]);

        $numeroWatchlist = $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }


    // Connessione a MongoDB e ricerca film
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db = $mongoClient->selectDatabase("cinevobis");
        $collection = $db->selectCollection("films");

        $cursor = $collection->find(
            // $in seleziona i documenti in cui il valore di un campo corrisponde a uno qualsiasi dei valori presenti nell'array specificato
            ['id' => ['$in' => $ids]],
            [
                'sort' => ['vote_average' => -1],
                'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array'],
            ]
        );

        // Da puntatore ad array, si estraggono i dati da MongoDB come array
        $raw_films = iterator_to_array($cursor);

        // --- Riordinamento manuale ---
        $films_map = [];

        foreach ($raw_films as $f) {
            $films_map[$f['id']] = $f;
        }

        // Ricostruiamo la lista $films seguendo l'ordine esatto di $ids
        $films = [];

        foreach ($ids as $id) {
            if (isset($films_map[$id])) {
                $films[] = $films_map[$id];
            }
        }

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-4">Watchlist</h1>

        <?php 
        if ($numeroWatchlist > 0) {
            echo "<div class='mb-4'>";
            echo "<small class='text-uppercase fw-bold text-muted d-block mb-2' style='letter-spacing:1px'>Hai " . htmlspecialchars($numeroWatchlist) . " Film che vorresti vedere</small>";
            echo "</div>";
        }
        ?>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non hai ancora aggiunto film alla tua watchlist
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                
                <?php 
                /** @var array $film */
                foreach ($films as $film):
                    $id = $film['id'] ?? '';
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) 
                        ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] 
                        : "https://via.placeholder.com/500x750?text=No+Poster";
                    $anno = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
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
```

## File: pages/admin/edit_user.php
```php
<?php
/**
 * Pagina di modifica utente (area admin). Riceve lo username tramite GET,
 * carica i dati correnti dell'utente e permette all'admin di aggiornare
 * nome, cognome, email e stato attivo tramite userObj::update, oppure di
 * eliminare definitivamente l'account tramite userObj::delete.
 *
 * @note Interagisce con la tabella MariaDB: `utenti` (tramite userObj::update e userObj::delete).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

$username = $_GET['username'] ?? '';

if (!$username) {
    header("Location: admin_area.php");
    exit();
}

$errore = '';
$messaggio = '';

// Carichiamo i dati attuali dell'utente
$user = new userObj($conn, $username);
$utente = $user->findByUsername();

if (!$utente) {
    header("Location: admin_area.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) {
        $nome = trim($_POST['nome'] ?? '');
        $cognome = trim($_POST['cognome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $attivo = isset($_POST['attivo']) ? (int) $_POST['attivo'] : 0;

        if (!$nome || !$cognome || !$email) {
            $errore = "Nome, cognome ed email sono obbligatori";
        } else {
            try {
                $userUpdate = new userObj(
                    $conn,
                    $username,
                    null,
                    $nome,
                    $cognome,
                    $email,
                    $attivo,
                    $utente['id_profilo']
                );

                $userUpdate->update($username);
                $messaggio = "Utente aggiornato con successo";

                // Ricarichiamo i dati aggiornati
                $utente = $userUpdate->findByUsername();
            } catch (PDOException $e) {
                $errore = "Errore durante l'aggiornamento";
                error_log("Errore update utente: " . $e->getMessage());
            }
        }
    }

    if (isset($_POST['delete_user'])) {
        try {
            $user->delete();
            header("Location: users.php?msg=eliminato");
            exit();
        } catch (PDOException $e) {
            $errore = "Errore durante l'eliminazione";
            error_log("Errore delete utente: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica utente - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
        <div style="max-width: 450px; width: 100%;">

            <a href="users.php"
                class="btn-close position-absolute top-0 start-0 m-4"
                aria-label="Chiudi">
            </a>

            <h1 class="display-6 fw-bolder mb-2 text-center">Modifica utente</h1>
            <p class="text-secondary mb-5 text-center">Aggiorna i dati dell'account</p>

            <?php if ($errore): ?>
                <div class="alert alert-danger border-0 small py-2 mb-4">
                    <?= htmlspecialchars($errore) ?>
                </div>
            <?php endif; ?>

            <?php if ($messaggio): ?>
                <div class="alert alert-success border-0 small py-2 mb-4">
                    <?= htmlspecialchars($messaggio) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <input type="text"
                            name="nome"
                            class="form-control bg-light border-light py-3"
                            placeholder="Nome"
                            value="<?= htmlspecialchars($utente['nome'] ?? '') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <input type="text"
                            name="cognome"
                            class="form-control bg-light border-light py-3"
                            placeholder="Cognome"
                            value="<?= htmlspecialchars($utente['cognome'] ?? '') ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <input type="email"
                        name="email"
                        class="form-control bg-light border-light py-3"
                        placeholder="Email"
                        value="<?= htmlspecialchars($utente['email'] ?? '') ?>"
                        required>
                </div>

                <div class="mb-3">
                    <input type="text"
                        class="form-control bg-light border-light py-3 text-muted"
                        value="<?= htmlspecialchars($utente['username'] ?? '') ?>"
                        disabled 
                        style="cursor: not-allowed;">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">Attivo</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="attivo" value="1"
                                <?= (int)($utente['attivo'] ?? 0) === 1 ? 'checked' : '' ?>>
                            <label class="form-check-label">Sì</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="attivo" value="0"
                                <?= (int)($utente['attivo'] ?? 0) === 0 ? 'checked' : '' ?>>
                            <label class="form-check-label">No</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit"
                            name="save"
                            class="btn btn-dark btn-lg flex-fill py-3 fw-bold">
                        Salva modifiche
                    </button>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit"
                            name="delete_user"
                            class="btn btn-outline-danger btn-lg flex-fill py-3 fw-bold"
                            onclick="return confirm('Sei sicuro?');">
                        Elimina utente
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
```

## File: pages/user/favorites.php
```php
<?php
/**
 * Pagina dei film preferiti (riservata agli utenti autenticati).
 * Recupera da MariaDB i TMDB ID dei film preferiti dell'utente loggato,
 * poi interroga MongoDB per ottenere titolo, poster e anno di ciascun film.
 * I risultati vengono ordinati per voto medio decrescente e presentati
 * in una griglia di card cliccabili.
 *
 * @note Interagisce con la tabella MariaDB: `preferiti`.
 * @note Interagisce con la collezione MongoDB: `films` (query con operatore $in).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Controllo autenticazione
$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}


// Estrazione tmdb_id
$ids = [];
$id_utente = $_SESSION['id_utente'] ?? '';

try {
    $sql = "SELECT tmdb_id FROM preferiti WHERE id_utente = :id_u ORDER BY data_aggiunto DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_u' => $id_utente]);

    // Prende l'intera colonna e la mette dentro un array
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
    
} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
    $ids = [];
}


// Connessione a MongoDB e ricerca film
$films = [];
$numeroPreferiti = 0;

if (!empty($ids)) {

    // Conteggio preferiti
    try {
        $sql = "SELECT COUNT(*) FROM preferiti WHERE id_utente = :id_u";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_u' => $id_utente]);

        $numeroPreferiti = $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }


    // Connessione a MongoDB e ricerca film
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

        // Da puntatore ad array, si estraggono i dati da MongoDB come array
        $raw_films = iterator_to_array($cursor);

        // --- Riordinamento manuale ---
        $films_map = [];

        foreach ($raw_films as $f) {
            $films_map[$f['id']] = $f;
        }

        // Ricostruiamo la lista $films seguendo l'ordine esatto di $ids
        $films = [];

        foreach ($ids as $id) {
            if (isset($films_map[$id])) {
                $films[] = $films_map[$id];
            }
        }

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preferiti - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-4">Preferiti</h1>

        <?php 
            if ($numeroPreferiti > 0) {
                echo "<div class='mb-4'>";
                echo "<small class='text-uppercase fw-bold text-muted d-block mb-2' style='letter-spacing:1px'>Hai " . htmlspecialchars($numeroPreferiti) . " Film come preferiti</small>";
                echo "</div>";
            }
        ?>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non hai ancora aggiunto film ai tuoi preferiti
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                
                <?php 
                /** @var array $film */
                foreach ($films as $film):
                    $id = $film['id'] ?? '';
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) 
                        ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] 
                        : "https://via.placeholder.com/500x750?text=No+Poster";
                    $anno = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
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
```

## File: pages/user/reviews.php
```php
<?php
/**
 * Pagina delle recensioni personali (riservata agli utenti autenticati).
 * Recupera da MariaDB i TMDB ID e i dati (voto, commento) di tutti i film
 * recensiti dall'utente loggato, poi interroga MongoDB per ottenere poster
 * e titolo di ciascun film. I risultati vengono presentati come card con
 * il commento e il voto dell'utente.
 *
 * @note Interagisce con la tabella MariaDB: `recensioni`.
 * @note Interagisce con la collezione MongoDB: `films` (query con operatore $in).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Controllo autenticazione
$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}

// Estrazione tmdb_id + dati recensione
$recensioni_map = [];
$ids = [];
$id_utente = $_SESSION['id_utente'] ?? '';

try {
    $sql = "SELECT tmdb_id, commento, voto 
            FROM recensioni 
            WHERE id_utente = :id_u 
            ORDER BY voto DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_u' => $id_utente]);

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $ids[] = (int) $row['tmdb_id'];

        $recensioni_map[(int) $row['tmdb_id']] = [
            'voto' => $row['voto'],
            'commento' => $row['commento'],
        ];
    }

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}

// Connessione a MongoDB e ricerca film
$films = [];
$numeroRecensioni = 0;

if (!empty($ids)) {

    // Conteggio recensioni
    try {
        $sql = "SELECT COUNT(*) 
                FROM recensioni 
                WHERE id_utente = :id_u";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_u' => $id_utente]);

        $numeroRecensioni = $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }


    // Connessione a MongoDB
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

        // Da puntatore ad array, si estraggono i dati da MongoDB come array
        $raw_films = iterator_to_array($cursor);

        // --- Riordinamento manuale ---
        $films_map = [];

        foreach ($raw_films as $f) {
            $films_map[$f['id']] = $f;
        }

        // Ricostruiamo la lista $films seguendo l'ordine esatto di $ids
        $films = [];

        foreach ($ids as $id) {
            if (isset($films_map[$id])) {
                $films[] = $films_map[$id];
            }
        }

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recensioni - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root { --accent-color: #ffc107; }

        .text-justify { text-align: justify; }

        .cast-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        .review-poster {
            width: 120px;
            min-width: 120px;
            aspect-ratio: 2/3;
            object-fit: cover;
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .star-rating {
            color: #ccc;
            font-size: 1.1rem;
            letter-spacing: 2px;
        }

        .star-rating .filled {
            color: var(--accent-color);
        }

        .vote-badge {
            background-color: var(--accent-color);
            color: #1a1a1a;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 0.4rem;
            padding: 2px 10px;
            display: inline-block;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-4">Recensioni</h1>

        <?php 
        if ($numeroRecensioni > 0) {
            echo "<div class='mb-4'>";
            echo "<small class='text-uppercase fw-bold text-muted d-block mb-2' style='letter-spacing:1px'>Hai recensito " . htmlspecialchars($numeroRecensioni) . " Film</small>";
            echo "</div>";
        }
        ?>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non hai ancora recensito nessun film
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-3">
                <?php
                /** @var array $film */
                foreach ($films as $film):
                    $id = (int) ($film['id'] ?? 0);
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path'])
                        ? "https://image.tmdb.org/t/p/w500" . $film['poster_path']
                        : "https://via.placeholder.com/500x750?text=No+Poster";
                    $rec = $recensioni_map[$id] ?? [];
                    $voto = isset($rec['voto']) ? (float) $rec['voto'] : null;
                    $commento = $rec['commento'] ?? '';
                ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover position-relative">
                        <div class="d-flex">

                            <img src="<?= htmlspecialchars($poster) ?>"
                                alt="<?= htmlspecialchars($titolo) ?>"
                                class="review-poster">

                            <div class="card-body d-flex flex-column justify-content-between p-3">
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark stretched-link">
                                            <?= htmlspecialchars($titolo) ?>
                                        </a>
                                    </h5>

                                    <?php if (!empty($commento)): ?>
                                        <p class="text-muted small mb-2 text-justify">
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
```

## File: pages/admin/users.php
```php
<?php
/**
 * Pagina di gestione utenti (area admin). Recupera e mostra in tabella
 * tutti gli utenti registrati con username, nome, cognome, email, ruolo
 * e stato attivo/inattivo. Ogni riga include un link alla pagina di modifica
 * individuale (edit_user.php). Riservata agli utenti con id_profilo = 1.
 *
 * @note Interagisce con le tabelle MariaDB: `utenti`, `profili` (tramite userObj::readAll).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

// Controllo autenticazione
$username   = $_SESSION['username'] ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}

$user = new userObj($conn, $username);
$utenti = $user->readAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione utenti - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 mb-5 pb-5 flex-grow-1">
        <h1 class="fs-4 fw-bold mb-4">Gestione Utenti</h1>
        
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">Username</th>
                            <th>Identità</th>
                            <th>Email</th>
                            <th>Profilo</th>
                            <th class="text-center">Attivo</th>
                            <th class="text-end pe-3">Azione</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utenti as $utente): ?>
                            <tr>
                                <td class="ps-3 fw-bold"><?= htmlspecialchars($utente['username']) ?></td>
                                <td>
                                    <div class="small fw-semibold"><?= htmlspecialchars($utente['nome'] ?? '') ?> <?= htmlspecialchars($utente['cognome'] ?? '') ?></div>
                                </td>
                                <td class="small"><?= htmlspecialchars($utente['email'] ?? '') ?></td>
                                <td><span><?= htmlspecialchars($utente['nome_profilo'] ?? '') ?></span></td>
                                <td class="text-center">
                                    <?php if ($utente['attivo']): ?>
                                        <span class="text-success" title="Attivo">True</span>
                                    <?php else: ?>
                                        <span class="text-danger" title="Inattivo">False</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <form method="GET" action="edit_user.php">
                                        <a href="edit_user.php?username=<?= htmlspecialchars($utente['username']) ?>" 
                                            class="text-dark text-decoration-none d-inline-flex align-items-center">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Modifica
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/public/search.php
```php
<?php
/**
 * Pagina di ricerca film. Riceve il termine di ricerca tramite GET (?search=...),
 * interroga l'API TMDB sull'endpoint /search/movie e ordina i risultati per popolarità
 * decrescente con un bubble sort manuale. I risultati vengono normalizzati tramite
 * movieObj::search() e visualizzati come lista di card cliccabili.
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/movie_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Dotenv\Dotenv;
use Kiwilan\Tmdb\Tmdb;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$tmdb = Tmdb::client($_ENV['API_KEY']);

$errore = "";
$moviesList = [];
$searched = isset($_GET['search']) ? trim($_GET['search']) : '';


if ($searched !== '') {
    $raw = $tmdb->raw()->url('/search/movie', [
        'query' => $searched,
        'language' => 'it-IT'
    ]);

    $results = [];

    if ($raw !== null) {
        $body = $raw->getBody();

        if (isset($body['results'])) {
            $results = $body['results'];
        }
    }

    $n = count($results);

    // Ordinare per popolarità
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = $i + 1; $j < $n; $j++) {
            if ($results[$i]['popularity'] < $results[$j]['popularity']) {
                // scambio
                $temp = $results[$i];
                $results[$i] = $results[$j];
                $results[$j] = $temp;
            }
        }
    }

    if (empty($results)) 
        $errore = "Nessun risultato trovato per: " . htmlspecialchars($searched);
    else 
        $moviesList = movieObj::search($results);
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerca Film - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1 d-flex flex-column align-items-center">

        <div class="w-100" style="max-width: 650px;">

            <?php if ($errore): ?>
                <div class="alert alert-warning text-center shadow-sm rounded-3 border-0">
                    <i class="bi bi-info-circle me-2"></i> <?= $errore ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($moviesList)): ?>
                <h5 class="text-muted mb-3 fw-normal">Risultati della ricerca</h5>
                <div class="d-flex flex-column gap-3">

                    <?php foreach ($moviesList as $movie): ?>
                        <a href="film.php?tmdb_id=<?= urlencode($movie['id']) ?>" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-3 card-hover bg-white search-result-card">
                                <div class="card-body px-4 py-3 d-flex align-items-center gap-3">

                                    <?php if ($movie['poster']): ?>
                                        <img src="<?= htmlspecialchars($movie['poster']) ?>"
                                            alt="Poster <?= htmlspecialchars($movie['titolo']) ?>"
                                            class="rounded-2 flex-shrink-0"
                                            style="width: 48px; height: 72px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-2 flex-shrink-0 bg-secondary d-flex align-items-center justify-content-center"
                                            style="width: 48px; height: 72px;">
                                            <i class="bi bi-film text-white fs-5"></i>
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex-grow-1 overflow-hidden">
                                        <span class="fs-6 text-dark fw-medium d-block text-truncate">
                                            <?= htmlspecialchars($movie['titolo']) ?>
                                        </span>
                                        <?php if ($movie['anno']): ?>
                                            <small class="text-muted"><?= htmlspecialchars($movie['anno']) ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <i class="bi bi-chevron-right text-muted flex-shrink-0"></i>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

        </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>

</html>
```

## File: pages/admin/sessions.php
```php
<?php
/**
 * Pagina di gestione sessioni (area admin). Mostra le ultime N sessioni di
 * accesso al sito con username, data di login e data di logout. Il numero
 * di righe da visualizzare è configurabile tramite il parametro GET ?righe=N
 * (default: 15). Utilizza userObj::readAccess per la query.
 *
 * @note Interagisce con le tabelle MariaDB: `sessioni`, `utenti` (tramite userObj::readAccess).
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}

$user = new userObj($conn, $username);
$utenti = $user->readAll();

$righe = $_GET['righe'] ?? 15;
$sessioni = $user->readAccess($righe);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area sessioni - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 mb-5 pb-5 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fs-4 fw-bold mb-0">Gestione Sessioni</h1>
            
            <form method="GET" class="d-flex align-items-center gap-2">
                <label class="small text-muted mb-0">Righe:</label>
                <input type="number" name="righe" class="form-control form-control-sm" style="width: 70px;" min="1" value="<?= htmlspecialchars($righe) ?>">
                <button type="submit" class="btn btn-sm btn-dark">Aggiorna</button>
            </form>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="border-0 ps-3">Username</th>
                            <th class="border-0">Data Login</th>
                            <th class="border-0">Data Logout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessioni as $sessione): ?>
                            <tr>
                                <td><?= htmlspecialchars($sessione['username']) ?></td>
                                <td><span class="badge bg-light text-dark fw-normal border"><?= htmlspecialchars($sessione['data_login'] ?? '') ?></span></td>
                                <td>
                                    <?php if (!empty($sessione['data_logout'])): ?>
                                        <span class="badge bg-light text-muted fw-normal border"><?= htmlspecialchars($sessione['data_logout']) ?></span>
                                    <?php else: ?>
                                        <span class="text small italic">In corso...</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: assets/css/style.css
```css
:root {
    /* Palette Colori Moderna (Slate & Indigo) */
    --accent: #6366f1;         /* Indaco vibrante */
    --accent-hover: #4f46e5;
    --accent-dark: #3730a3;
    --bg: #f8fafc;             /* Grigio freddo chiarissimo per lo sfondo */
    --bg-surface: #ffffff;     /* Bianco puro per le card */
    --bg-muted: #f1f5f9;
    --border: #e2e8f0;
    --text: #0f172a;           /* Testo quasi nero, molto leggibile */
    --text-muted: #64748b;

    /* Ombre fluide e stratificate */
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    
    /* Layout */
    --radius-md: 12px;
    --radius-lg: 16px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* =====================
   Base & Animazioni
===================== */
body {
    background-color: var(--bg);
    color: var(--text);
    font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* =====================
   Bottoni
===================== */
.btn-dark {
    background-color: var(--text) !important;
    border-color: var(--text) !important;
    color: #ffffff !important;
    border-radius: 8px;
    font-weight: 500;
    transition: var(--transition);
}

.btn-dark:hover {
    background-color: #1e293b !important;
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-outline-secondary {
    border-radius: 8px;
    font-weight: 500;
    border-color: var(--border);
    color: var(--text-muted);
    transition: var(--transition);
}

.btn-outline-secondary:hover {
    background-color: var(--bg-muted);
    color: var(--text);
    border-color: var(--text-muted);
}

/* =====================
   Navbar (Glassmorphism)
===================== */
.navbar {
    background-color: rgba(255, 255, 255, 0.8) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px); /* Supporto Safari */
    border-bottom: 1px solid rgba(226, 232, 240, 0.8) !important;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.navbar-brand {
    text-decoration: none;
    color: var(--text) !important;
    letter-spacing: -0.5px;
}

.nav-link {
    color: var(--text-muted) !important;
    font-weight: 500;
    transition: var(--transition);
}

.nav-link:hover,
.nav-link.text-dark {
    color: var(--accent) !important;
}

.nav-link.active-nav {
    color: var(--accent) !important;
    position: relative;
}

.nav-link.active-nav::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: var(--accent);
    border-radius: 2px;
}

/* =====================
   Ricerca
===================== */
.search-wrap {
    background: var(--bg-muted);
    border: 1px solid transparent;
    border-radius: 20px;
    padding: 8px 16px;
    transition: var(--transition);
    cursor: text;
}

.search-wrap:hover,
.search-wrap:focus-within {
    background: var(--bg-surface);
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

.search-wrap input {
    border: none;
    outline: none;
    font-size: 14px;
    background: transparent;
    width: 100%;
    color: var(--text);
}

.search-wrap input::placeholder {
    color: var(--text-muted);
}

/* Fix per input di ricerca base nella Navbar */
input[name="search"] {
    background-color: var(--bg-muted) !important;
    border: 1px solid transparent !important;
    border-radius: 20px !important;
    padding: 8px 16px;
    transition: var(--transition);
}

input[name="search"]:focus {
    background-color: var(--bg-surface) !important;
    border-color: var(--accent) !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
}

/* =====================
   Dropdown
===================== */
.dropdown-menu {
    border: 1px solid var(--border) !important;
    background-color: var(--bg-surface);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    padding: 8px 0;
}

.dropdown-item {
    font-weight: 500;
    transition: background-color 0.2s, color 0.2s;
}

.dropdown-item:hover,
.dropdown-item:active,
.dropdown-item.active {
    background-color: var(--bg-muted) !important;
    color: var(--accent) !important;
}

/* =====================
   Card Film & Hover Effects
===================== */
.card,
.search-result-card {
    background-color: var(--bg-surface) !important;
    border: 1px solid var(--border) !important;
    border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.transition-hover, .card-hover {
    transition: var(--transition);
    will-change: transform, box-shadow;
}

.transition-hover:hover, .card-hover:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg) !important;
    border-color: rgba(99, 102, 241, 0.3) !important;
}

/* =====================
   Form
===================== */
.form-control {
    background-color: var(--bg-surface) !important;
    border: 1px solid var(--border) !important;
    border-radius: 8px;
    color: var(--text) !important;
    padding: 10px 14px;
    transition: var(--transition);
}

.form-control::placeholder {
    color: var(--text-muted) !important;
}

.form-control:focus {
    border-color: var(--accent) !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
}

/* =====================
   Footer
===================== */
footer {
    border-top: 1px solid var(--border) !important;
    background-color: var(--bg-surface) !important;
}

/* =====================
   Alerts
===================== */
.alert {
    border-radius: var(--radius-md);
    border: none !important;
    font-weight: 500;
}

.alert-danger {
    background-color: #fef2f2 !important;
    color: #b91c1c !important;
}

.alert-success {
    background-color: #f0fdf4 !important;
    color: #15803d !important;
}

.alert-warning {
    background-color: #fffbeb !important;
    color: #b45309 !important;
}

/* =====================
   Password Toggle
===================== */
.password-wrapper {
    position: relative;
    width: 100%;
}

.toggle-icon {
    position: absolute;
    right: 15px;
    bottom: 12px;
    cursor: pointer;
    color: var(--text-muted);
    font-size: 1.1rem;
    z-index: 10;
    transition: color 0.2s;
}

.toggle-icon:hover {
    color: var(--text);
}

/* =====================
   Placeholder Poster
===================== */
.card-img-top {
    background-color: var(--bg-muted);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='48' height='48' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z'/%3E%3Cpath d='M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
}

/* =====================
   Barra di scorrimento
===================== */
::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}

::-webkit-scrollbar-track {
  background: var(--bg);
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
  border: 2px solid var(--bg);
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
```

## File: actions/change_password.php
```php
<?php
/**
 * Gestisce il cambio password dell'utente autenticato: verifica la password
 * attuale, controlla la corrispondenza tra la nuova password e la conferma,
 * quindi aggiorna il record tramite userObj::changePassword.
 * Richiede una sessione attiva; in caso contrario reindirizza alla home.
 *
 * @note Interagisce con la tabella MariaDB: `utenti` (tramite userObj::changePassword).
 */
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/connection.php');
require_once(__DIR__ . '/../includes/user_obj.php');

$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}

$errore = '';
$messaggio = '';

if (isset($_POST['cambia_password'])) {
    $password_attuale = $_POST['password_attuale'] ?? '';
    $nuova_password = $_POST['nuova_password']   ?? '';
    $conferma = $_POST['conferma_password'] ?? '';

    if (!$password_attuale || !$nuova_password || !$conferma) {
        $errore = "Compila tutti i campi";
    } elseif ($nuova_password !== $conferma) {
        $errore = "Le nuove password non coincidono";
    } elseif ($password_attuale === $nuova_password) {
        $errore = "La nuova password deve essere diversa dalla attuale";
    } else {
        try {
            $user = new userObj($conn, $username);
            $risultato = $user->changePassword($password_attuale, $nuova_password);

            if ($risultato['ok']) {
                $messaggio = "Password aggiornata con successo";
            } else {
                $errore = $risultato['errore'];
            }
        } catch (PDOException $e) {
            $errore = "Errore"; 
            error_log("Errore: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambia Password - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid">

        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4 px-4">

                <a href="javascript:void(0)" 
                    onclick="closeAndRedirect()" 
                    class="btn-close position-absolute top-0 start-0 m-4" 
                    aria-label="Close">
                </a>

                <div class="text-center mb-5">
                    <h1 class="display-6 fw-bolder mb-2">Cambia password</h1>
                    <p class="text-secondary">Modifica la tua password</p>
                </div>

                <?php if ($errore): ?>
                    <div class="alert alert-danger border-0 small py-2 mb-4 text-center"><?= htmlspecialchars($errore) ?></div>
                <?php endif; ?>
                
                <?php if ($messaggio): ?>
                    <div class="alert alert-success border-0 small py-2 mb-4 text-center"><?= htmlspecialchars($messaggio) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4 position-relative password-wrapper">
                        <label class="form-label small text-secondary">Password attuale</label>
                        <input type="password" name="password_attuale" id="password_attuale" class="form-control bg-light border-light py-3" placeholder="Password attuale" required>
                        <i class="bi bi-eye toggle-icon" data-target="password_attuale"></i>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="mb-4 position-relative password-wrapper">
                        <label class="form-label small text-secondary">Nuova password</label>
                        <input type="password" name="nuova_password" id="nuova_password" class="form-control bg-light border-light py-3" placeholder="Nuova password" required>
                        <i class="bi bi-eye toggle-icon" data-target="nuova_password"></i>
                    </div>

                    <div class="mb-5 position-relative password-wrapper">
                        <label class="form-label small text-secondary">Conferma nuova password</label>
                        <input type="password" name="conferma_password" id="conferma_password" class="form-control bg-light border-light py-3" placeholder="Ripeti nuova password" required>
                        <i class="bi bi-eye toggle-icon" data-target="conferma_password"></i>
                    </div>

                    <button type="submit" name="cambia_password" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">
                        Salva modifiche
                    </button>

                    <p class="text-center small text-secondary">Non ricordi la password? 
                        <a href="contact.php" class="text-dark fw-bold text-decoration-none">
                            Contattaci
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: includes/header.php
```php
<?php
// Session_start() deve essere chiamato all'inizio di ogni files
$isLogged = isset($_SESSION['username']);
$currentPage = basename($_SERVER['SCRIPT_NAME']);

$publicPages = ['login.php', 'signup.php'];
$adminPages = ['add_film.php', 'dashboard.php', 'sessions.php', 'users.php', 'edit_user.php', 'notifications.php', 'films.php', 'film_db.php'];

$isPublicPage = in_array($currentPage, $publicPages);
$isAdminPage = in_array($currentPage, $adminPages);
?>

<nav class="navbar navbar-expand-lg px-4 py-3 border-bottom mb-0" style="position: relative;">
    <div class="container-fluid">

        <?php if ($isAdminPage): ?>
            <a href="/pages/admin/dashboard.php" class="navbar-brand fw-bold text-dark" style="font-size: 20px; z-index: 2;">
                Cinevobis
            </a>
        <?php else: ?>
            <a href="/" class="navbar-brand fw-bold text-dark" style="font-size: 20px; z-index: 2;">
                Cinevobis
            </a>
        <?php endif; ?>

        <div class="ms-auto d-flex align-items-center gap-3" style="z-index: 2;">
            <?php if (!$isAdminPage): ?>
                <form action="/pages/public/search.php" method="GET" class="d-flex align-items-center m-0">
                    <input type="text" name="search" placeholder="Cerca un film..." class="form-control form-control-sm shadow-none rounded-3 me-2" style="min-width: 220px;" required value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </form>
            <?php endif; ?>

            <?php if (!$isLogged): ?>
                <div class="d-flex gap-2 align-items-center">
                    <a href="/pages/public/login.php" class="btn btn-outline-secondary btn-sm px-4">Accedi</a>
                    <a href="/pages/public/signup.php" class="btn btn-dark btn-sm px-4">Registrati</a>
                </div>
            <?php else: ?>
                <div class="dropdown">
                    <button class="btn border-0 p-2 shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">

                        <li><a class="dropdown-item py-2 small" href="/pages/user/profile.php">Profilo</a></li>

                        <?php if(!$isAdminPage): ?>
                            <li><a class="dropdown-item py-2 small" href="/pages/user/favorites.php">Preferiti</a></li>
                            <li><a class="dropdown-item py-2 small" href="/pages/user/watchlist.php">Watchlist</a></li>
                            <li><a class="dropdown-item py-2 small" href="/pages/user/watched.php">Watched</a></li>
                            <li><a class="dropdown-item py-2 small" href="/pages/user/reviews.php">Recensioni</a></li>
                            <li><a class="dropdown-item py-2 small" href="/pages/user/notice_board.php">Bacheca</a></li>
                            
                            <?php if ($_SESSION['id_profilo'] == 2): ?>
                                <li><a class="dropdown-item py-2 small" href="/actions/contact.php">Contattaci</a></li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if($isAdminPage): ?>
                            <li><a class="dropdown-item py-2 small" href="/pages/admin/dashboard.php">Dashboard</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item py-2 small" href="/">Home</a></li>
                        <?php endif; ?>
                        

                        <?php if ($_SESSION['id_profilo'] == '1' && !$isAdminPage): ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item py-2 small fw-bold text" href="/pages/admin/dashboard.php">Dashboard</a></li>
                        <?php endif; ?>


                        <?php if ($isAdminPage): ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item py-2 small fw-bold text" href="/">Esci</a></li>
                        <?php endif; ?>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item py-2 small fw-bold text-danger" href="/actions/logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
```

## File: index.php
```php
<?php
/**
 * Homepage di Cinevobis. Recupera da MongoDB due liste di film:
 * - "Film in evidenza": gli ultimi 12 film aggiunti al catalogo (ordinati per data).
 * - "Migliori film": i 6 film con il voto medio più alto, da cui viene estratto
 *   il "Film della settimana" usando il numero della settimana ISO come seed deterministico.
 *
 * @note Interagisce con la collezione MongoDB: `films` (database: `cinevobis`).
 */
require_once(__DIR__ . '/config/config.php');
require_once(__DIR__ . '/config/connection.php');
require_once(__DIR__ . '/includes/header_logic.php');
require_once(__DIR__ . '/vendor/autoload.php');

use MongoDB\Client;

$nome = $_SESSION['nome'] ?? '';


// DIchiarazione variabili
$collection = [];
$cursor = [];

try {
    // Connessione a MongoDB
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

} catch (MongoDBException $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}


// I Film in evidenza
$recommendedFilms = [];

try {
    $cursor = $collection->find([], [
        'limit' => 12,
        'sort' => ['release_date' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
    ]);

    $recommendedFilms = iterator_to_array($cursor);

    $cursor = $collection->find([], [
        'limit' => 6,
        'sort' => ['vote_average' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
    ]);
    
} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
}


// I migliori Film
$topFilms = [];

try {
    $topFilms = iterator_to_array($cursor);
    
    // Film della settimana: cambia ogni lunedì usando il numero della settimana come seed
    $weekSeed = (int)date('oW');  // anno ISO + numero settimana
    $index = $weekSeed % count($topFilms);
    $film = $topFilms[$index] ?? null;

} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include("includes/header.php"); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <div class="container">
            <?php if(isset($nome)): ?>
                <h1 class="fw-bold mb-4">Benvenuto <?= htmlspecialchars($nome) ?></h1>
            <?php else: ?>
                <h1 class="fw-bold mb-4">Benvenuto</h1>
            <?php endif; ?>

            <?php if (!empty($topFilms)):
                $id = $film['id'] ?? '';
                
                $titolo = $film['title'] ?? '';
                $anno = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';  // Restituisce parte di una stringa
                
                $rating = isset($film['vote_average']) ? number_format((float)$film['vote_average'], 1) : null;
                $overview = $film['overview'] ?? '';

                $background = '';

                // Controlliamo se c'è un background
                if (!empty($film['backdrop_path'])) {
                    
                    $background = "https://image.tmdb.org/t/p/w1280" . $film['backdrop_path'];

                // Se non c'è controlliamo il poster
                } elseif (!empty($film['poster_path'])) {
                    
                    $background = "https://image.tmdb.org/t/p/w500" . $film['poster_path'];

                // Se non c'è né il background né il poster
                } else {
                    $background = ''; 
                }
            ?>

            <div class="position-relative rounded-4 overflow-hidden mb-5"
                 style="min-height: 420px; background: url('<?= htmlspecialchars($background) ?>') center/cover no-repeat #1a1a1a;">
                <div class="position-absolute top-0 start-0 w-100 h-100"
                     style="background: linear-gradient(to right, rgba(0,0,0,.85) 0%, rgba(0,0,0,.4) 60%, transparent 100%);"></div>
                <div class="position-relative d-flex align-items-end h-100 p-4 p-md-5" style="min-height: 420px;">
                    <div style="max-width: 500px;">
                        <div class="mb-2 d-flex align-items-center gap-2">
                            <span class="badge bg-white bg-opacity-25 text-white border border-white border-opacity-25 fw-semibold">Film della settimana</span>
                            <?php if ($anno): ?>
                                <span class="text-white-50 small"><?= htmlspecialchars($anno) ?></span>
                            <?php endif; ?>
                        </div>
                        <h2 class="fw-bold text-white mb-2" style="font-size: clamp(1.6rem, 3.5vw, 2.4rem);">
                            <?= htmlspecialchars($titolo) ?>
                        </h2>
                        <?php if ($rating): ?>
                            <p class="text-white fw-semibold mb-2">
                                <i class="bi bi-star-fill text-warning me-1"></i><?= $rating ?> <span class="text-white-50">/ 10</span>
                            </p>
                        <?php endif; ?>
                        <?php if ($overview): ?>
                            <p class="text-white-50 small mb-3 d-none d-md-block hero-overview">
                                <?= htmlspecialchars($overview) ?>
                            </p>
                        <?php endif; ?>
                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>"
                           class="btn btn-light fw-bold rounded-pill px-4">Scopri di più
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
                <h3 class="fw-bold m-0">I Film in evidenza</h3>
            </div>

            <?php if (empty($recommendedFilms)): ?>
                <div class="alert alert-info shadow-sm rounded-4 border-0">
                    <i class="bi bi-info-circle me-2"></i>Nessun film trovato nel database.
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                    <?php foreach ($recommendedFilms as $film):
                        $id     = $film['id'] ?? '';
                        $titolo = $film['title'] ?? 'Titolo non disponibile';
                        $poster = !empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : "https://via.placeholder.com/500x750?text=No+Poster";
                        $anno   = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                    ?>
                    <div class="col">
                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                                <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" style="object-fit: cover; aspect-ratio: 2/3;">
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

            <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
                <h3 class="fw-bold m-0">I migliori Film</h3>
            </div>

            <?php if (empty($topFilms)): ?>
                <div class="alert alert-info shadow-sm rounded-4 border-0">
                    <i class="bi bi-info-circle me-2"></i>Nessun film trovato nel database.
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                    <?php foreach ($topFilms as $film):
                        $id     = $film['id'] ?? '';
                        $titolo = $film['title'] ?? 'Titolo non disponibile';
                        $poster = !empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : "https://via.placeholder.com/500x750?text=No+Poster";
                        $anno   = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                    ?>
                    <div class="col">
                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                                <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" loading="lazy" style="object-fit: cover; aspect-ratio: 2/3;">
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

        </div>
    </main>

    <?php require_once('includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## File: pages/public/film.php
```php
<?php
/**
 * Pagina di dettaglio film (pubblica). Recupera i dati del film dall'API TMDB,
 * li salva in MongoDB alla prima visita e li aggiorna se sono più vecchi di 30 giorni.
 * Per gli utenti autenticati gestisce le azioni POST per aggiungere/rimuovere il film
 * da Preferiti, Watchlist e Watched, e verifica se l'utente ha già scritto una recensione.
 *
 * @note Interagisce con la collezione MongoDB: `films` (insert/update/find).
 * @note Interagisce con le tabelle MariaDB: `preferiti`, `watchlist`, `watched`, `recensioni`.
 */
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/movie_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Dotenv\Dotenv;
use Kiwilan\Tmdb\Tmdb;
use MongoDB\Client;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$tmdb = Tmdb::client($_ENV['API_KEY']);


// Dichiarazione variabili
$movie_api = null;
$movie_db = null;
$collection = [];
$errore = "";

$movie_id = $_GET['tmdb_id'] ?? null;


// Connessione a MongoDB
try {
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');
    
} catch(Exception $e) {
    error_log("Errore: " . $e->getMessage());
}


// 1. Recupero film da TMDB
if (!empty($movie_id)) {
    $results = $tmdb->raw()->url("/movie/{$movie_id}", [
        'language' => 'it-IT',
        'append_to_response' => 'credits,videos'
    ]);

    $body = $results?->getBody();
    if ($body) {
        $movie_api = is_string($body) ? json_decode($body, true) : $body;
    }

    if (empty($movie_api)) {
        $errore = "Film non trovato su TMDB";
    }
} else {
    $errore = "Nessun film selezionato";
}


// 2. Controllo/inserimento o aggiornamento in MongoDB
if (!empty($movie_api)) {

    $now = time();
    $aMonthInSeconds = 30 * 24 * 60 * 60; // 30 giorni

    
    // Cercare il film nel DB
    $movie_db = $collection->findOne(
        ['id' => (int)$movie_id],
        ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
    );


    // Se non esiste lo si inserisci
    if ($movie_db === null) {
        $movie_api['last_updated'] = new \MongoDB\BSON\UTCDateTime();  // Timestamp attuale in secondi
        $collection->insertOne($movie_api);
        $movie_db = $movie_api;
    } else {
        // Se esiste si recupera il timestamp 
        $lastUpdateSeconds = isset($movie_db['last_updated'])
            ? $movie_db['last_updated']->toDateTime()->getTimestamp()
            : 0;

        // Se è passato un mese si aggiorna il film
        if (($now - $lastUpdateSeconds) > $aMonthInSeconds) {
            $movie_api['last_updated'] = new \MongoDB\BSON\UTCDateTime();

            $collection->updateOne(
                ['id' => $movie_id],
                ['$set' => $movie_api]
            );

            $movie_db = $movie_api;  // Usare i dati fresci per la visualizzazione
        }
    }
}


// 3. Estrazione dati
$titolo = $trama = $poster_path = $trailerKey = $paese = '';
$voto = 0;
$durata = $anno = '';
$generi = $cast = $registi = [];

if ($movie_db) {
    $movieObj = new movieObj($movie_db);
    $data = $movieObj->toArray();

    $titolo = $data['titolo'];
    $titolo_orig = $data['titolo_orig'];

    $trama = $data['trama'];
    $poster_path = $data['poster_path'];

    $voto = $data['voto'];
    $trailerKey = $data['trailer_key'];

    $durata = $data['durata'];
    $anno = $data['anno'];

    $generi = $data['generi'];
    $paese = $data['paese'];

    $cast = $data['cast'];
    $registi = $data['registi'];
}


// Dichiarazione variabili
$tmdb_id = $movie_db['id'];
$id_utente = $_SESSION['id_utente'];

$is_favorite = false;
$is_review = false;
$is_watchlist = false;
$is_watched = false;


// Verifica condizioni per preferiti
if ($tmdb_id != null && $id_utente != null) {

    // Aggiungere il film alla lista preferiti
    if (isset($_POST['favorite'])) {
        try {
            $sql = "INSERT INTO preferiti (tmdb_id, id_utente, data_aggiunto) VALUES
                    (:tmdb_id, :id_utente, :data_aggiunto)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'tmdb_id' => $tmdb_id,
                'id_utente' => $id_utente,
                'data_aggiunto' => date('Y-m-d H:i:s')
            ]);

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nell'aggiunta alla lista preferiti";
        }
    }

    // Elimina dalla lista preferiti
    if (isset($_POST['delete_favorite'])) {
        try {
            $sql = "DELETE FROM preferiti WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id_utente' => $id_utente,
                ':tmdb_id' => $tmdb_id
            ]);

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nella rimozione dalla lista preferiti";
        }
    }

    // Controllo se l'utente ha aggiunto il film ai preferiti (DOPO aver gestito il POST)
    try {
        $sql = "SELECT * FROM preferiti WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_utente' => $id_utente,
            ':tmdb_id' => $tmdb_id
        ]);

        $results = $stmt->fetchColumn();
        
        if (!empty($results)) 
            $is_favorite = true;

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
    }
}


// Verifica condizioni per watchlist
if ($tmdb_id != null && $id_utente != null) {

    // Aggiungere il film alla lista watchlist
    if (isset($_POST['watchlist'])) {
        try {
            $sql = "INSERT INTO watchlist (tmdb_id, id_utente, data_aggiunto) VALUES
                    (:tmdb_id, :id_utente, :data_aggiunto)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'tmdb_id' => $tmdb_id,
                'id_utente' => $id_utente,
                'data_aggiunto' => date('Y-m-d H:i:s')
            ]);

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nell'aggiunta alla lista watchlist";
        }
    }

    // Elimina dalla lista watchlist
    if (isset($_POST['delete_watchlist'])) {
        try {
            $sql = "DELETE FROM watchlist WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id_utente' => $id_utente,
                ':tmdb_id' => $tmdb_id
            ]);

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nella rimozione dalla lista watchlist";
        }
    }

    // Controllo se l'utente ha aggiunto il film ai watchlist (DOPO aver gestito il POST)
    try {
        $sql = "SELECT * FROM watchlist WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_utente' => $id_utente,
            ':tmdb_id' => $tmdb_id
        ]);

        $results = $stmt->fetchColumn();

        if (!empty($results)) 
            $is_watchlist = true;

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
    }
}


// Verifica condizioni per watched
if ($tmdb_id != null && $id_utente != null) {

    // Aggiungere il film alla lista watched
    if (isset($_POST['watched'])) {
        try {
            $sql = "INSERT INTO watched (tmdb_id, id_utente, data_aggiunto) VALUES
                    (:tmdb_id, :id_utente, :data_aggiunto)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'tmdb_id' => $tmdb_id,
                'id_utente' => $id_utente,
                'data_aggiunto' => date('Y-m-d H:i:s')
            ]);

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nell'aggiunta alla lista watched";
        }
    }

    // Elimina dalla lista watched
    if (isset($_POST['delete_watched'])) {
        try {
            $sql = "DELETE FROM watched WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id_utente' => $id_utente,
                ':tmdb_id' => $tmdb_id
            ]);

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nella rimozione dalla lista watched";
        }
    }

    // Controllo se l'utente ha aggiunto il film ai watched (DOPO aver gestito il POST)
    try {
        $sql = "SELECT * FROM watched WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_utente' => $id_utente,
            ':tmdb_id' => $tmdb_id
        ]);

        $results = $stmt->fetchColumn();

        if (!empty($results)) 
            $is_watched = true;

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
    }
}


// Controlla se l'utente ha già recensito il film
if ($tmdb_id != null && $id_utente != null) {
    try {
        $sql = "SELECT * FROM recensioni WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_utente' => $id_utente, 
            ':tmdb_id' => $tmdb_id
        ]);
        
        $results = $stmt->fetchColumn();
        
        if (!empty($results)) {
            $is_review = true;
            $is_watched = true;
        }

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
    }
}

// Contiamo le recensioni degli altri utenti
$recensioni_altri = 0;
try {   
    $sql = "SELECT COUNT(*)
            FROM recensioni r
            WHERE tmdb_id = :tmdb_id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':tmdb_id' => $movie_id]);

    $recensioni_altri = $stmt->fetchColumn();

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $movie_db ? htmlspecialchars($titolo) : 'Film' ?> - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root { --accent-color: #ffc107; }

        .text-justify   { text-align: justify; }

        .cast-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container my-5 flex-grow-1">
        <?php if ($movie_db): ?>
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white">

                        <div class="row g-5 mb-5">
                            <div class="col-md-4">
                                <?php if($poster_path): ?>
                                    <img src="https://image.tmdb.org/t/p/w500<?= $poster_path ?>" class="img-fluid rounded-4 shadow-sm w-100" alt="Poster">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded-4 shadow-sm w-100" style="aspect-ratio: 2/3; border: 2px dashed #dee2e6;">
                                        <div class="text-center">
                                            <i class="bi bi-film text-muted" style="font-size: 4rem;"></i>
                                            <p class="text-muted small mt-2">Poster non disponibile</p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($trailerKey): ?>
                                    <div class="mt-3">
                                        <button type="button"
                                            class="btn btn-dark w-100 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#trailerModal">
                                            <i class="bi bi-play-fill fs-4 me-2"></i> Trailer
                                        </button>
                                    </div>
                                <?php endif; ?>

                            </div> <div class="col-md-8">
                                <h1 class="fw-bold text-dark display-5 mb-3"><?= htmlspecialchars($titolo) ?></h1>

                                <?php if (!empty($titolo_orig) && strcasecmp(trim($titolo_orig), trim($titolo)) !== 0): ?>
                                    <p class="text-muted fs-5 mb-4"><?= htmlspecialchars($titolo_orig) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($registi)): ?>
                                    <div class="mb-4">
                                        <small class="text-uppercase fw-bold text-muted d-block mb-2" style="letter-spacing:1px">Regia</small>
                                        <p class="fs-5 fw-medium mb-0">
                                            <?php 
                                            $registi_links = [];
                                            foreach ($registi as $regista) {
                                                // Creiamo un link per ogni singolo regista
                                                $name = htmlspecialchars($regista['name']);
                                                $id = urlencode($regista['id']);
                                                $registi_links[] = "<a href='https://www.themoviedb.org/person/$id' class='text-decoration-none link-dark'>$name</a>";
                                            }
                                            // Uniamo i link con una virgola
                                            echo implode(', ', $registi_links);
                                            ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($generi as $genre): ?>
                                        <a href="search_genre.php?id=<?= urlencode($genre['id']) ?>&name=<?= urlencode($genre['name']) ?>" 
                                            class="badge bg-white text-decoration-none text-dark border rounded-pill px-3 py-2">
                                            
                                            <?= htmlspecialchars($genre['name']) ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>

                                <?php if($_SESSION['username']): ?>
                                    <form method="POST" class="d-flex flex-wrap gap-2 mb-4">
                                        
                                        <?php if ($is_favorite): ?>
                                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3" name="delete_favorite">
                                                <i class="bi bi-heart-fill me-1 btn-action-icon"></i> Rimuovi
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3" name="favorite">
                                                <i class="bi bi-heart-fill me-1 btn-action-icon"></i> Preferiti
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($is_watchlist): ?>
                                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3" name="delete_watchlist">
                                                <i class="bi bi-bookmark-fill me-1 btn-action-icon"></i> Rimuovi
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3" name="watchlist">
                                                <i class="bi bi-bookmark-fill me-1 btn-action-icon"></i> Watchlist
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($is_watched): ?>
                                            <button class="btn btn-outline-success btn-sm rounded-pill px-3" name="delete_watched">
                                                <i class="bi bi-eye-fill me-1 btn-action-icon"></i> Rimuovi
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-outline-success btn-sm rounded-pill px-3" name="watched">
                                                <i class="bi bi-eye-fill me-1 btn-action-icon"></i> Watched
                                            </button>
                                        <?php endif; ?>

                                        <a href="/pages/user/review.php?tmdb_id=<?= urlencode($tmdb_id) ?>" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                            <i class="bi bi-pencil-fill me-1 btn-action-icon"></i>
                                            <?= $is_review ? "Modifica recensione" : "Scrivi recensione" ?>
                                        </a>
                                    </form>
                                <?php endif; ?>

                                <div class="border-top pt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="fw-bold m-0">Trama</h4>
                                        <div class="d-flex align-items-center fs-4 fw-bold">
                                            <i class="bi bi-star-fill text-warning me-2"></i>
                                            <span>
                                                <?= number_format($voto, 1) ?>
                                                <small class="text-muted fw-normal fs-6">/ 10</small>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-justify lh-lg text-dark fs-6 mb-4"><?= nl2br(htmlspecialchars($trama)) ?></p>
                                    
                                    <?php if($recensioni_altri > 0): ?>
                                        <a href="/pages/public/users_reviews.php?tmdb_id=<?= urlencode($tmdb_id) ?>" class="text-decoration-none fw-bold text-info">
                                            <i class="bi bi-chat-left-text-fill me-1"></i> Leggi le recensioni degli utenti
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row text-center py-4 bg-light rounded-4 mb-5 border mx-0">
                            <div class="col-4 border-end">
                                <div class="small text-muted text-uppercase fw-bold">Durata</div>
                                <div class="fw-bold"><?= $durata ?> min</div>
                            </div>
                            <div class="col-4 border-end">
                                <div class="small text-muted text-uppercase fw-bold">Anno</div>
                                <div class="fw-bold"><?= $anno ?></div>
                            </div>
                            <div class="col-4">
                                <div class="small text-muted text-uppercase fw-bold">Paese</div>
                                <div class="fw-bold"><?= $paese ?></div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <h4 class="fw-bold mb-4">Cast Principale</h4>
                            <div class="row g-3">
                                <?php foreach ($cast as $actor):
                                    $profile = $actor['profile_path']
                                        ? "https://image.tmdb.org/t/p/w185" . $actor['profile_path']
                                        : "https://ui-avatars.com/api/?name=" . urlencode($actor['name']) . "&background=random";
                                ?>
                                    <div class="col-12 col-sm-6 col-lg-4">
                                        <a href="https://www.themoviedb.org/person/<?= $actor['id'] ?>" class="text-decoration-none d-block">
                                            <div class="d-flex align-items-center p-2 border rounded-3 bg-light shadow-sm transition-hover">
                                                <img src="<?= $profile ?>"
                                                     class="cast-avatar rounded-circle border border-2 border-white shadow-sm me-3"
                                                     loading="lazy"
                                                     alt="<?= htmlspecialchars($actor['name']) ?>">
                                                <div class="overflow-hidden">
                                                    <p class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.9rem;">
                                                        <?= htmlspecialchars($actor['name']) ?>
                                                    </p>
                                                    <p class="mb-0 text-muted text-truncate" style="font-size: 0.8rem;">
                                                        <?= htmlspecialchars($actor['character']) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?php if ($trailerKey): ?>
            <div class="modal fade" id="trailerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content bg-transparent border-0">
                        <div class="d-flex justify-content-end mb-4">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="ratio ratio-16x9 shadow-lg rounded-4 overflow-hidden">
                                <iframe id="trailerVideo"
                                    data-src="https://www.youtube.com/embed/<?= $trailerKey ?>?rel=0&autoplay=1"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-warning shadow-sm rounded-4"><?= htmlspecialchars($errore) ?></div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```
