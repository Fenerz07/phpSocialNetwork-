<?php
require 'autoload.php';

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;

session_start();

if (!isset($_GET['id'])) {
    echo "Aucun article spécifié.";
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$articleId = $_GET['id'];
$articleRepo = new ArticleRepository();
$article = $articleRepo->find($articleId);

if (!$article) {
    echo "L'article n'existe pas.";
    exit;
}

$commentRepo = new CommentRepository();

// condition pour ajouter un commentaire

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $userId = $_SESSION['user_id'];
    $content = $_POST['comment'];
    $commentRepo->addComment($articleId, $userId, $content);
    header("Location: article.php?id=$articleId");
    exit;
}

$comments = $commentRepo->findByArticleId($articleId);
?>

<link rel="stylesheet" href="Css/styles.css">

<h1><?= htmlspecialchars($article->getTitle()) ?></h1>
<p><?= htmlspecialchars($article->getContent()) ?></p>

<?php if ($article->getImage()): ?>
    <img src="<?= htmlspecialchars($article->getImage()) ?>" alt="Image de l'article" width="300">
<?php endif; ?>

<br/>

<p><strong>Auteur :</strong> <?= htmlspecialchars($article->getAuthorName()) ?></p>
<p><strong>Nombre de likes :</strong> <?= $article->getLikes() ?></p>

<h2>Commentaires</h2>
<ul>
    <?php foreach ($comments as $comment): ?>
        <li>
            <p><?= htmlspecialchars($comment->getContent()) ?></p>
            <small>Posté le <?= $comment->getCreatedAt() ?></small>
            </br>
            <small> par <?= htmlspecialchars($comment->getAuthorName() ?? 'Inconnu')?></small>
        </li>
    <?php endforeach; ?>
</ul>

<?php if ($article->getUserId() != $_SESSION['user_id']): ?>
    <h3>Ajouter un commentaire</h3>
    <form method="POST" action="article.php?id=<?= $articleId ?>">
        <textarea name="comment" required></textarea><br>
        <button type="submit">Commenter</button>
    </form>
<?php endif; ?>

<br>
<a href="index.php">Retour à la liste des articles</a>