<?php


namespace Core;

use PDO;

class Database {
    // FIXME make nice functions for getting user, games, attrs, etc
    public $con;
    public $statement;

    function __construct($dsn) {
        $this->con = new PDO($dsn, '', '', [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function removeGame(int $id, int $uid): void {
        $this->query('DELETE FROM games WHERE id = :id AND uid = :uid', [
            'id' => $id,
            'uid' => $uid
        ]);
    }

    public function saveGame(string $name, int $uid, string $state): void {
        $state_hash = sha1("{$uid}{$state}");
        $this->query('INSERT INTO games(uid, name, state, state_hash, timestamp) VALUES(:uid, :name, :state, :state_hash, :timestamp) ON CONFLICT(state_hash) DO UPDATE SET name=excluded.name, timestamp=excluded.timestamp', [
            'uid' => $uid,
            'name' => $name,
            'state' => $state,
            'state_hash' => $state_hash,
            'timestamp' => time()
        ]);
    }

    public function getGameState(int $uid, int $id): array|false {
        return $this->query('SELECT state FROM games WHERE uid = :uid AND id = :id', [
            'uid' => $uid,
            'id' => $id
        ])->find();
    }

    public function getGames(int $uid): array|false {
        return $this->query('SELECT * FROM games WHERE uid = :uid', [
            'uid' => $uid])->get();
    }

    public function getUserData(string $email): array|false {
        return $this->query('SELECT * FROM users WHERE email = :email', [
            'email' => $email])->find();
    }

    public function createUser(string $email, #[\SensitiveParameter] string $password): void {
        $this->query('INSERT INTO users(email, password) VALUES(:email, :password)', [
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);
    }

    public function query(string $query_string, array $params=[]) {
        $this->statement = $this->con->prepare($query_string);
        $this->statement->execute($params);
        return $this;
    }

    public function get() {
        return $this->statement->fetchAll();
    }

    public function find() {
        return $this->statement->fetch();
    }
}
