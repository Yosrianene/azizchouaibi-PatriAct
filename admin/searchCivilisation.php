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

// Pagination
$itemsPerPage = 5; // Nombre d'éléments par page
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Page actuelle (par défaut la page 1)
$startLimit = ($currentPage - 1) * $itemsPerPage; // Calculer la position de départ pour la requête SQL

// Filtre de recherche
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Récupérer le total des civilisations pour calculer le nombre total de pages
$sqlTotal = "SELECT COUNT(*) FROM civilisation WHERE name LIKE :search";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->bindValue(':search', "%$searchTerm%", PDO::PARAM_STR);
$stmtTotal->execute();
$totalCivilisations = $stmtTotal->fetchColumn();
$totalPages = ceil($totalCivilisations / $itemsPerPage); // Calculer le nombre total de pages

// Récupérer les civilisations pour la page actuelle avec filtre de recherche
$sql = "SELECT * FROM civilisation WHERE name LIKE :search LIMIT :start, :limit";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', "%$searchTerm%", PDO::PARAM_STR);
$stmt->bindParam(':start', $startLimit, PDO::PARAM_INT);
$stmt->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$civilisations = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

        <!-- Message de succès après ajout ou mise à jour -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php if ($_GET['success'] == 1): ?>
                    La civilisation a été ajoutée avec succès.
                <?php elseif ($_GET['success'] == 2): ?>
                    La civilisation a été mise à jour avec succès.
                <?php elseif ($_GET['success'] == 3): ?>
                    La civilisation a été supprimée avec succès.
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Formulaire de recherche -->
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Rechercher par nom" value="<?= htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>

        <!-- Lien vers la page d'ajout -->
        <p class="text-end">
            <a href="addCivilisation.php" class="btn btn-primary">Ajouter une nouvelle civilisation</a>
        </p>

        <!-- Tableau des civilisations -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($civilisations as $civilisation): ?>
                    <tr>
                        <td><?= htmlspecialchars($civilisation['name']); ?></td>
                        <td><?= htmlspecialchars($civilisation['description']); ?></td>
                        <td>
                            <a href="editCivilisation.php?id=<?= $civilisation['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="deleteCivilisation.php?id=<?= $civilisation['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette civilisation ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <!-- Page précédente -->
                <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=1" aria-label="Première">
                            <span aria-hidden="true">&laquo;&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage - 1; ?>" aria-label="Précédente">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Pages numérotées -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Page suivante -->
                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage + 1; ?>" aria-label="Suivante">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $totalPages; ?>" aria-label="Dernière">
                            <span aria-hidden="true">&raquo;&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

    </div>
</body>
</html>
