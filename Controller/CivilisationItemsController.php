<?php
include_once '../View/config.php';

class CivilisationItemsController
{
    private $db;

    public function __construct()
    {
        $this->db = config::getConnexion();
    }

    // Lister tous les éléments d'une civilisation
    public function listAllItems($civilisation_id = null)
    {
        if ($civilisation_id) {
            $sql = "SELECT * FROM civilisation_items WHERE civilisation_id = :civilisation_id";
        } else {
            $sql = "SELECT * FROM civilisation_items";
        }

        try {
            $query = $this->db->prepare($sql);
            if ($civilisation_id) {
                $query->execute(['civilisation_id' => $civilisation_id]);
            } else {
                $query->execute();
            }
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Ajouter un élément pour une civilisation
    public function addCivilisationItem($civilisation_id, $type, $name, $description, $image, $location)
    {
        $sql = "INSERT INTO civilisation_items (id, civilisation_id, type, name, description, image, location) 
                VALUES (NULL, :civilisation_id, :type, :name, :description, :image, :location)";

        try {
            $query = $this->db->prepare($sql);
            $query->execute([
                'civilisation_id' => $civilisation_id,
                'type' => $type,
                'name' => $name,
                'description' => $description,
                'image' => $image,
                'location' => $location,
            ]);
            echo "Élément ajouté avec succès";
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Mettre à jour un élément existant
    public function updateCivilisationItem($id, $civilisation_id, $type, $name, $description, $image, $location)
    {
        $sql = "UPDATE civilisation_items SET 
                    civilisation_id = :civilisation_id,
                    type = :type,
                    name = :name,
                    description = :description,
                    image = :image,
                    location = :location 
                WHERE id = :id";

        try {
            $query = $this->db->prepare($sql);
            $query->execute([
                'id' => $id,
                'civilisation_id' => $civilisation_id,
                'type' => $type,
                'name' => $name,
                'description' => $description,
                'image' => $image,
                'location' => $location,
            ]);
            echo $query->rowCount() . " enregistrements mis à jour avec succès <br>";
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Supprimer un élément par son ID
    public function deleteCivilisationItem($id)
    {
        $sql = "DELETE FROM civilisation_items WHERE id = :id";

        try {
            $query = $this->db->prepare($sql);
            $query->execute(['id' => $id]);
            echo "Élément supprimé avec succès";
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Recherche d'éléments par nom ou type
    public function searchCivilisationItem($search) {
        $query = $this->db->prepare("SELECT * FROM civilisation_items WHERE name LIKE :search OR type LIKE :search");
        $query->bindParam(':search', $search, PDO::PARAM_STR);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Afficher les éléments d'une civilisation donnée
    public function DisplayCivilisationItemByCivilisation($civilisationNameInput)
    {
        // Requête pour obtenir l'ID et la description de la civilisation
        $query1 = $this->db->prepare("SELECT id, description FROM civilisation WHERE name LIKE :civilisationNameInput");
        $query1->bindValue(':civilisationNameInput', '%' . $civilisationNameInput . '%', PDO::PARAM_STR);
        $query1->execute();
        $civilisation = $query1->fetch(PDO::FETCH_ASSOC);
    
        if ($civilisation) {
            // Obtenez les éléments de la civilisation à partir de l'ID récupéré
            $query2 = $this->db->prepare("SELECT * FROM civilisation_items WHERE civilisation_id = :civilisation_id");
            $query2->bindParam(':civilisation_id', $civilisation['id'], PDO::PARAM_INT);
            $query2->execute();
            $items = $query2->fetchAll(PDO::FETCH_ASSOC);
    
            // Combiner la description de la civilisation avec ses éléments
            return [
                'description' => $civilisation['description'],
                'items' => $items
            ];
        }
    
        return null; // Retourner null si la civilisation n'est pas trouvée
    }
}
?>
