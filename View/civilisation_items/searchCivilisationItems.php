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

// Récupérer les paramètres

$civilisation_id = isset($_GET['civilisation_id']) ? (int)$_GET['civilisation_id'] : null;


// Vérifiez si l'ID de civilisation est fourni
if ($civilisation_id) {
    // Requête pour récupérer les éléments de la civilisation spécifique
    $sql = "SELECT * FROM civilisation_items WHERE civilisation_id = :civilisation_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':civilisation_id' => $civilisation_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si aucun ID de civilisation, affichez un message d'erreur
    $results = [];
    $error = "Aucune civilisation sélectionnée.";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Éléments de Civilisation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Éléments de Civilisation</h1>

        <!-- Résultats -->
        <?php if (!empty($results)): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Lieu</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']); ?></td>
                                <td><?= htmlspecialchars($item['description']); ?></td>
                                <td><img src="<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>" width="50"></td>
                                <td><?= htmlspecialchars($item['location']); ?></td>
                                <td><?= htmlspecialchars($item['type']); ?></td>
                                <td>
                                    <a href="editCivilisationItem.php?id=<?= $item['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                                    <a href="searchCivilisationItems.php?delete=<?= $item['id']; ?>&civilisation_id=<?= $civilisation_id; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');" class="btn btn-danger btn-sm">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif (isset($error)): ?>
            <p class="text-muted text-center"><?= $error; ?></p>
        <?php else: ?>
            <p class="text-muted text-center">Aucun élément trouvé pour cette civilisation.</p>
        <?php endif; ?>
    </div>
</body>
</html>
