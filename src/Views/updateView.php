<?php
require_once '../Controllers/authController.php';
require_once '../Models/databaseConnexion.php';
require_once '../Models/materielClass.php';
require_once '../Controllers/session.php';

// Récupération de l'ID du matériel
$idMateriel = $_GET['id'];

$materiel = new Materiel();
$materielData = $materiel->getMaterielById($idMateriel);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type_materiel = $_POST['type_materiel'];
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $description_materiel = $_POST['description_materiel'];
    $etat = $_POST['etat'];
    $garantie = $_POST['garantie'];
    $fournisseur = $_POST['fournisseur'];
    $stock = $_POST['stock'];

    $materiel->updateMateriel($idMateriel, $type_materiel, $marque, $modele, $description_materiel, $etat, $garantie, $fournisseur, $stock);
    header("Location: parcView.php");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Matériel</title>
    <link rel="stylesheet" href="/assets/css/main.css?v=1">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; display: flex; background-color: #F0FFFF; }
        .container { width: 80%; margin: auto; padding: 20px; }
        form input[type="text"], form input[type="number"], form select { width: 100%; padding: 10px; margin: 5px 0 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        form input[type="submit"] { background-color: #4CAF50; color: white; border: none; border-radius: 5px; padding: 10px 20px; cursor: pointer; transition: background-color 0.3s ease; }
        form input[type="submit"]:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifier Matériel</h2>
        <form action="" method="post">
            <input type="text" name="type_materiel" placeholder="Type de matériel" value="<?= $materielData['type_materiel'] ?>" required />
            <input type="text" name="marque" placeholder="Marque" value="<?= $materielData['marque'] ?>" required />
            <input type="text" name="modele" placeholder="Modèle" value="<?= $materielData['modele'] ?>" />
            <input type="text" name="description_materiel" placeholder="Description" value="<?= $materielData['description_materiel'] ?>" />
            <input type="text" name="etat" placeholder="État" value="<?= $materielData['etat'] ?>" required />
            <select name="garantie" required>
                <option value="">Produit sous garantie</option>
                <option value="Oui" <?= $materielData['garantie'] == 'Oui' ? 'selected' : '' ?>>OUI</option>
                <option value="Non" <?= $materielData['garantie'] == 'Non' ? 'selected' : '' ?>>NON</option>
                <option value="Inconnu" <?= $materielData['garantie'] == 'Inconnu' ? 'selected' : '' ?>>Inc</option>
            </select>
            <input type="text" name="fournisseur" placeholder="Fournisseur" value="<?= $materielData['fournisseur'] ?>" />
            <input type="number" name="stock" placeholder="Stock" value="<?= $materielData['stock'] ?>" required />
            <input type="submit" value="Modifier" />
        </form>
    </div>
</body>
</html>
