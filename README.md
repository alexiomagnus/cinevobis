# Cinevobis

**Cinevobis** è una piattaforma web dedicata alla gestione e alla scoperta di contenuti cinematografici. Il sistema permette agli utenti di gestire il proprio profilo, creare liste di film e scrivere recensioni, offrendo al contempo strumenti amministrativi avanzati per la gestione del catalogo.

## Caratteristiche Principali

*   **Gestione Utenti**: Registrazione, login e profili personalizzati.
*   **Interazione Social**: Sistema di recensioni e bacheca avvisi.
*   **Organizzazione Contenuti**: Liste personalizzate come Preferiti, Watchlist e Film già visti.
*   **Dashboard Amministrativa**: Gestione del database dei film, monitoraggio utenti e sessioni.

## Prerequisiti

Per avviare correttamente il progetto in locale, assicurati di avere installato i seguenti servizi e strumenti di gestione:

1.  **PHP** (Versione 7.4 o superiore).
2.  **MariaDB**: Server per i dati relazionali (utenti, sessioni, recensioni).
3.  **MongoDB**: Server per il catalogo film (document-oriented).
4.  **DBeaver**: Strumento consigliato per la gestione e l'amministrazione del database MariaDB.
5.  **MongoDB Compass**: Interfaccia grafica consigliata per la gestione e la visualizzazione delle collezioni MongoDB.
6.  **Composer**: Per la gestione delle dipendenze PHP.
7.  **Node.js e npm**: Per la compilazione degli asset frontend.
8.  **Git**: Per la clonazione del repository.

## Procedura di Installazione

### 1. Clonazione del Repository

Apri il terminale e clona il progetto:

```bash
git clone https://github.com/alexiomagnus/cinevobis.git
cd cinevobis
```

### 2. Configurazione dei Database

Il progetto utilizza un'architettura a database ibrido.

#### 2.1 MariaDB (tramite DBeaver)
1.  Installa MariaDB tramite il sito ufficiale: https://mariadb.org/
2.  Installa Dbeaver mediante il sito ufficiale: https://dbeaver.io/download/
2.  Apri **DBeaver** e crea una nuova connessione a MariaDB.
3.  Crea un nuovo database denominato `cinevobis`.
4.  Utilizza la funzione "Esegui script SQL" di DBeaver per importare il file dello schema:
    *   File: `database/dump-cinevobis.sql`.

#### 2.2 MongoDB (tramite MongoDB Compass)
1.  Installa MongoDB e tramite il sito ufficiale: https://www.mongodb.com/try/download/community
2.  Installa MongoDB Compass mediante il sito ufficiale: https://www.mongodb.com/products/tools/compass
3.  Apri **MongoDB Compass** e connettiti alla tua istanza locale (`mongodb://localhost:27017`).
4.  Crea un nuovo database denominato `cinevobis`.
5.  All'interno del database `cinevobis`, crea una nuova collection denominata `films`.

### 3. Installazione delle Dipendenze

Esegui i seguenti comandi nella root del progetto:

*   **Backend**: `composer install`.
*   **Frontend**: `npm install`.

# Configurazione TMDB
Il sistema per funzionare correttamente richiede l'API di TMDB

3.  Crea un account TMDB e richiedi l'API gratuita dal sito ufficiale: https://www.themoviedb.org/
4.  Aggiungi la tua chiave al file .env

Copia la chiave più lunga

### 4. Configurazione dell'Ambiente (.env)

Configura le connessioni ai database creando un file di ambiente nella root del progetto, evitando di modificare direttamente i file in `config/`:

1.  Crea un file chiamato **`.env`**.
2.  Inserisci i seguenti parametri adattandoli alla tua configurazione:

```env
# Configurazione
API_KEY=la_tua_api_tmdb
DB_HOST=localhost
DB_NAME=cinevobis
DB_USER=tuo_utente
DB_PASS=tua_password
MONGODB_HOST=mongodb://localhost:27017
MONGODB_NAME=cinevobis
MONGODB_COLLECTION=films
SECRET_KEY=la_tua_secret_key
```

La secret key è necessaria per i cookie, generala con Bitwarden: https://bitwarden.com/password-generator/#password-generator

Questi valori verranno caricati da `config/connection.php` per gestire le connessioni al sistema

(Se vuoi utilizzare l'utente root metti a DB_USER=root e a DB_PASS=root)

### 5. Avvio del Progetto

Una volta completata la configurazione, avvia il server locale di PHP all'interno della cartella cinevobis:

```bash
php -S localhost:8000
```

Se vuoi usare un ambiente professionale e non il server messo a disposizione da PHP puoi usare:



Punta il tuo browser all'indirizzo: `http://localhost:8000`.

## Struttura delle Cartelle Principali

*   `/actions`: Logica backend per operazioni come logout e cambi password.
*   `/config`: File di configurazione di sistema e connessioni.
*   `/database`: Dump SQL e riferimenti per le credenziali.
*   `/includes`: Classi oggetto (User, Movie) e componenti comuni della UI.
*   `/pages`: Interfacce divise per tipologia di utente (admin, user, public).

Spero sia stato chiaro, buon divertimento.