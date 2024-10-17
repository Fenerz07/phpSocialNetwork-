<?php
session_start();

require 'autoload.php';

use App\Repository\UserRepository;
use App\Entity\User;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userRepo = new UserRepository();
$user = $userRepo->read($_SESSION['user_id']);

// Condition pour la modification du profil

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Récupération des données du formulaire

    $id = $_SESSION['user_id'];
    $email = $user->getMail();
    $username = $_POST['username'];
    $role_admin = $user->getRoleAdmin();
    $created_at = $user->getCreatedAt(); // Keep the original created_at date
    $last_connection = date('Y-m-d H:i:s'); // Update the last connection date

    // Vérification du mot de passe

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    } else {
        $password = $user->getPassword(); // Keep the original password if not changed
    }

    // Upload de l'image

    if (!empty($_FILES['media_object']['name'])) {
        $media_object = $_FILES['media_object']['name'];
        $uploadDir = 'uploads/';
        $nouveauNomFichier = uniqid() . '-' . basename($media_object);
        $uploadFile = $uploadDir . $nouveauNomFichier;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (move_uploaded_file($_FILES['media_object']['tmp_name'], $uploadFile)) {
            $media_object = $uploadFile;
        } else {
            $media_object = $user->getMediaObject(); // Keep the original media object if upload fails
        }
    } else {
        $media_object = $user->getMediaObject(); // Keep the original media object if not changed
    }

    // Mise à jour de l'utilisateur

    $updatedUser = new User(
        $username,
        $email,
        $password,
        $media_object,
        $id,
        $created_at,
        $last_connection,
        $role_admin
    );

    $userRepo->update($updatedUser);
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon profil</title>
    <link rel="stylesheet" href="Css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Modifier mon profil</h2>
        <form method="POST" action="modify_user.php" enctype="multipart/form-data">
            <label>Nom d'utilisateur:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user->getUsername()); ?>" required><br>
            <label>Nouveau mot de passe (optionnel):</label>
            <input type="password" name="password"><br>
            <label>Nouvelle Photo de Profil (optionnel):</label>
            <input type="file" name="media_object"><br>
            <?php if ($user->getMediaObject()): ?>
                <img src="<?= htmlspecialchars($user->getMediaObject()) ?>" alt="Photo de profil" width="100"><br>
            <?php endif; ?>
            <button type="submit">Mettre à jour le profil</button>
        </form>
    </div>
</body>
</html>