<?php
session_start();
require 'autoload.php';

use App\Repository\UserRepository;

$errors = [];

// Vérification de l'authentification de l'utilisateur

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    // Validation de l'email et du mot de passe

    if (empty($mail)) {
        $errors[] = "L'email est obligatoire.";
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }

    if (empty($password)) {
        $errors[] = "Le mot de passe est obligatoire.";
    }

    if (empty($errors)) {
        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($mail);

        if ($user && password_verify($password, $user->getPassword())) {
            try {
                $userRepo->updateLastConnection($user->getId());
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['username'] = $user->getUsername();
                $_SESSION['admin'] = $user->getRoleAdmin();
                
                header('Location: index.php');
                exit;
            } catch(Exception $e) {
                echo "Erreur: " . $e->getMessage();
            }
        } else {
            $errors[] = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="Css/styles.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>

    <?php
    // Affichage des erreurs
    if (!empty($errors)) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
    }
    ?>

    <form method="POST" action="login.php">
        <label for="mail">E-mail:</label>
        <input type="email" id="mail" name="mail" required>
        <br>
        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Se connecter</button>
    </form>

    <br>
    <a href="create.php">Créer un compte</a>
</body>
</html>
