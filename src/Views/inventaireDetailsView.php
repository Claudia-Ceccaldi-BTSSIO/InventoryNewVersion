<?php
require_once '../Controllers/authController.php';
require_once '../Models/databaseConnexion.php';
require_once '../Models/materielClass.php';
require_once '../Controllers/session.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$db = DatabaseConnection::getInstance()->getConnection();

// Récupération de tous les matériels
$materiel = new Materiel();
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
$materielData = $materiel->searchMateriel($searchTerm);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaire Détail</title>
    <link rel="stylesheet" href="/assets/css/main.css?v=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #F0FFFF;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .form-group input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .low-stock {
            color: red;
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 5px;
            color: white;
            text-align: center;
            cursor: pointer;
            border: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-modifier {
            background-color: #007BFF;
        }

        .btn-modifier:hover {
            background-color: #0056b3;
        }

        .btn-supprimer {
            background-color: #DC3545;
        }

        .btn-supprimer:hover {
            background-color: #c82333;
        }

        .btn-retour {
            background-color: #28a745;
        }

        .btn-retour:hover {
            background-color: #218838;
        }

        .btn-retour a {
            color: white;
            text-decoration: none;
        }

        .btn-ajout-materiel {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            cursor: pointer;
            border: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-ajout-materiel:hover {
            background-color: #45a049;
        }

        .kanban-board {
            display: none;
            flex-direction: column;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #e6f2ff;
            padding: 20px;
            margin-bottom: 20px;
        }

        form input[type="text"],
        form input[type="number"],
        form select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .hidden-field {
            display: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const showFormButton = document.getElementById('showFormButton');
            const form = document.getElementById('ajoutMaterielForm');

            showFormButton.addEventListener('click', function () {
                form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <h1>Inventaire Détail</h1>

        <!-- Formulaire de recherche -->
        <form action="inventaireDetailsView.php" method="post">
            <input type="text" id="search" name="search" placeholder="Rechercher ..." value="<?= htmlspecialchars($searchTerm) ?>" />
            <input type="submit" value="Recherche" />
        </form>

        <!-- Bouton pour afficher le formulaire d'ajout de matériel -->
        <button id="showFormButton" class="btn-ajout-materiel">Ajout d'un matériel</button>
        <!-- Tableau kanban pour l'ajout de matériel, initialement caché -->
        <div id="ajoutMaterielForm" class="kanban-board">
            <div class="kanban-column">
                <h2>À ajouter</h2>
                <form action="../Models/insertMatInventaire.php" method="post">
                    <input type="text" name="type_materiel" placeholder="Type de matériel" required />
                    <input type="text" name="marque" placeholder="Marque" required />
                    <input type="text" name="modele" placeholder="Modèle" />
                    <input type="text" name="description_materiel" placeholder="Description" />
                    <input type="text" name="etat" placeholder="État" required />
                    <input type="text" name="garantie" placeholder="Garantie" class="hidden-field" />
                    <input type="text" name="fournisseur" placeholder="Fournisseur" class="hidden-field" />
                    <input type="number" name="stock" placeholder="Stock" required />
                    <input type="submit" value="Ajouter" />
                </form>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Type de matériel</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Description</th>
                    <th>État</th>
                    <th class="hidden-field">Garantie</th>
                    <th class="hidden-field">Fournisseur</th>
                    <th>Stock</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($materielData as $row) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['type_materiel']) ?></td>
                        <td><?= htmlspecialchars($row['marque']) ?></td>
                        <td><?= htmlspecialchars($row['modele']) ?></td>
                        <td><?= htmlspecialchars($row['description_materiel']) ?></td>
                        <td><?= htmlspecialchars($row['etat']) ?></td>
                        <td class="hidden-field"><?= htmlspecialchars($row['garantie']) ?></td>
                        <td class="hidden-field"><?= htmlspecialchars($row['fournisseur']) ?></td>
                        <td><?= htmlspecialchars($row['stock']) ?></td>
                        <td>
                            <form action="updateView.php" method="get">
                                <input type="hidden" name="id" value="<?= $row['id_materiel'] ?>">
                                <button type="submit" class="btn btn-modifier">Modifier</button>
                            </form>
                        </td>
                        <td>
                            <form action="../Controllers/delete.php" method="post">
                                <input type="hidden" name="id_materiel" value="<?= $row['id_materiel'] ?>">
                                <button type="submit" class="btn btn-supprimer">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn btn-retour"><a href="parcView.php">Retour</a></button>
    </div>
</body>

</html>
