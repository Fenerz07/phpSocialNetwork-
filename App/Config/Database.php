<?php

namespace App\Config;

// Importation de la classe PDO

use \PDO;

// Classe Database pour se connecter à la base de données

class Database {
    private $host = 'localhost';
    private $dbname = 'my_database';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function __construct() {
        $this->pdo = new \PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    // Fonction pour récupérer la connexion

    public function getConnection() {
        return $this->pdo;
    }
}
