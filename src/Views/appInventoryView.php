<?php

require_once '../Models/databaseConnexion.php';

// Création d'une instance de connexion à la base de données
$dbConnection = DatabaseConnection::getInstance()->getConnection();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaire - Application CAF du Lot-et-Garonne</title>
    <link rel="stylesheet" href="../../assets/css/style_inventory.css?v=1">
</head>

<body>
    <header>
        <h1>Inventaire - Application CAF du Lot-et-Garonne</h1>
    </header>

    <div class="container">
        <div class="button-container">
            <a href="loginView.php" class="btn">Connexion</a>
            <a href="registerView.php" class="btn">Inscription</a>
        </div>
        <div class="button-container">
            <a href="../../index.php" class="btn btn-return">Retour à l'accueil</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 CAF du Lot-et-Garonne. Tous droits réservés.</p>
    </footer>
</body>

</html>
