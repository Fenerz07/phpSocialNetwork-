<?php

namespace App\Repository;

use App\Config\Database;
use App\Entity\User;

// Classe UserRepository pour gérer les utilisateurs

class UserRepository {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAll() : array {
        $stmt = $this->db->query("SELECT * FROM users");
        $users = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $users[] = new User(
                $row['username'], 
                $row['email'], 
                $row['password'], 
                $row['photo'], 
                $row['id'], 
                $row['created_at'],
                $row['last_connection'],
                $row['role_admin']
            );
        }
        return $users;
    }

    // Fonction pour créer un utilisateur

    public function create(User $user) {
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, photo, created_at, last_connection, role_admin) VALUES (:username, :email, :password, :photo, :created_at, :last_connection, :role_admin)");
        
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':email', $user->getMail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':photo', $user->getMediaObject());
        $stmt->bindValue(':role_admin', 0);
        
        $createdAt = date('Y-m-d H:i:s');
        $lastConnection = null;
        
        $stmt->bindValue(':created_at', $createdAt);
        $stmt->bindValue(':last_connection', $lastConnection);
        
        return $stmt->execute();
    }

    // Fonction pour récupérer un utilisateur par son id

    public function read($id) : ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $data ? new User(
            $data['username'], 
            $data['email'], 
            $data['password'], 
            $data['photo'], 
            $data['id'], 
            $data['created_at'], 
            $data['last_connection'],
            $data['role_admin'],
        ) : null;
    }

    // Fonction pour mettre à jour un utilisateur

    public function update(User $user) {
        $stmt = $this->db->prepare("UPDATE users SET username = :username, email = :email, password = :password, photo = :photo, created_at = :created_at, last_connection = :last_connection, role_admin = :role_admin WHERE id = :id");
        
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':email', $user->getMail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':photo', $user->getMediaObject());
        $stmt->bindValue(':created_at', $user->getCreatedAt());
        $stmt->bindValue(':last_connection', $user->getLastConnection());
        $stmt->bindValue(':role_admin', (int)$user->getRoleAdmin());
        $stmt->bindValue(':id', $user->getId());
        
        return $stmt->execute();
    }

    // Fonction pour supprimer un utilisateur

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    // Fonction pour récupérer un utilisateur par son email

    public function findByEmail($mail) : ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :mail");
        $stmt->bindValue(':mail', $mail);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $data ? new User(
            $data['username'], 
            $data['email'], 
            $data['password'], 
            $data['photo'], 
            $data['id'], 
            $data['created_at'], 
            $data['last_connection'],
            $data['role_admin']
        ) : null;
    }

    // Fonction pour récupérer un utilisateur par son id

    public function findByID($id) : ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $data ? new User(
            $data['username'], 
            $data['email'], 
            $data['password'], 
            $data['photo'], 
            $data['id'], 
            $data['created_at'], 
            $data['last_connection'],
            $data['role_admin']
        ) : null;
    }

    // Fonction pour mettre à jour la date de dernière connexion
    
    public function updateLastConnection($id) {
        $stmt = $this->db->prepare("UPDATE users SET last_connection = :last_connection WHERE id = :id");
        $lastConnection = date('Y-m-d H:i:s');
        $stmt->bindValue(':last_connection', $lastConnection);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
}
