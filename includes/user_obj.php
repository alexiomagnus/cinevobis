<?php
// Rappresenta un utente e raggruppa le operazioni CRUD sugli account, le sessioni
// e le liste personali (preferiti, watchlist, watched, recensioni).
class userObj {
    private string $username;
    private ?string $password;
    private ?string $nome;
    private ?string $cognome;
    private ?string $email;
    private ?int $id_profilo;
    private ?int $attivo;
    private PDO $db;

    // Costruttore della classe: inizializza le proprietà dell'oggetto utente.
    // Se viene passata una password, ne genera automaticamente l'hash.
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

    // -------------------------------------------------------------------------
    // CRUD UTENTI
    // -------------------------------------------------------------------------

    // Crea un nuovo record utente nel database utilizzando i dati attualmente impostati nell'oggetto.
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

    // Cerca e restituisce tutti i dati di un singolo utente filtrando per il suo username.
    public function findByUsername() {
        $sql = "SELECT id_utente, username, password, nome, cognome, email,
                       attivo, id_profilo, data_registrazione
                FROM utenti WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Recupera la lista di tutti gli utenti registrati, includendo anche il nome del loro profilo (es. Admin, User).
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

    // Aggiorna le informazioni anagrafiche, lo stato e il profilo di un utente esistente.
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

    // Modifica la password dell'utente previa verifica che la password attuale inserita sia corretta.
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

    // disabilita l'utente dal database.
    public function disable() {
        $sql  = "UPDATE utenti SET attivo = 0 WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':username' => $this->username]);
    }

    // -------------------------------------------------------------------------
    // SESSIONI
    // -------------------------------------------------------------------------

    // Registra un nuovo evento di login, salvando l'ID specifico della sessione, l'utente associato e la data d'ingresso.
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

    // Registra un evento di logout, aggiornando la riga della sessione esistente con la data di uscita.
    public function setDataLogout(string $value, string $id_sessione) {
        $sql = "UPDATE sessioni SET data_logout = :value WHERE id_sessione = :id_s";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':value' => $value,
            ':id_s'  => $id_sessione
        ]);
    }

    // Ottiene l'elenco degli ultimi accessi (login/logout) degli utenti, limitando il numero di risultati restituiti a $num.
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

    // -------------------------------------------------------------------------
    // LISTE FILM (preferiti, watchlist, watched)
    // -------------------------------------------------------------------------

    // Metodo interno condiviso: aggiunge un film a una lista (tabella) per l'utente.
    private function addToList(string $tabella, int $tmdb_id, int $id_utente): void {
        $sql  = "INSERT INTO {$tabella} (tmdb_id, id_utente, data_aggiunto) VALUES (:tmdb_id, :id_utente, :data_aggiunto)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':tmdb_id'      => $tmdb_id,
            ':id_utente'    => $id_utente,
            ':data_aggiunto' => date('Y-m-d H:i:s')
        ]);
    }

    // Metodo interno condiviso: rimuove un film da una lista (tabella) per l'utente.
    private function removeFromList(string $tabella, int $tmdb_id, int $id_utente): void {
        $sql  = "DELETE FROM {$tabella} WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_utente' => $id_utente, ':tmdb_id' => $tmdb_id]);
    }

    // Metodo interno condiviso: verifica se un film è già presente in una lista (tabella) per l'utente.
    private function isInList(string $tabella, int $tmdb_id, int $id_utente): bool {
        $sql  = "SELECT 1 FROM {$tabella} WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_utente' => $id_utente, ':tmdb_id' => $tmdb_id]);
        return (bool) $stmt->fetchColumn();
    }

    // Aggiunge il film ai preferiti dell'utente.
    public function addFavorite(int $tmdb_id, int $id_utente): void {
        $this->addToList('preferiti', $tmdb_id, $id_utente);
    }

    // Rimuove il film dai preferiti dell'utente.
    public function removeFavorite(int $tmdb_id, int $id_utente): void {
        $this->removeFromList('preferiti', $tmdb_id, $id_utente);
    }

    // Restituisce true se il film è tra i preferiti dell'utente.
    public function isFavorite(int $tmdb_id, int $id_utente): bool {
        return $this->isInList('preferiti', $tmdb_id, $id_utente);
    }

    // Aggiunge il film alla watchlist dell'utente.
    public function addWatchlist(int $tmdb_id, int $id_utente): void {
        $this->addToList('watchlist', $tmdb_id, $id_utente);
    }

    // Rimuove il film dalla watchlist dell'utente.
    public function removeWatchlist(int $tmdb_id, int $id_utente): void {
        $this->removeFromList('watchlist', $tmdb_id, $id_utente);
    }

    // Restituisce true se il film è nella watchlist dell'utente.
    public function isInWatchlist(int $tmdb_id, int $id_utente): bool {
        return $this->isInList('watchlist', $tmdb_id, $id_utente);
    }

    // Aggiunge il film alla lista "visti" dell'utente.
    public function addWatched(int $tmdb_id, int $id_utente): void {
        $this->addToList('watched', $tmdb_id, $id_utente);
    }

    // Rimuove il film dalla lista "visti" dell'utente.
    public function removeWatched(int $tmdb_id, int $id_utente): void {
        $this->removeFromList('watched', $tmdb_id, $id_utente);
    }

    // Restituisce true se il film è nella lista "visti" dell'utente.
    public function isWatched(int $tmdb_id, int $id_utente): bool {
        return $this->isInList('watched', $tmdb_id, $id_utente);
    }

    // -------------------------------------------------------------------------
    // RECENSIONI
    // -------------------------------------------------------------------------

    // Restituisce true se l'utente ha già scritto una recensione per il film.
    public function hasReview(int $tmdb_id, int $id_utente): bool {
        $sql  = "SELECT 1 FROM recensioni WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_utente' => $id_utente, ':tmdb_id' => $tmdb_id]);
        return (bool) $stmt->fetchColumn();
    }

    // Conta il numero totale di recensioni della community per un dato film.
    public function countReviews(int $tmdb_id): int {
        $sql  = "SELECT COUNT(*) FROM recensioni WHERE tmdb_id = :tmdb_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tmdb_id' => $tmdb_id]);
        return (int) $stmt->fetchColumn();
    }
}
?>