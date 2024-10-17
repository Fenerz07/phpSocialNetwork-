# Développement d'un site PHP inspiré d'Instagram

## Description

Ce projet est une application web développée en PHP, inspirée d'Instagram. Il permet aux utilisateurs de créer des comptes, de publier des articles, de liker et de commenter les articles des autres utilisateurs. Les administrateurs ont des privilèges supplémentaires pour gérer les utilisateurs et les articles.

## Fonctionnalités

### Utilisateurs

- **Inscription et Connexion** : Les utilisateurs peuvent s'inscrire et se connecter pour accéder à l'application.
- **Profil Utilisateur** : Chaque utilisateur a une page de profil où ses informations et ses articles sont affichés.
- **Modification du Profil** : Les utilisateurs peuvent modifier leurs informations de profil, y compris leur photo de profil.
- **Publication d'Articles** : Les utilisateurs peuvent publier des articles avec des images.
- **Likes et Commentaires** : Les utilisateurs peuvent liker et commenter les articles des autres utilisateurs.

### Administrateurs

- **Gestion des Utilisateurs** : Les administrateurs peuvent voir la liste de tous les utilisateurs, accéder à leurs profils détaillés (sauf le mot de passe) et supprimer des utilisateurs.
- **Gestion des Articles** : Les administrateurs peuvent voir la liste de tous les articles, accéder aux articles détaillés et supprimer des articles.

## Installation

- **git clone https://github.com/Fenerz07/phpSocialNetwork-**

## Utilisation
**Inscription et Connexion**
Accédez à la page d'inscription pour créer un nouveau compte.
Connectez-vous avec vos identifiants pour accéder à l'application.
**Publication d'Articles**
Une fois connecté, vous pouvez publier des articles en accédant à la page de création d'article.
Ajoutez un titre, un contenu et une image pour votre article.
**Likes et Commentaires**
Vous pouvez liker et commenter les articles des autres utilisateurs.
Les likes et les commentaires sont mis à jour en temps réel grâce à AJAX.
**Gestion Administrateur**
Les administrateurs peuvent accéder au tableau de bord administrateur pour gérer les utilisateurs et les articles.
Les administrateurs peuvent supprimer des utilisateurs et des articles.

## Remarques
Pour le bon fonctionnement veuillez commencer avec l'initialisation de la database, en partant de **users.sql**.
Pour le compte administrateur, celui-ci est à faire manuellement dans la database après avoir créer un utilisateur lambda, en lui mettant simplement **1** comme valeur à **role_admin** dans la **table users**.
