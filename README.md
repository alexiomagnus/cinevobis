# Cinevobis

**Cinevobis** è una piattaforma web dedicata alla gestione e alla scoperta di contenuti cinematografici. Il sistema permette agli utenti di gestire il proprio profilo, creare liste di film e scrivere recensioni, offrendo al contempo strumenti amministrativi avanzati per la gestione del catalogo.

## Caratteristiche Principali

- **Gestione Utenti**: Registrazione, login e profili personalizzati.
- **Interazione Social**: Sistema di recensioni e bacheca avvisi.
- **Organizzazione Contenuti**: Liste personalizzate come Preferiti, Watchlist e Film già visti.
- **Dashboard Amministrativa**: Gestione del database dei film, monitoraggio utenti e sessioni.

## Come avviare il progetto

Ci sono due modi per avviare Cinevobis in locale: **con container** (consigliato) o **installando i servizi direttamente sull'host**.

### Perché conviene usare i container

- **Nessun conflitto con l'ambiente PHP di sistema**: non serve installare/abilitare l'estensione `mongodb` sul PHP dell'host solo per farlo girare — resta isolata nel container.
- **Ambiente riproducibile**: MariaDB, MongoDB e Apache/PHP partono sempre con le stesse versioni ed estensioni, indipendentemente dal PC usato.
- **Setup più veloce per chi clona il repo**: non serve seguire una procedura di installazione manuale di MariaDB/MongoDB/DBeaver/Compass — basta un `docker-compose up`.
- **Facile pulizia**: si può eliminare tutto (`docker rm -f $(docker ps -aq)`, `docker volume prune`) senza lasciare tracce sparse sul sistema, a differenza di un'installazione nativa di MariaDB/MongoDB.

### Clona il repository

```bash
git clone https://github.com/alexiomagnus/cinevobis.git
cd cinevobis
```

### Opzione A — Avvio con container (consigliato)

**Prerequisiti**: Docker (o Podman) con `docker-compose`/`podman-compose` installati.

