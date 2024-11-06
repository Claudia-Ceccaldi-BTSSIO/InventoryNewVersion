<?php
require_once '../Controllers/session.php';
require_once '../Models/databaseConnexion.php';
require_once '../Models/materielClass.php';

// Création de l'instance pour accéder à l'inventaire
$materiel = new Materiel();
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
$materielData = $materiel->searchMateriel($searchTerm);

// Récupération de l'ID du matériel pour la modification, si fourni
$idMaterielToEdit = isset($_GET['id']) ? $_GET['id'] : null;
$materielDataToEdit = $idMaterielToEdit ? $materiel->getMaterielById($idMaterielToEdit) : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de l'inventaire</title>
    <link rel="stylesheet" href="/assets/css/main.css?v=1">
    <style>
        /* Styles pour la page */
    </style>
</head>
<body>
    <nav class="burger-menu">
        <ul>
            <li><a href="demandesView.php">Agents CAF47 et Demandes</a></li>
            <li><a href="parcView.php">Inventaire</a></li>
            <li><a href="../Controllers/logout.php">Déconnexion</a></li>
        </ul>
    </nav>

    <main>
        <h1>Inventaire</h1>

        <!-- Formulaire de recherche -->
        <form action="parcView.php" method="post">
            <input type="text" name="search" placeholder="Rechercher dans l'inventaire..." value="<?= htmlspecialchars($searchTerm) ?>" />
            <input type="submit" value="Recherche" />
        </form>

        <!-- Affichage de l'inventaire -->
        <table>
            <thead>
                <tr>
                    <th>Type de matériel</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Description</th>
                    <th>État</th>
                    <th>Stock</th>
                    <th>Actions</th>
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
                        <td><?= htmlspecialchars($row['stock']) ?></td>
                        <td>
                            <a href="parcView.php?id=<?= $row['id_materiel'] ?>">Modifier</a> | 
                            <form action="../Controllers/delete.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $row['id_materiel'] ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Formulaire de modification d'un matériel -->
        <?php if ($materielDataToEdit): ?>
            <h2>Modifier le matériel</h2>
            <form action="../Controllers/update.php" method="post">
                <input type="hidden" name="id" value="<?= $materielDataToEdit['id_materiel'] ?>" />
                <input type="text" name="type_materiel" placeholder="Type de matériel" value="<?= $materielDataToEdit['type_materiel'] ?>" required />
                <input type="text" name="marque" placeholder="Marque" value="<?= $materielDataToEdit['marque'] ?>" required />
                <input type="text" name="modele" placeholder="Modèle" value="<?= $materielDataToEdit['modele'] ?>" />
                <input type="text" name="description_materiel" placeholder="Description" value="<?= $materielDataToEdit['description_materiel'] ?>" />
                <input type="text" name="etat" placeholder="État" value="<?= $materielDataToEdit['etat'] ?>" required />
                <input type="number" name="stock" placeholder="Stock" value="<?= $materielDataToEdit['stock'] ?>" required />
                <button type="submit">Enregistrer les modifications</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
