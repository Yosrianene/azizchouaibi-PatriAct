<?php
// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=patrimoine;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Initialisation des variables d'erreur
$nameErr = $descriptionErr = ""; 
$name = $description = ""; // Initialisation des variables

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Validation côté serveur
    if (empty($name)) {
        $nameErr = "Le nom est requis";
    }

    if (empty($description)) {
        $descriptionErr = "La description est requise";
    }

    if (empty($nameErr) && empty($descriptionErr)) {
        try {
            // Préparer la requête pour insérer les données dans la base
            $stmt = $pdo->prepare("INSERT INTO civilisation (name, description) VALUES (:name, :description)");

            // Lier les paramètres
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);

            // Exécuter la requête
            $stmt->execute();

            // Rediriger vers une page de succès après l'insertion
            header("Location: searchCivilisation.php?success=1"); // redirection avec un message de succès
            exit();

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Civilisation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Validation du formulaire avec JavaScript
        function validateForm() {
            let name = document.getElementById("name").value;
            let description = document.getElementById("description").value;
            let valid = true;

            // Validation du nom
            if (name === "") {
                document.getElementById("nameErr").innerHTML = "Le nom est requis";
                valid = false;
            } else {
                document.getElementById("nameErr").innerHTML = "";
            }

            // Validation de la description
            if (description === "") {
                document.getElementById("descriptionErr").innerHTML = "La description est requise";
                valid = false;
            } else {
                document.getElementById("descriptionErr").innerHTML = "";
            }

            return valid;
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Ajouter une Civilisation</h1>

        <!-- Formulaire d'ajout -->
        <form action="addCivilisation.php" method="POST" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="name" class="form-label">Nom de la civilisation</label>
                <input type="text" class="form-control" id="name" name="name" value="">
                <div class="text-danger" id="nameErr"><?php echo isset($nameErr) ? $nameErr : ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                <div class="text-danger" id="descriptionErr"><?php echo isset($descriptionErr) ? $descriptionErr : ''; ?></div>
            </div>

            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
