<?php
require_once '../Controllers/authController.php';
require_once '../Models/databaseConnexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function getRequests($db) {
    $stmt = $db->prepare("SELECT * FROM demandes_emprunt");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getRestitutions($db) {
    $stmt = $db->prepare("SELECT * FROM restitutions");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$db = DatabaseConnection::getInstance()->getConnection();
$requests = getRequests($db);
$restitutions = getRestitutions($db);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggle_validation'])) {
    $requestId = $_POST['request_id'];
    $type = $_POST['type'];
    $currentValidation = $_POST['current_validation'];

    $newValidation = $currentValidation == '0' ? '1' : '0';

    if ($type == 'emprunt') {
        $stmt = $db->prepare("UPDATE demandes_emprunt SET valid = ? WHERE id_demande = ?");
    } else {
        $stmt = $db->prepare("UPDATE restitutions SET valid = ? WHERE id_restitution = ?");
    }
    $stmt->bind_param('ii', $newValidation, $requestId);
    $stmt->execute();
    header("Location: requestsView.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_selected'])) {
    $selectedIds = $_POST['selected_ids'];
    if (!empty($selectedIds)) {
        $ids = implode(',', array_map('intval', $selectedIds));
        $type = $_POST['type'];
        if ($type == 'emprunt') {
            $stmt = $db->prepare("DELETE FROM demandes_emprunt WHERE id_demande IN ($ids)");
        } else {
            $stmt = $db->prepare("DELETE FROM restitutions WHERE id_restitution IN ($ids)");
        }
        $stmt->execute();
    }
    header("Location: requestsView.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue des demandes d'emprunt et de restitution</title>
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
        .kanban-board {
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #e6f2ff;
            padding: 20px;
            margin-bottom: 20px;
            overflow-x: auto;
        }
        .kanban-board h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }
        table, th, td {
            border: 1px solid #ddd;
            word-wrap: break-word;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #5bc0de;
            color: white;
        }
        .btn-retour {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            text-align: center;
            cursor: pointer;
            border: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .btn-retour:hover {
            background-color: #0056b3;
        }
        .btn-validation, .btn-delete {
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-validation.valid {
            background-color: #28a745;
            color: white;
        }
        .btn-validation.not-valid {
            background-color: #6c757d;
            color: white;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }
            .btn-retour {
                font-size: 14px;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="requestsView.php" method="post">
            <div class="kanban-board">
                <h2>Demandes d'emprunt</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Type de matériel</th>
                            <th>Nom de l'agent</th>
                            <th>Fonction</th>
                            <th>Date de demande</th>
                            <th>Réceptionné par</th>
                            <th>Validation</th>
                            <th>Sélectionner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request) : ?>
                            <tr>
                                <td><?= htmlspecialchars($request['identification_materiel'] ?? '') ?></td>
                                <td><?= htmlspecialchars($request['emprunte_par']) ?></td>
                                <td><?= htmlspecialchars($request['fonction']) ?></td>
                                <td><?= htmlspecialchars($request['date_emprunt']) ?></td>
                                <td><?= htmlspecialchars($request['receptionne_par'] ?? '') ?></td>
                                <td>
                                    <form action="requestsView.php" method="post">
                                        <input type="hidden" name="request_id" value="<?= $request['id_demande'] ?>">
                                        <input type="hidden" name="type" value="emprunt">
                                        <input type="hidden" name="current_validation" value="<?= $request['valid'] ?>">
                                        <button type="submit" name="toggle_validation" class="btn-validation <?= $request['valid'] == '1' ? 'valid' : 'not-valid' ?>">
                                            <?= $request['valid'] == '1' ? 'Validé' : 'Pas validé' ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <input type="checkbox" name="selected_ids[]" value="<?= $request['id_demande'] ?>">
                                    <input type="hidden" name="type" value="emprunt">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" name="delete_selected" class="btn-delete">Supprimer les éléments sélectionnés</button>
            </div>
        </form>

        <form action="requestsView.php" method="post">
            <div class="kanban-board">
                <h2>Restitutions</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Type de matériel</th>
                            <th>Nom de l'agent</th>
                            <th>Fonction</th>
                            <th>Date de restitution</th>
                            <th>Réceptionné par</th>
                            <th>Validation</th>
                            <th>Sélectionner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($restitutions as $restitution) : ?>
                            <tr>
                                <td><?= htmlspecialchars($restitution['identification_materiel']) ?></td>
                                <td><?= htmlspecialchars($restitution['restitue_par']) ?></td>
                                <td><?= htmlspecialchars($restitution['fonction']) ?></td>
                                <td><?= htmlspecialchars($restitution['date_restitution']) ?></td>
                                <td><?= htmlspecialchars($restitution['receptionne_par'] ?? '') ?></td>
                                <td>
                                    <form action="requestsView.php" method="post">
                                        <input type="hidden" name="request_id" value="<?= $restitution['id_restitution'] ?>">
                                        <input type="hidden" name="type" value="restitution">
                                        <input type="hidden" name="current_validation" value="<?= $restitution['valid'] ?>">
                                        <button type="submit" name="toggle_validation" class="btn-validation <?= $restitution['valid'] == '1' ? 'valid' : 'not-valid' ?>">
                                            <?= $restitution['valid'] == '1' ? 'Validé' : 'Pas validé' ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <input type="checkbox" name="selected_ids[]" value="<?= $restitution['id_restitution'] ?>">
                                    <input type="hidden" name="type" value="restitution">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" name="delete_selected" class="btn-delete">Supprimer les éléments sélectionnés</button>
            </div>
        </form>

        <a href="parcView.php" class="btn-retour">Retour</a>
    </div>
</body>
</html>
