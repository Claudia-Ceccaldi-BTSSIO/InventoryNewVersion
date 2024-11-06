<?php

require_once 'databaseConnexion.php';

class Materiel {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    // Méthode pour rechercher du matériel
    public function searchMateriel($searchTerm) {
        try {
            if (!empty($searchTerm)) {
                $searchTerm = "%{$searchTerm}%";
                $stmt = $this->db->prepare("SELECT * FROM Materiel WHERE type_materiel LIKE ? OR marque LIKE ? OR modele LIKE ? OR description_materiel LIKE ?");
                $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
            } else {
                $stmt = $this->db->prepare("SELECT * FROM Materiel");
            }

            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur lors de la recherche de matériel : " . $e->getMessage());
            return [];
        }
    }

    // Méthode pour récupérer un matériel par ID
    public function getMaterielById($idMateriel) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Materiel WHERE id_materiel = ?");
            $stmt->bind_param("i", $idMateriel);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération du matériel : " . $e->getMessage());
            return null;
        }
    }

    // Méthode pour mettre à jour un matériel
    public function updateMateriel($idMateriel, $type_materiel, $marque, $modele, $description_materiel, $etat, $garantie, $fournisseur, $stock) {
        try {
            $stmt = $this->db->prepare("UPDATE Materiel SET type_materiel = ?, marque = ?, modele = ?, description_materiel = ?, etat = ?, garantie = ?, fournisseur = ?, stock = ? WHERE id_materiel = ?");
            $stmt->bind_param("ssssssssi", $type_materiel, $marque, $modele, $description_materiel, $etat, $garantie, $fournisseur, $stock, $idMateriel);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du matériel : " . $e->getMessage());
        }
    }

    // Méthode pour supprimer un matériel
    public function deleteMateriel($idMateriel) {
        try {
            $stmt = $this->db->prepare("DELETE FROM Materiel WHERE id_materiel = ?");
            $stmt->bind_param("i", $idMateriel);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression du matériel : " . $e->getMessage());
        }
    }
}
?>
