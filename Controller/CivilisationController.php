<?php
include_once '../View/config.php';

class CivilisationController
{
    // Lister toutes les civilisations
    public function listAll()
    {
        $sql = "SELECT * FROM civilisation";
        $conn = config::getConnexion();

        try {
            $list = $conn->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Ajouter une civilisation
    public function addCivilisation($name, $description)
    {
        $sql = "INSERT INTO civilisation (id, name, description) 
                VALUES (NULL, :name, :description)";
        $conn = config::getConnexion();

        try {
            $query = $conn->prepare($sql);
            $query->execute([
                'name' => $name,
                'description' => $description,
            ]);
            echo "Civilisation ajoutée avec succès";
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Mettre à jour une civilisation
    public function updateCivilisation($id, $name, $description)
    {
        $sql = "UPDATE civilisation SET 
                    name = :name, 
                    description = :description 
                WHERE id = :id";
        $conn = config::getConnexion();

        try {
            $query = $conn->prepare($sql);
            $query->execute([
                'id' => $id,
                'name' => $name,
                'description' => $description,
            ]);

            echo $query->rowCount() . " enregistrements mis à jour avec succès <br>";
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Supprimer une civilisation
    public function deleteCivilisation($id)
    {
        $sql = "DELETE FROM civilisation WHERE id=:id";
        $conn = config::getConnexion();
        $query = $conn->prepare($sql);
        $query->bindValue(':id', $id);

        try {
            $query->execute();
            echo "Civilisation supprimée avec succès";
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Sélectionner une civilisation par ID
    public function getCivilisationById($id)
    {
        $sql = "SELECT * FROM civilisation WHERE id = :id";
        $conn = config::getConnexion();

        try {
            $query = $conn->prepare($sql);
            $query->execute(['id' => $id]);

            $civilisation = $query->fetch();
            return $civilisation;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
?>
