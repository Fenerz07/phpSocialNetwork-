<?php
session_start();

require 'autoload.php';

use App\Repository\ArticleRepository;
use App\Entity\Article;

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Vérifier si l'utilisateur est administrateur
if (!isset($_SESSION['admin']) || $_SESSION['admin'] === false) {
    header('Location: index.php');
    exit;
}

// Vérifier si l'id de l'article est spécifié

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $articleRepo = new ArticleRepository();
    $article = $articleRepo->find($id);

    // Supprimer l'article si il existe
    
    if ($article instanceof Article) {
        // Supprimer l'image associée à l'article si elle existe
        if ($article->getImage() && file_exists($article->getImage())) {
            unlink($article->getImage());
        }
        $articleRepo->delete($article->getId());
        header('Location: admin_dashboard.php');
        exit;
    } else {
        header('Location: admin_dashboard.php');
        exit;
    }
} else {
    header('Location: admin_dashboard.php');
    exit;
}