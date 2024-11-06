<?php
session_start();
require_once '../Controllers/session.php';  // Vérifie que l'utilisateur est authentifié
$user = isset($_SESSION["user"]) ? $_SESSION["user"] : null;

if (!$user) {
    die("User not authenticated.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demandes d'emprunt et de restitution</title>
    <link rel="stylesheet" href="/assets/css/main.css?v=1">
    <style>
        /* Style global ici */
    </style>
</head>

<body>
    <div class="container">
        <!-- Formulaire de demande de matériel nomade -->
        <button class="btn-toggle" onclick="toggleForm('form1')">
            Demande de mise à disposition de matériel nomade
            <img src="../../assets/images/matnomade.png" alt="Icone Formulaire 1">
        </button>
        <div id="form1" class="kanban-board">
            <form action="../Models/notificationService.php" method="post">
                <input type="hidden" name="subject" value="Nouvelle demande d'emprunt">
                <input type="hidden" name="type_demande" value="Non Permanent">
                <input type="text" name="identification_materiel" placeholder="Type de Matériel" required>
                <input type="text" name="emprunte_par" value="<?= htmlspecialchars($user['fullName']) ?>" required>
                <input type="text" name="fonction" value="<?= htmlspecialchars($user['function']) ?>" required>
                <input type="date" name="date" required>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                <input type="text" name="id_agent" value="<?= htmlspecialchars($user['agentId']) ?>" required>
                <button type="submit" class="btn-toggle">Envoyer</button>
            </form>
        </div>

        <!-- Formulaire de demande de matériel nomade permanent -->
        <button class="btn-toggle" onclick="toggleForm('form2')">
            Demande de mise à disposition de matériel nomade PERMANENT
            <img src="../../assets/images/matnomadePerm.png" alt="Icone Formulaire 2">
        </button>
        <div id="form2" class="kanban-board">
            <form action="../Models/notificationService.php" method="post">
                <input type="hidden" name="subject" value="Nouvelle demande de matériel permanent">
                <input type="hidden" name="type_demande" value="PERMANENT">
                <input type="text" name="identification_materiel" placeholder="Type de Matériel" required>
                <input type="text" name="emprunte_par" value="<?= htmlspecialchars($user['fullName']) ?>" required>
                <input type="text" name="fonction" value="<?= htmlspecialchars($user['function']) ?>" required>
                <input type="date" name="date" required>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                <input type="text" name="id_agent" value="<?= htmlspecialchars($user['agentId']) ?>" required>
                <button type="submit" class="btn-toggle">Envoyer</button>
            </form>
        </div>

        <!-- Formulaire de restitution de matériel -->
        <button class="btn-toggle" onclick="toggleForm('form3')">
            Restitution de matériel
            <img src="../../assets/images/restitmat.png" alt="Icone Formulaire 3">
        </button>
        <div id="form3" class="kanban-board">
            <form action="../Models/notificationService.php" method="post">
                <input type="hidden" name="subject" value="Nouvelle restitution de matériel">
                <input type="hidden" name="type_demande" value="Restitution">
                <input type="text" name="identification_materiel" placeholder="Type de Matériel" required>
                <input type="text" name="emprunte_par" value="<?= htmlspecialchars($user['fullName']) ?>" required>
                <input type="text" name="fonction" value="<?= htmlspecialchars($user['function']) ?>" required>
                <input type="date" name="date" required>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                <input type="text" name="id_agent" value="<?= htmlspecialchars($user['agentId']) ?>" required>
                <button type="submit" class="btn-toggle">Envoyer</button>
            </form>
        </div>
    </div>

    <script>
        function toggleForm(formId) {
            var form = document.getElementById(formId);
            form.style.display = form.style.display === 'none' || form.style.display === '' ? 'flex' : 'none';
        }
    </script>
</body>
</html>
