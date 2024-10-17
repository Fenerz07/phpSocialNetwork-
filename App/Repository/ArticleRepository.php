<?php

namespace App\Repository;

use App\Config\Database;
use App\Entity\Article;

// Classe ArticleRepository pour gérer les articles

class ArticleRepository {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    // Fonction pour créer un article

    public function create(Article $article) {
        $stmt = $this->db->prepare("INSERT INTO articles (user_id, title, content, image) VALUES (:user_id, :title, :content, :image)");
        $stmt->bindValue(':user_id', $article->getUserId());
        $stmt->bindValue(':title', $article->getTitle());
        $stmt->bindValue(':content', $article->getContent());
        $stmt->bindValue(':image', $article->getImage());
        return $stmt->execute();
    }

    // Fonction pour récupérer tous les articles

    public function findAll() : array {
        $stmt = $this->db->query("
            SELECT articles.*, users.username AS author_name 
            FROM articles 
            JOIN users ON articles.user_id = users.id
        ");
        $articles = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $articles[] = new Article(
                $row['user_id'],
                $row['title'], 
                $row['content'], 
                $row['image'],
                $row['id'],
                $row['author_name'],
                $row['likes']
            );
        }
        return $articles;
    }

    // Fonction pour récupérer un article par son id

    public function find($id) : ?Article {
        $stmt = $this->db->prepare("
            SELECT articles.*, users.username AS author_name 
            FROM articles 
            JOIN users ON articles.user_id = users.id 
            WHERE articles.id = :id
        ");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $data ? new Article(
            $data['user_id'],
            $data['title'], 
            $data['content'], 
            $data['image'],
            $data['id'],
            $data['author_name'],
            $data['likes']
        ) : null;
    }

    // Fonction pour incrémenter les likes d'un article

    public function incrementLikes($id) {
        $stmt = $this->db->prepare("UPDATE articles SET likes = likes + 1 WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Fonction pour décrémenter les likes d'un article

    public function decrementLikes($id) {
        $stmt = $this->db->prepare("UPDATE articles SET likes = likes - 1 WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Fonction pour vérifier si un utilisateur a aimé un article

    public function hasUserLiked($articleId, $userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM article_likes WHERE article_id = :article_id AND user_id = :user_id");
        $stmt->bindValue(':article_id', $articleId, \PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Fonction pour ajouter un like à un article

    public function addUserLike($userId, $articleId) {
        $stmt = $this->db->prepare("INSERT INTO article_likes (user_id, article_id) VALUES (:user_id, :article_id)");
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':article_id', $articleId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Fonction pour retirer un like à un article

    public function removeUserLike($userId, $articleId) {
        $stmt = $this->db->prepare("DELETE FROM article_likes WHERE user_id = :user_id AND article_id = :article_id");
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':article_id', $articleId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Fonction pour récupérer les articles d'un utilisateur

    public function findByUserId($userId) : ?array {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();

        $articles = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $articles[] = new Article(
                $row['user_id'],
                $row['title'], 
                $row['content'], 
                $row['image'],
                $row['id']
            );
        }
        return $articles;
    }

    // Fonction pour supprimer un article

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}