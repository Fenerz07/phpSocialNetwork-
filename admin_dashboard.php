<?php

// Inclusion des fichiers de configuration et d'autoloading

require 'autoload.php';

use App\Repository\UserRepository;
use App\Repository\ArticleRepository;

// Démarrage de la session

session_start();

// Vérification de l'authentification de l'utilisateur

if (!isset($_SESSION['user_id']) || !$_SESSION['admin']) {
    header('Location: login.php');
    exit;
}

// Récupération de la liste des utilisateurs et des articles

$userRepo = new UserRepository();
$users = $userRepo->getALL();

$articleRepo = new ArticleRepository();
$articles = $articleRepo->findALL();

// Récupération de l'utilisateur connecté

$userId = $_SESSION['user_id'];
$userRepo = new UserRepository();
$user = $userRepo->findByID($userId);
$userName = $user->getUsername();

?>

<link rel="stylesheet" href="Css/styles.css">

<h1>Tableau de bord administrateur</h1>
<p>Bienvenue <?= $userName ?></p>

<a href="modify_user.php">Modifier mon profil</a>

<h2>Liste des utilisateurs</h2>
<ul>
    <?php foreach ($users as $user): ?>
        <li>
            <a href="profile.php?user_id=<?= $user->getId() ?>"><?= htmlspecialchars($user->getUsername()) ?></a>
            <form action="delete_user.php" method="POST" style="display:inline;">
                <input type="hidden" name="user_id" value="<?= $user->getId() ?>">
                <button><a href="delete_user.php?id=<?= $user->getId() ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');"><p><strong>Supprimer cet Utilisateur</strong></p></a></button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>

<h2>Liste des articles</h2>
<ul>
    <?php foreach ($articles as $article): ?>
        <li>
            <a href="article.php?id=<?= $article->getId() ?>"><?= htmlspecialchars($article->getTitle()) ?></a>
            <form action="delete_article.php" method="POST" style="display:inline;">
                <input type="hidden" name="article_id" value="<?= $article->getId() ?>">
                <button><a href="delete_article.php?id=<?= $article->getId() ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');"><p><strong>Supprimer ce poste</strong></p></a></button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>

<a href="index.php">Retour à la liste des articles</a>
<a href="logout.php">Déconnexion</a>