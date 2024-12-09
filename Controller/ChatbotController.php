<?php
include_once '../View/config.php';

class ChatbotController
{
    // Lister toutes les questions et réponses
    public function listAll()
    {
        $sql = "SELECT * FROM chatbot";
        $conn = config::getConnexion();

        try {
            $list = $conn->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Ajouter une question et une réponse
    public function addEntry($question, $answer)
    {
        $sql = "INSERT INTO chatbot (id, question, answer) 
                VALUES (NULL, :question, :answer)";
        $conn = config::getConnexion();

        try {
            $query = $conn->prepare($sql);
            $query->execute([
                'question' => $question,
                'answer' => $answer,
            ]);
            echo "Entrée ajoutée avec succès";
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Mettre à jour une question ou une réponse
    public function updateEntry($id, $question, $answer)
    {
        $sql = "UPDATE chatbot SET 
                    question = :question, 
                    answer = :answer 
                WHERE id = :id";
        $conn = config::getConnexion();

        try {
            $query = $conn->prepare($sql);
            $query->execute([
                'id' => $id,
                'question' => $question,
                'answer' => $answer,
            ]);

            echo $query->rowCount() . " enregistrements mis à jour avec succès <br>";
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Supprimer une entrée
    public function deleteEntry($id)
    {
        $sql = "DELETE FROM chatbot WHERE id=:id";
        $conn = config::getConnexion();
        $query = $conn->prepare($sql);
        $query->bindValue(':id', $id);

        try {
            $query->execute();
            echo "Entrée supprimée avec succès";
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Récupérer une réponse basée sur une question
    public function getAnswer($question)
    {
        $sql = "SELECT answer FROM chatbot WHERE question = :question";
        $conn = config::getConnexion();

        try {
            $query = $conn->prepare($sql);
            $query->execute(['question' => $question]);

            $answer = $query->fetchColumn();
            return $answer ? $answer : "Désolé, je n'ai pas de réponse pour cette question.";
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Sélectionner une question-réponse par ID
    public function getEntryById($id)
    {
        $sql = "SELECT * FROM chatbot WHERE id = :id";
        $conn = config::getConnexion();

        try {
            $query = $conn->prepare($sql);
            $query->execute(['id' => $id]);

            $entry = $query->fetch();
            return $entry;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
?>
