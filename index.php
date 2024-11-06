<?php
require_once './src/Models/databaseConnexion.php';
require_once './src/Controllers/authController.php';

// Création d'une instance de connexion à la base de données
$dbConnection = DatabaseConnection::getInstance()->getConnection();
$authController = new AuthController();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application CAF du Lot-et-Garonne</title>
    <link rel="stylesheet" href="assets/css/style_index.css?v=1">
    <style>
                body {
            background-image: url('assets/images/femmepc.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
        }

        @media (max-width: 768px) {
            body {
                background-position: top;
            }
        }

        .container {
            text-align: center;
            margin-top: 20%;
        }

        .hello h1 {
            font-size: 2.5em;
            color: #3f51b5;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .button-container {
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            font-size: 1.5em;
            color: #fff;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .hello h1 {
                font-size: 1.8em;
            }

            .btn {
                padding: 10px 20px;
                font-size: 1.2em;
            }
        }

        @media (max-width: 480px) {
            .hello h1 {
                font-size: 1.5em;
            }

            .btn {
                padding: 8px 16px;
                font-size: 1em;
            }
        }

        footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
        }

        .welcome-container {
            padding: 15px 30px;
            font-size: 1.5em;
            color: #fff;
            background-color: #28a745;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .welcome-container:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            .welcome-container {
                padding: 10px 20px;
                font-size: 1.2em;
            }
        }

        @media (max-width: 480px) {
            .welcome-container {
                padding: 8px 16px;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hello">
            <h1>Bienvenue sur l'application de la CAF<br>du Lot-et-Garonne</h1>
        </div>

        <div class="button-container">
            <a href="src/Views/parcView.php" class="btn">Inventaire</a>
            <a href="#" onclick="window.location.href='<?php echo $authController->getLoginUrl(); ?>';" class="btn">Connexion</a>
        </div>
    </div>
</body>

<footer>
    <button class="welcome-container btn-zoom" onclick="window.location.href='./src/Views/demandesView.php'">
        <h2>
            Demandes de matériels informatique
        </h2>
    </button>
</footer>

</html>
