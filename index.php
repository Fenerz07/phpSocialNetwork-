<?php
    require 'autoload.php';
    
    use App\Repository\ArticleRepository;
    use App\Repository\UserRepository;

    session_start();
    
    $articleRepo = new ArticleRepository();
    $articles = $articleRepo->findAll();

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    if ($_SESSION['admin'] === true) {
        header('Location: admin_dashboard.php');
        exit;
    }

    // Si l'utilisateur est connecté, on récupère son nom
    $userId = $_SESSION['user_id'];
    $userRepo = new UserRepository();
    $user = $userRepo->findByID($userId);
    $userName = $user->getUsername();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des articles</title>
    <link rel="stylesheet" href="Css/styles.css">
</head>
<body>
    <div class="container">
        <nav>
            <p>Bienvenue <?php echo $userName ?></p>
            <a href="logout.php">Deconnexion</a>
            <a href="modify_user.php">Modifier mon profil</a>
        </nav>
        <h1>Liste des articles</h1>
        <a href="create_article.php">Créer un article</a>
        <ul>
            <?php foreach (array_reverse($articles) as $article): ?>
                <li>
                    <p><strong>Auteur :</strong> <a href="profile.php?user_id=<?= $article->getUserId() ?>"><?= htmlspecialchars($article->getAuthorName() ?? 'Inconnu') ?></a></p>
                    <h2><?= htmlspecialchars($article->getTitle()) ?></h2>
                    <?php if ($article->getImage()): ?>
                        <img src="<?= htmlspecialchars($article->getImage()) ?>" alt="Image de l'article" width="200">
                    <?php endif; ?>
                    <br/>
                    <a href="article.php?id=<?= $article->getId() ?>">Lire l'article</a>
                    <form action="like_article.php" method="POST" style="display:inline;">
                        <input type="hidden" name="article_id" value="<?= $article->getId() ?>">
                        <button type="submit">J'aime (<?= $article->getLikes() ?>)</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

    