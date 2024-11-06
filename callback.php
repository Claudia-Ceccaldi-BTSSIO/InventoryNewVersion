<?php
// Assurez-vous que le chemin d'inclusion est correct
require_once('src/Controllers/authController.php'); // Ajustez le chemin si nécessaire

$authController = new AuthController();
$authController->handleCallback();
?>