1. Crea il file `.env` nella root del progetto (i valori `DB_HOST` e `MONGODB_HOST` puntano ai nomi dei servizi definiti in `docker-compose.yml`, non a `localhost`):
   ```
   API_KEY=la_tua_api_tmdb
   DB_HOST=mariadb
   DB_NAME=cinevobis
   DB_USER=root
   DB_PASS=root
   MONGODB_HOST=mongodb://root:root@mongodb:27017/?authSource=admin
   MONGODB_NAME=cinevobis
   MONGODB_COLLECTION=films
   SECRET_KEY=la_tua_secret_key
   ```
   (`authSource=admin` è necessario perché l'utente `root` del container MongoDB viene creato nel database `admin`)

2. Genera la cartella `vendor/`. Il tuo PHP host quasi certamente non ha l'estensione `mongodb` installata (e non serve installarla solo per questo, dato che gira già nel container) — quindi hai due strade:

   **In locale, ignorando il controllo dell'estensione:**
   ```bash
   composer install --ignore-platform-req=ext-mongodb
   ```

   **Oppure dentro il container** (una volta avviato, anche senza `vendor/` — il container si avvia comunque, semplicemente le pagine PHP daranno errore finché non lanci l'install):
   ```bash
   docker-compose exec webserver composer install
   ```

   In entrambi i casi, dato che la cartella del progetto è montata come volume, `vendor/` comparirà nella cartella locale indipendentemente da dove hai lanciato il comando.

   Poi:
   ```bash
   npm install
   ```

3. Avvia i container:
   ```bash
   docker-compose up -d --build
   ```

4. Punta il browser su `http://localhost:8080` (Apache dentro il container serve sulla porta 80, mappata sulla 8080 dell'host). Se preferisci usare `http://cinevobis.local:8080` invece di `localhost`, aggiungi una riga al file `/etc/hosts` del tuo sistema:
   ```bash
   echo "127.0.0.1 cinevobis.local" | sudo tee -a /etc/hosts
   ```

**Comandi utili**
```bash
docker-compose ps               # stato dei container
docker-compose logs -f webserver   # log del container PHP/Apache
docker rm -f $(docker ps -aq)   # rimuove tutti i container esistenti
docker volume prune             # rimuove i volumi/dati salvati in precedenza
```

**Note (problemi comuni)**
- Se le pagine di catalogo danno errore "Class MongoDB\Driver\Manager not found", l'immagine del container PHP non ha l'estensione `mongodb` compilata: va aggiunta nel Dockerfile con `pecl install mongodb && docker-php-ext-enable mongodb`.
- Se vedi errori "Command find requires authentication" nei log, la stringa di connessione in `MONGODB_HOST` manca di utente/password/`authSource` rispetto a quanto configurato nel container MongoDB.

### Opzione B — Avvio manuale sull'host

Per chi preferisce installare i servizi direttamente sul proprio sistema, senza container.

**Prerequisiti**
1. **PHP** (Versione 7.4 o superiore), con estensione `mongodb` abilitata.
2. **MariaDB**: Server per i dati relazionali (utenti, sessioni, recensioni).
3. **MongoDB**: Server per il catalogo film (document-oriented).
4. **DBeaver**: Strumento consigliato per la gestione e l'amministrazione del database MariaDB.
5. **MongoDB Compass**: Interfaccia grafica consigliata per la gestione e la visualizzazione delle collezioni MongoDB.
6. **Composer**: Per la gestione delle dipendenze PHP.
7. **Node.js e npm**: Per la compilazione degli asset frontend.
8. **Git**: Per la clonazione del repository.

#### 1. Configurazione dei Database

Il progetto utilizza un'architettura a database ibrido.

**2.1 MariaDB (tramite DBeaver)**
1. Installa MariaDB tramite il sito ufficiale: <https://mariadb.org/>
2. Installa DBeaver mediante il sito ufficiale: <https://dbeaver.io/download/>
3. Apri **DBeaver** e crea una nuova connessione a MariaDB.
4. Crea un nuovo database denominato `cinevobis`.
5. Utilizza la funzione "Esegui script SQL" di DBeaver per importare il file dello schema: `database/dump-cinevobis.sql`.

**2.2 MongoDB (tramite MongoDB Compass)**
1. Installa MongoDB tramite il sito ufficiale: <https://www.mongodb.com/try/download/community>
2. Installa MongoDB Compass mediante il sito ufficiale: <https://www.mongodb.com/products/tools/compass>
3. Apri **MongoDB Compass** e connettiti alla tua istanza locale (`mongodb://localhost:27017`).
4. Crea un nuovo database denominato `cinevobis`.
5. All'interno del database `cinevobis`, crea una nuova collection denominata `films`.

#### 2. Installazione delle Dipendenze

Se il tuo PHP host non ha l'estensione `mongodb` installata (probabile, non serve installarla solo per questo), usa:

```bash
composer install --ignore-platform-req=ext-mongodb
```

Altrimenti, se hai installato anche l'estensione `mongodb` sull'host (necessaria comunque se vuoi far girare `php -S localhost:8000` con successo, vedi punto 6):

```bash
composer install
```

Poi:

```bash
npm install
```

#### 3. Configurazione TMDB

1. Crea un account TMDB e richiedi l'API gratuita dal sito ufficiale: <https://www.themoviedb.org/>
2. Aggiungi la tua chiave al file `.env`.

#### 4. Configurazione dell'Ambiente (.env)

Configura le connessioni ai database creando un file `.env` nella root del progetto, evitando di modificare direttamente i file in `config/`:

```
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

La secret key è necessaria per i cookie, generala con Bitwarden: <https://bitwarden.com/password-generator/#password-generator>

Questi valori verranno caricati da `config/connection.php` per gestire le connessioni al sistema.

(Se vuoi utilizzare l'utente root metti `DB_USER=root` e `DB_PASS=root`)

#### 5. Avvio del Progetto

```bash
php -S localhost:8000
```
Punta il browser su `http://localhost:8000`.

Se preferisci usare Apache invece del server integrato di PHP, vedi il passaggio con il container in Opzione A — funziona anche se i database girano nativamente sull'host, basta che `.env` punti agli indirizzi giusti.

## Struttura delle Cartelle Principali

- `/actions`: Logica backend per operazioni come logout e cambi password.
- `/config`: File di configurazione di sistema e connessioni.
- `/database`: Dump SQL e riferimenti per le credenziali.
- `/includes`: Classi oggetto (User, Movie) e componenti comuni della UI.
- `/pages`: Interfacce divise per tipologia di utente (admin, user, public).

Spero sia stato chiaro.