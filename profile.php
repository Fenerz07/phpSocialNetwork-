<?php
require 'autoload.php';

use App\Repository\UserRepository;
use App\Repository\ArticleRepository;

session_start();

if (!isset($_GET['user_id'])) {
    echo "Aucun utilisateur spécifié.";
    exit;
}

// Récupérer l'utilisateur

$userId = $_GET['user_id'];
$userRepo = new UserRepository();
$user = $userRepo->findByID($userId);

if (!$user) {
    echo "L'utilisateur n'existe pas.";
    exit;
}

// Récupérer les articles de l'utilisateur

$articleRepo = new ArticleRepository();
$articles = $articleRepo->findByUserId($userId);

// Vérifier si l'utilisateur est administrateur

$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

?>
<link rel="stylesheet" href="Css/styles.css">
<h1>Profil de <?= htmlspecialchars($user->getUsername() ?? 'Inconnu') ?></h1>

<?php if ($isAdmin): ?>
    <p><strong>Email :</strong> <?= htmlspecialchars($user->getMail() ?? 'Inconnu') ?></p>
    <p><strong>Date de création :</strong> <?= htmlspecialchars($user->getCreatedAt() ?? 'Inconnue') ?></p>
    <p><strong>Dernière connexion :</strong> <?= htmlspecialchars($user->getLastConnection() ?? 'Inconnue') ?></p>
    <p><strong>Nombre d'articles postés :</strong> <?= count($articles) ?></p>
    <?php if ($user->getMediaObject()): ?>
        <img src="<?= htmlspecialchars($user->getMediaObject()) ?>" width="100" height="100" alt="Photo de profil">
    <?php else: ?>
        <p>Aucune photo disponible.</p>
    <?php endif; ?>
<?php endif; ?>
<?php if (count($articles) != 0): ?>
    <h2>Articles postés par <?= htmlspecialchars($user->getUsername() ?? 'Inconnu') ?></h2>
    <ul>
        <?php foreach ($articles as $article): ?>
            <li>
                <h3><a href="article.php?id=<?= $article->getId() ?>"><?= htmlspecialchars($article->getTitle() ?? 'Sans titre') ?></a></h3>
                <p><?= htmlspecialchars($article->getContent() ?? 'Pas de contenu') ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<br>
<a href="index.php">Retour à la liste des articles</a>

<?php if ($isAdmin): ?>
    <a href="admin_dashboard.php">Retour au tableau de bord administrateur</a>
<?php endif; ?>

