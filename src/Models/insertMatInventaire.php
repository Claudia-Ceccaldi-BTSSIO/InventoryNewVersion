<?php
session_start();
require_once 'databaseConnexion.php';
require_once 'session.php';  // Assure que l'utilisateur est connecté

function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_materiel = clean_input($_POST['type_materiel']);
    $marque = clean_input($_POST['marque']);
    $modele = clean_input($_POST['modele']);
    $description_materiel = clean_input($_POST['description_materiel']);
    $etat = clean_input($_POST['etat']);
    $garantie = clean_input($_POST['garantie']);
    $fournisseur = clean_input($_POST['fournisseur']);
    $stock = clean_input($_POST['stock']);

    try {
        $db = DatabaseConnection::getInstance()->getConnection();
        
        $stmt = $db->prepare("INSERT INTO Materiel (type_materiel, marque, modele, description_materiel, etat, garantie, fournisseur, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Erreur de préparation de la requête : " . $db->error);
        }
        
        $stmt->bind_param("sssssssi", $type_materiel, $marque, $modele, $description_materiel, $etat, $garantie, $fournisseur, $stock);
        
        if ($stmt->execute()) {
            header("Location: ../Views/parcView.php");
            exit;
        } else {
            throw new Exception("Erreur : " . $stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Erreur : " . $e->getMessage();
        header("Location: ../Views/parcView.php");
        exit;
    }
}
?>
