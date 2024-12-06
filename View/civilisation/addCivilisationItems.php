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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérification des champs du formulaire (en excluant l'ID)
    $civilisation_id = trim($_POST['civilisation_id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $type = trim($_POST['type']);

    // Gestion de l'upload de l'image
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_new_name = uniqid('item_', true) . '.' . $image_extension; // Générer un nom unique pour l'image
        $upload_dir = 'uploads/images/'; // Dossier où l'image sera sauvegardée

        // Créer le dossier s'il n'existe pas
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Déplacer l'image téléchargée vers le répertoire
        if (move_uploaded_file($image_tmp_name, $upload_dir . $image_new_name)) {
            $image_path = $upload_dir . $image_new_name; // Chemin relatif de l'image
        } else {
            $error_message = "Erreur lors de l'upload de l'image.";
        }
    } else {
        $error_message = "Veuillez télécharger une image.";
    }

    // Validation des champs
    if (!empty($civilisation_id) && !empty($name) && !empty($description) && !empty($location) && !empty($type) && !empty($image_path)) {
        try {
            // Préparer la requête pour insérer l'élément (sans l'ID)
            $stmt = $pdo->prepare("INSERT INTO civilisation_items (civilisation_id, name, description, image, location, type) 
                                   VALUES (:civilisation_id, :name, :description, :image, :location, :type)");
            $stmt->execute([ 
                'civilisation_id' => $civilisation_id,
                'name' => $name,
                'description' => $description,
                'image' => $image_path,
                'location' => $location,
                'type' => $type
            ]);

            // Rediriger vers la page de recherche avec un message de succès dans l'URL
            header("Location: searchCivilisationItems.php?message=success");
            exit();
        } catch (PDOException $e) {
            $error_message = "Erreur lors de l'ajout de l'élément : " . htmlspecialchars($e->getMessage());
        }
    } else {
        $error_message = "Veuillez remplir tous les champs.";
    }
}

// Récupérer les civilisations pour le champ "civilisation_id"
$sql = "SELECT id, name FROM civilisation";
$stmt = $pdo->query($sql);
$civilisations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Item de Civilisation</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        label {
            font-weight: 500;
            color: #444;
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="file"] {
            padding: 5px;
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        .error-message {
            color: red;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un Item de Civilisation</h1>

        <!-- Message de succès ou erreur -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
            <p class="success-message">Item ajouté avec succès ! Vous êtes redirigé vers la page de recherche.</p>
        <?php elseif (isset($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form name="civilisationForm" action="addCivilisationItems.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="civilisation_id">ID de la civilisation :</label>
                <select name="civilisation_id">
                    <?php foreach ($civilisations as $civilisation): ?>
                        <option value="<?= htmlspecialchars($civilisation['id']); ?>">
                            <?= htmlspecialchars($civilisation['name']); ?> (ID: <?= htmlspecialchars($civilisation['id']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <span id="civilisation_id_error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="name">Nom de l'item :</label>
                <input type="text" name="name">
                <span id="name_error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea name="description"></textarea>
                <span id="description_error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="image">Image (upload depuis votre PC) :</label>
                <input type="file" name="image" accept="image/*">
                <span id="image_error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="location">Lieu :</label>
                <input type="text" name="location">
                <span id="location_error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="type">Type :</label>
                <input type="text" name="type">
                <span id="type_error" class="error-message"></span>
            </div>

            <button type="submit">Ajouter</button>
        </form>
    </div>
</body>
</html>
