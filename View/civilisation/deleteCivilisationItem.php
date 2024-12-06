<?php
// Connexion à la base de données avec PDO
$dsn = "mysql:host=localhost;dbname=patrimoine;charset=utf8";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si l'ID de l'élément à supprimer est fourni
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    // Supprimer l'élément de la base de données
    $delete_stmt = $pdo->prepare("DELETE FROM civilisation_items WHERE id = :id");
    $delete_stmt->execute(['id' => $item_id]);

    // Rediriger vers la page d'affichage après suppression
    header("Location: displayCivilisationItems.php");
    exit();
} else {
    // Si l'ID n'est pas fourni, rediriger vers la page d'affichage avec un message d'erreur
    header("Location: displayCivilisationItems.php?error=missing_id");
    exit();
}
?>
