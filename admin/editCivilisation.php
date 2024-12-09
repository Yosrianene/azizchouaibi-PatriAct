<?php
// Connexion à la base de données
$dsn = "mysql:host=localhost;dbname=patrimoine;charset=utf8";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les données de la civilisation à modifier
    $stmt = $pdo->prepare("SELECT * FROM civilisation WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $civilisation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$civilisation) {
        die("Civilisation introuvable.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    if (empty($name) || empty($description)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        // Mise à jour de la civilisation dans la base de données
        $stmt = $pdo->prepare("UPDATE civilisation SET name = :name, description = :description WHERE id = :id");
        $stmt->execute(['name' => $name, 'description' => $description, 'id' => $id]);

        // Redirection vers la page de gestion avec l'ID de la civilisation modifiée
        header("Location: index.php?id=" . $id . "&success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Civilisation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
        // Fonction de validation
        function validateForm(event) {
            let isValid = true;

            // Réinitialiser les messages d'erreur
            document.getElementById("name-error").innerHTML = "";
            document.getElementById("description-error").innerHTML = "";
            document.getElementById("name").classList.remove("is-invalid");
            document.getElementById("description").classList.remove("is-invalid");

            // Récupérer les valeurs du formulaire
            let name = document.getElementById("name").value.trim();
            let description = document.getElementById("description").value.trim();

            // Vérification du champ nom
            const namePattern = /^[a-zA-Z ]+$/; // Modification ici pour validation de lettres uniquement
            if (name === "") {
                isValid = false;
                document.getElementById("name").classList.add("is-invalid");
                document.getElementById("name-error").innerHTML = "Le nom est obligatoire.";
            } else if (!namePattern.test(name)) {
                isValid = false;
                document.getElementById("name").classList.add("is-invalid");
                document.getElementById("name-error").innerHTML = "Le nom ne doit contenir que des lettres.";
            }

            // Vérification du champ description
            if (description === "") {
                isValid = false;
                document.getElementById("description").classList.add("is-invalid");
                document.getElementById("description-error").innerHTML = "La description est obligatoire.";
            }

            // Si le formulaire n'est pas valide, empêcher l'envoi
            if (!isValid) {
                event.preventDefault();
            }

            return isValid;
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Modifier une Civilisation</h1>

        <form action="editCivilisation.php?id=<?= $civilisation['id']; ?>" method="POST" onsubmit="return validateForm(event)">
            <div class="mb-3">
                <label for="name" class="form-label">Nom de la civilisation</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($civilisation['name']); ?>">
                <div id="name-error" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($civilisation['description']); ?></textarea>
                <div id="description-error" class="text-danger"></div>
            </div>

            <button type="submit" class="btn btn-warning">Mettre à jour</button>
        </form>
    </div>
</body>
</html>
