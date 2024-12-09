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
    // Récupérer et valider les données du formulaire
    $civilisation_id = trim($_POST['civilisation_id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $type = trim($_POST['type']);

    // Validation côté serveur pour le champ "Nom"
    if (!preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/u", $name)) {
        $error_message = "Le champ 'Nom de l'item' ne peut contenir que des lettres, accents, espaces, apostrophes et tirets.";
    }

    // Gestion de l'upload de l'image
    $image_path = '';
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
            $image_path = $upload_dir . $image_new_name;
        } else {
            $error_message = "Erreur lors de l'upload de l'image.";
        }
    } else {
        $error_message = "Veuillez télécharger une image.";
    }

    // Validation des autres champs
    if (!isset($error_message) && !empty($civilisation_id) && !empty($name) && !empty($description) && !empty($location) && !empty($type) && !empty($image_path)) {
        try {
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
            header("Location: index2.php?message=success");
            exit();
        } catch (PDOException $e) {
            $error_message = "Erreur lors de l'ajout de l'élément : " . htmlspecialchars($e->getMessage());
        }
    } else {
        $error_message = $error_message ?? "Veuillez remplir tous les champs.";
    }
}

// Récupérer les civilisations
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
            max-width: 900px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        textarea {
            resize: vertical;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
            text-align: center;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nameField = document.querySelector('input[name="name"]');
            const nameError = document.getElementById('name_error');

            nameField.addEventListener('input', function () {
                const regex = /^[a-zA-ZÀ-ÿ\s'-]+$/;
                if (!regex.test(nameField.value)) {
                    nameError.textContent = "Seules les lettres, accents, espaces, apostrophes et tirets sont autorisés.";
                    nameField.value = nameField.value.replace(/[^a-zA-ZÀ-ÿ\s'-]/g, '');
                } else {
                    nameError.textContent = '';
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Ajouter un Item de Civilisation</h1>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form action="addCivilisationItems.php" method="POST" enctype="multipart/form-data">
            <label for="civilisation_id">Civilisation :</label>
            <select name="civilisation_id">
                <?php foreach ($civilisations as $civilisation): ?>
                    <option value="<?= htmlspecialchars($civilisation['id']) ?>">
                        <?= htmlspecialchars($civilisation['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="name">Nom de l'item :</label>
            <input type="text" name="name">
            <span id="name_error" class="error-message"></span>

            <label for="description">Description :</label>
            <textarea name="description"></textarea>

            <label for="image">Image :</label>
            <input type="file" name="image" accept="image/*">

            <label for="location">Lieu :</label>
            <input type="text" name="location">

            <label for="type">Type :</label>
            <input type="text" name="type">

            <button type="submit">Ajouter</button>
        </form>
    </div>
</body>
</html>
