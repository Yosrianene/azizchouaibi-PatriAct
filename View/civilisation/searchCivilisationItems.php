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

// Démarrer la session
session_start();

// Vérifier si un message est passé dans la session
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Effacer le message après l'avoir affiché
} elseif (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']); // Effacer le message après l'avoir affiché
}

// Vérifier si une action de suppression a été demandée
if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    $sqlDelete = "DELETE FROM civilisation_items WHERE id = :id";
    $stmtDelete = $pdo->prepare($sqlDelete);
    
    if ($stmtDelete->execute([':id' => $deleteId])) {
        $_SESSION['success_message'] = "L'élément a été supprimé avec succès.";
    } else {
        $_SESSION['error_message'] = "Une erreur s'est produite lors de la suppression de l'élément.";
    }
    
    header("Location: searchCivilisationItems.php");
    exit;
}

// Pagination
$itemsPerPage = 10;  // Nombre d'éléments par page
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Page actuelle, par défaut la première page
$startLimit = ($currentPage - 1) * $itemsPerPage;  // Calculer le début de la requête LIMIT

// Récupérer le total des items pour calculer le nombre total de pages
$sqlTotalItems = "SELECT COUNT(*) FROM civilisation_items";
$stmtTotal = $pdo->query($sqlTotalItems);
$totalItems = $stmtTotal->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// Récupérer les items de civilisation avec pagination
$sql = "SELECT * FROM civilisation_items LIMIT :start, :limit";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':start', $startLimit, PDO::PARAM_INT);
$stmt->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Civilisation Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .alert {
            margin-top: 20px;
        }
        /* Make the table more compact */
        .table-sm th, .table-sm td {
            padding: 0.3rem; /* Reducing padding */
        }

        /* Reduce the image size to make it more compact */
        .table img {
            width: 50px; /* Resize the image */
            height: auto;
        }

        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Civilisation Items</h1>
        <div class="text-end mb-4">
            <a href="addCivilisationItems.php" class="btn btn-primary">Ajouter un élément</a>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover">
                <thead class="table-dark">
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
                            <td><img src="<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>"></td>
                            <td><?= htmlspecialchars($item['location']); ?></td>
                            <td><?= htmlspecialchars($item['type']); ?></td>
                            <td>
                                <a href="editCivilisationItem.php?id=<?= $item['id']; ?>" class="btn btn-primary btn-sm">Modifier</a>
                                <a href="searchCivilisationItems.php?delete=<?= $item['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');" class="btn btn-danger btn-sm">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php if ($currentPage > 1): ?>
                    <li class="page-item"><a class="page-link" href="searchCivilisationItems.php?page=1">1</a></li>
                    <li class="page-item"><a class="page-link" href="searchCivilisationItems.php?page=<?= $currentPage - 1; ?>">Précédent</a></li>
                <?php endif; ?>

                <!-- Pages numérotées -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : ''; ?>"><a class="page-link" href="searchCivilisationItems.php?page=<?= $i; ?>"><?= $i; ?></a></li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item"><a class="page-link" href="searchCivilisationItems.php?page=<?= $currentPage + 1; ?>">Suivant</a></li>
                    <li class="page-item"><a class="page-link" href="searchCivilisationItems.php?page=<?= $totalPages; ?>"><?= $totalPages; ?></a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
