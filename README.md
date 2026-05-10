# CineVobis

**CineVobis** è una piattaforma web dedicata agli appassionati di cinema, che permette agli utenti di scoprire film, gestire le proprie liste personali e interagire con la community attraverso recensioni e valutazioni.

## 🚀 Funzionalità Principali

Il progetto è suddiviso in diverse aree funzionali per soddisfare le esigenze di utenti pubblici, registrati e amministratori:

### 👤 Area Utente (Registrato)
*   **Gestione Profilo:** Visualizzazione e modifica delle informazioni personali e cambio password.
*   **Liste Personalizzate:** Gestione di una *Watchlist* (film da vedere), una lista dei film già visti (*Watched*) e dei propri *Preferiti*.
*   **Recensioni:** Possibilità di scrivere e visualizzare recensioni sui film.
*   **Bacheca:** Accesso a una bacheca di annunci o notifiche interne.

### 🕵️ Funzionalità Pubbliche
*   **Ricerca Avanzata:** Ricerca di film per titolo o per genere.
*   **Scheda Film:** Informazioni dettagliate su ogni titolo presente nel database.
*   **Autenticazione:** Sistema completo di Login e Signup per la creazione di nuovi account.
*   **Pagine informative:** Termini di servizio e informativa sulla privacy.

### ⚙️ Area Amministrativa (Dashboard)
*   **Pannello di Controllo:** Dashboard centralizzata per il monitoraggio del sistema.
*   **Gestione Film:** Strumenti per aggiungere, modificare o rimuovere film dal database.
*   **Gestione Utenti:** Monitoraggio e amministrazione degli account registrati.
*   **Monitoraggio Sessioni:** Controllo delle sessioni attive e delle notifiche di sistema.

## 🛠️ Tecnologie Utilizzate

*   **Linguaggio:** PHP (con approccio Object-Oriented, vedi `movie_obj.php` e `user_obj.php`).
*   **Database:** SQL (configurazione presente in `config/connection.php`).
*   **Frontend:** HTML5, CSS3 (`assets/css/style.css`) e JavaScript (`assets/js/script.js`).
*   **Dependency Management:** Supporto per **Composer** (PHP) e **NPM** (Node.js).

## 💻 Come Far Funzionare il Progetto

### Prerequisiti
*   Un server locale (come XAMPP, WAMP o MAMP) con supporto **PHP 7.4+** e **MySQL/MariaDB**.
*   **Composer** installato (opzionale, per dipendenze PHP).
*   **Node.js/NPM** (opzionale, per la gestione degli asset frontend).

### Installazione

1.  **Clona il repository:**
    ```bash
    git clone https://github.com/tuo-username/cinevobis.git
    cd cinevobis
    ```

2.  **Configura il Database:**
    *   Crea un database SQL sul tuo server locale.
    *   Modifica il file `config/connection.php` inserendo le tue credenziali (host, username, password, nome database).
    *   Esegui l'importazione dello schema del database (se fornito come file `.sql`).

3.  **Configurazione del sistema:**
    *   Controlla il file `config/config.php` per eventuali costanti globali o parametri di configurazione dell'applicazione.

4.  **Installa le dipendenze:**
    ```bash
    composer install
    npm install
    ```

5.  **Avvio:**
    *   Sposta la cartella del progetto nella directory `htdocs` (o equivalente) del tuo server web.
    *   Naviga su `http://localhost/cinevobis/index.php` tramite il tuo browser.

## 📂 Struttura del Progetto

*   `/actions`: Logica per operazioni specifiche (es. logout, cambio password, contatti).
*   `/assets`: File statici come fogli di stile (CSS) e script (JS).
*   `/config`: File di configurazione e connessione al database.
*   `/includes`: Logica di backend condivisa, oggetti (Movie/User) e componenti dell'interfaccia (header/footer).
*   `/pages`: Contiene le sottocartelle per le diverse aree (`public`, `user`, `admin`).
