<?php
class userObj {
    private string $username;
    private ?string $password;
    private ?string $nome;
    private ?string $cognome;
    private ?string $email;
    private ?int $id_profilo;
    private ?int $attivo;
    private PDO $db;


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


    public function findByUsername() {
        $sql = "SELECT id_utente, username, password, nome, cognome, email,
                       attivo, id_profilo, data_registrazione
                FROM utenti WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        return $stmt->fetch();
    }


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


    public function delete() {
        $sql  = "DELETE FROM utenti WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':username' => $this->username]);
    }


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


    public function setDataLogout(string $value, string $id_sessione) {
        $sql = "UPDATE sessioni SET data_logout = :value WHERE id_sessione = :id_s";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':value' => $value, 
            ':id_s'  => $id_sessione
        ]);
    }

    
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