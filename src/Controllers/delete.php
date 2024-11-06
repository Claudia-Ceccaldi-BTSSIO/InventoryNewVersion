<?php
require_once 'session.php';  // Assure l'authentification
require_once '../Models/databaseConnexion.php';
require_once '../Models/materielClass.php';

// Récupération de l'ID du matériel
$idMateriel = $_GET['id'];

$materiel = new Materiel();
$materiel->deleteMateriel($idMateriel);

header("Location: ../Views/parcView.php");
exit();
?>
