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
        $sql = "SELECT u.username, u.nome, u.cognome, s.data_login, s.data_logout
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