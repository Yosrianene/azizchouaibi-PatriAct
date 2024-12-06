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

// Récupérer la recherche et afficher les civilisations correspondantes
$search = $_GET['search'] ?? '';
$civilisations = [];

if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM civilisation WHERE name LIKE :search");
    $stmt->execute(['search' => "%$search%"]);
    $civilisations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si la recherche est vide, afficher toutes les civilisations
    $stmt = $pdo->query("SELECT * FROM civilisation");
    $civilisations = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Civilisations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestion des Civilisations</h1>

        <!-- Formulaire de recherche -->
        <form method="get" class="mb-4">
            <div class="input-group">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control" 
                    placeholder="Rechercher une civilisation" 
                    value="<?= htmlspecialchars($search); ?>"
                >
                <button class="btn btn-primary" type="submit">Rechercher</button>
            </div>
        </form>

       <!-- Résultats de la recherche -->
<div class="mt-4">
    <?php if (!empty($civilisations)): ?>
        <div class="row">
            <?php foreach ($civilisations as $civilisation): ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($civilisation['name']); ?></h5>
                            <p class="card-text"><?= htmlspecialchars($civilisation['description']); ?></p>
                            <div class="d-flex justify-content-between">
                                <!-- Bouton Modifier -->
                                <a href="../View/civilisation/editCivilisation.php?id=<?= $civilisation['id']; ?>&search=<?= urlencode($search); ?>" class="btn btn-warning btn-sm">Modifier</a>
                                
                                <!-- Bouton Supprimer -->
                                <a href="../View/civilisation/deleteCivilisation.php?id=<?= $civilisation['id']; ?>&search=<?= urlencode($search); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette civilisation ?')">Supprimer</a>
                                
                                <!-- Bouton Voir éléments -->
                                <a href="../View/civilisation/searchCivilisationItems.php?civilisation_id=<?= $civilisation['id']; ?>" class="btn btn-info btn-sm">Voir éléments</a>



                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif (!empty($search)): ?>
        <p class="text-muted text-center">Aucune civilisation trouvée correspondant à votre recherche.</p>
    <?php else: ?>
        <p class="text-muted text-center">Utilisez la barre de recherche pour trouver une civilisation.</p>
    <?php endif; ?>
</div>


        <!-- Lien vers la page d'ajout -->
        <p class="text-end mt-4">
            <a href="../View/civilisation/addCivilisation.php" class="btn btn-primary">Ajouter une nouvelle civilisation</a>
        </p>
    </div>
</body>
</html>
