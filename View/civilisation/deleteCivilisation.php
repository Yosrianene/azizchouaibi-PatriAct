<?php
// Connexion à la base de données
$dsn = "mysql:host=localhost;dbname=patrimoine;charset=utf8";
$username = "root";
$password = "";

try {
    // Connexion à la base de données
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si un ID est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Requête pour supprimer la civilisation
        $stmt = $pdo->prepare("DELETE FROM civilisation WHERE id = :id");
        $stmt->execute(['id' => $id]);

        // Vérifier si la suppression a eu lieu
        if ($stmt->rowCount() > 0) {
            // Si la suppression a réussi, rediriger avec un message de succès
            header("Location: searchCivilisation.php?success=3");
            exit();
        } else {
            // Si aucun enregistrement n'a été supprimé (ID invalide ou déjà supprimé)
            header("Location: searchCivilisation.php?error=1");
            exit();
        }
    } catch (PDOException $e) {
        // En cas d'erreur lors de la suppression, rediriger avec un message d'erreur
        header("Location: searchCivilisation.php?error=2");
        exit();
    }
} else {
    // Si aucun ID n'est spécifié dans l'URL, rediriger avec un message d'erreur
    header("Location: searchCivilisation.php?error=3");
    exit();
}
?>
