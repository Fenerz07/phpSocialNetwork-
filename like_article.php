<?php
require 'autoload.php';

use App\Repository\ArticleRepository;

session_start();

if (!isset($_POST['article_id'])) {
    echo "Aucun article spécifié.";
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour aimer un article.";
    exit;
}

$articleId = $_POST['article_id'];
$userId = $_SESSION['user_id'];
$articleRepo = new ArticleRepository();

// Vérifier si l'utilisateur est l'auteur de l'article

$article = $articleRepo->find($articleId);
if ($article->getUserId() == $userId) {
    header("Location: index.php");
    exit;
}

// condition pour aimer ou ne plus aimer un article

if ($articleRepo->hasUserLiked($articleId, $userId)) {
    $articleRepo->removeUserLike($userId, $articleId);
    $articleRepo->decrementLikes($articleId);
} else {
    $articleRepo->incrementLikes($articleId);
    $articleRepo->addUserLike($userId, $articleId);
}
header("Location: index.php");
exit;