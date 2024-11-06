<?php
// Vérifier si une session PHP est déjà active, sinon en démarrer une
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté via Microsoft Entra ID
if (!isset($_SESSION['id_user'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers Microsoft Entra ID
    require_once('authController.php');
    $authController = new AuthController();
    $authController->redirectToEntraID();
    exit();
}

// Si l'utilisateur est connecté, vérifier si la page demandée est autorisée sans authentification
$currentPage = basename($_SERVER['PHP_SELF']);
if (!in_array($currentPage, ['loginView.php', 'index.php', 'demandesView.php', 'logoutView.php'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas sur une page autorisée
    $authController->redirectToEntraID();
    exit();
}
?>
