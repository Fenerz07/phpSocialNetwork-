<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    // Détruire toutes les variables de session
    session_unset();

    // Détruire la session
    session_destroy();

    header('Location: login.php');
    exit;
