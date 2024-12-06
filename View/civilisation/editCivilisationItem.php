<?php
session_start(); // Démarrer la session

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

// Récupérer l'ID de l'item à modifier
$item_id = $_GET['id'];

// Récupérer les détails de l'item pour la modification
$stmt = $pdo->prepare("SELECT * FROM civilisation_items WHERE id = :id");
$stmt->execute(['id' => $item_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// Si l'item n'existe pas
if (!$item) {
    die("Item non trouvé.");
}

// Mettre à jour l'item lorsque le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $type = trim($_POST['type']);
    $image_path = $item['image']; // Conserver l'image actuelle si aucune nouvelle image n'est téléchargée

    // Gérer l'upload de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_new_name = uniqid('item_', true) . '.' . $image_extension;
        $upload_dir = 'uploads/images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        if (move_uploaded_file($image_tmp_name, $upload_dir . $image_new_name)) {
            $image_path = $upload_dir . $image_new_name; // Nouveau chemin d'image
        } else {
            $_SESSION['error_message'] = "Erreur lors de l'upload de l'image.";
        }
    }

    // Mettre à jour l'item dans la base de données
    $update_stmt = $pdo->prepare("UPDATE civilisation_items SET name = :name, description = :description, image = :image, location = :location, type = :type WHERE id = :id");
    $update_stmt->execute([
        'name' => $name,
        'description' => $description,
        'image' => $image_path,
        'location' => $location,
        'type' => $type,
        'id' => $item_id
    ]);

    // Message de succès et redirection vers searchCivilisationItems.php
    $_SESSION['success_message'] = "Item modifié avec succès.";
    header("Location: searchCivilisationItems.php?message=success");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Item de Civilisation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Validation du formulaire avec JavaScript
        function validateForm(event) {
            let isValid = true;
            const fields = ['name', 'description', 'location', 'type'];
            
            // Réinitialiser les messages d'erreur et les bordures de champ
            fields.forEach(field => {
                let errorMessage = document.getElementById(field + '_error');
                errorMessage.style.display = 'none';
                let input = document.getElementById(field);
                input.style.borderColor = '';  // Réinitialiser la bordure
            });

            // Vérifier chaque champ
            fields.forEach(field => {
                let input = document.getElementById(field);
                let errorMessage = document.getElementById(field + '_error');
                
                // Si le champ est vide
                if (input.value.trim() === '') {
                    errorMessage.style.display = 'block';  // Afficher le message d'erreur sous le champ
                    errorMessage.textContent = `${field.charAt(0).toUpperCase() + field.slice(1)} est requis.`;  // Message d'erreur
                    input.style.borderColor = 'red';  // Mettre la bordure en rouge
                    isValid = false;
                }
            });

            // Validation de l'image (facultative)
            let imageInput = document.getElementById('image');
            let imageError = document.getElementById('image_error');
            if (imageInput.files[0] && imageInput.files[0].size > 1048576) { // Limiter la taille à 1MB
                imageError.style.display = 'block';
                imageError.textContent = "L'image ne doit pas dépasser 1 Mo.";
                imageInput.style.borderColor = 'red';
                isValid = false;
            } else {
                imageError.style.display = 'none';
                imageInput.style.borderColor = '';
            }

            return isValid; // Si tous les champs sont valides, le formulaire sera envoyé
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Modifier Item de Civilisation</h1>

        <!-- Afficher les messages d'erreur -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" onsubmit="return validateForm(event)">
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($item['name']); ?>">
                <div id="name_error" style="color: red; display: none; margin-top: 5px;"></div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control"><?= htmlspecialchars($item['description']); ?></textarea>
                <div id="description_error" style="color: red; display: none; margin-top: 5px;"></div>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" id="image" name="image" class="form-control">
                <img src="<?= htmlspecialchars($item['image']); ?>" alt="Item image" style="max-width: 150px; margin-top: 10px;">
                <div id="image_error" style="color: red; display: none; margin-top: 5px;"></div>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Localisation</label>
                <input type="text" id="location" name="location" class="form-control" value="<?= htmlspecialchars($item['location']); ?>">
                <div id="location_error" style="color: red; display: none; margin-top: 5px;"></div>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <input type="text" id="type" name="type" class="form-control" value="<?= htmlspecialchars($item['type']); ?>">
                <div id="type_error" style="color: red; display: none; margin-top: 5px;"></div>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</body>
</html>
