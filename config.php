<?php

class config
{
    private static $pdo = null;

    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=127.0.0.1;dbname=patrimoine;charset=utf8', 
                    'root', // Remplacez par votre utilisateur MySQL si différent
                    '',     // Remplacez par votre mot de passe si nécessaire
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,       // Active les erreurs PDO
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC  // Définit le mode de fetch par défaut
                    ]
                );
            } catch (PDOException $e) {
                // En cas d'erreur, affiche un message clair
                die('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

// Tester la connexion
try {
    $db = config::getConnexion();
   // echo "Connexion réussie à la base de données.";
} catch (Exception $e) {
    echo "Échec de la connexion : " . $e->getMessage();
}
