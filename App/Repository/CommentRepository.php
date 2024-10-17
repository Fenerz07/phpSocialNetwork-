<?php

namespace App\Repository;

use App\Config\Database;
use App\Entity\Comment;

// Classe CommentRepository pour gérer les commentaires

class CommentRepository {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    // Fonction pour récupérer les commentaires par l'id de l'article

    public function findByArticleId($articleId) : array {
        $stmt = $this->db->prepare("
        SELECT comments.*, users.username AS author_name 
        FROM comments
        JOIN users ON comments.user_id = users.id
        WHERE comments.article_id = :article_id
        ORDER BY comments.created_at DESC
    ");
        $stmt->bindValue(':article_id', $articleId, \PDO::PARAM_INT);
        $stmt->execute();
        $comments = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $comments[] = new Comment(
                $row['article_id'],
                $row['user_id'],
                $row['content'],
                $row['id'],
                $row['created_at'],
                $row['author_name']
            );
        }
        return $comments;
    }

    // Fonction pour ajouter un commentaire

    public function addComment($articleId, $userId, $content) {
        $stmt = $this->db->prepare("INSERT INTO comments (article_id, user_id, content) VALUES (:article_id, :user_id, :content)");
        $stmt->bindValue(':article_id', $articleId, \PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, \PDO::PARAM_STR);
        return $stmt->execute();
    }
}