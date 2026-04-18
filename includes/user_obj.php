<?php
class userObj {
    private $username;
    private $password;
    private $nome;
    private $cognome;
    private $email;
    private $id_profilo;
    private $attivo;
    private $db;

    public function __construct($db, $username, $password = null, $nome = null, $cognome = null,
                            $email = null, $attivo = null, $id_profilo = null) {
        $this->db           = $db;
        $this->username     = $username;
        $this->password     = $password ? password_hash($password, PASSWORD_DEFAULT) : null;
        $this->nome         = $nome;
        $this->cognome      = $cognome;
        $this->email        = $email;
        $this->attivo       = $attivo;
        $this->id_profilo   = $id_profilo;
    }

    public function get($property) {
        if (property_exists($this, $property) && $property !== 'db') {
            return $this->$property;
        }
        return null;
    }

    public function set($property, $value) {
        // Impedisce la modifica diretta di db e username tramite questo metodo
        if (property_exists($this, $property) && $property !== 'db' && $property !== 'username') {
            if ($property === 'password') {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }
            $this->$property = $value;
            $sql  = "UPDATE utenti SET $property = :value WHERE username = :username";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':value' => $value, ':username' => $this->username]);
        }
        return false;
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

    public function update($usernameOriginale) {
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

    public function changePassword($passwordAttuale, $nuovaPassword) {
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

    public function createDataLogin($value, $id_sessione, $id_utente) {
        $sql = "INSERT INTO sessioni (id_sessione, id_utente, data_login)
                VALUES (:id_s, :id_u, :data_login)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_s'       => $id_sessione,
            ':id_u'       => $id_utente,
            ':data_login' => $value
        ]);
    }

    public function setDataLogout($value, $id_sessione) {
        $sql = "UPDATE sessioni SET data_logout = :value WHERE id_sessione = :id_s";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':value' => $value, ':id_s'  => $id_sessione]);
    }

    public function readAccess($num) {
        $sql = "SELECT u.username, s.data_login, s.data_logout
                FROM sessioni s
                JOIN utenti u ON u.id_utente = s.id_utente
                ORDER BY s.data_login DESC
                LIMIT :numero";
        $stmt = $this->db->prepare($sql);
        $num  = (int)$num;
        $stmt->bindParam(':numero', $num, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>