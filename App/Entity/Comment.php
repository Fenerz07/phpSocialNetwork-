<?php

namespace App\Entity;

//création de la classe Comment avec ses attributs

class Comment {
    private $id;
    private $articleId;
    private $userId;
    private $content;
    private $createdAt;
    private $authorName;

    public function __construct($articleId, $userId, $content, $id = null, $createdAt = null, $authorName = null) {
        $this->articleId = $articleId;
        $this->userId = $userId;
        $this->content = $content;
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->authorName = $authorName;
    }

    //getters pour récupérer les attributs de la classe

    public function getId() {
        return $this->id;
    }

    public function getArticleId() {
        return $this->articleId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getContent() {
        return $this->content;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getAuthorName() {
        return $this->authorName;
    }
}