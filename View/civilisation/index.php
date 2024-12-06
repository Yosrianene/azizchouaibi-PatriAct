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

// Récupérer le total des questions pour calculer le nombre total de pages
$sqlTotal = "SELECT COUNT(*) FROM chatbot";
$stmtTotal = $pdo->query($sqlTotal);
$totalQuestions = $stmtTotal->fetchColumn();
$totalPages = ceil($totalQuestions / $itemsPerPage); // Calculer le nombre total de pages

// Récupérer les questions et réponses pour la page actuelle
$sql = "SELECT * FROM chatbot LIMIT :start, :limit";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':start', $startLimit, PDO::PARAM_INT);
$stmt->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$chatbot = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Questions et Réponses (Chatbot)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestion des Questions et Réponses du Chatbot</h1>

        <!-- Message de succès après ajout ou mise à jour -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php if ($_GET['success'] == 1): ?>
                    La question et la réponse ont été ajoutées avec succès.
                <?php elseif ($_GET['success'] == 2): ?>
                    La question et la réponse ont été mises à jour avec succès.
                <?php elseif ($_GET['success'] == 3): ?>
                    La question et la réponse ont été supprimées avec succès.
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Lien vers la page d'ajout -->
        <p class="text-end">
            <a href="addQuestion.php" class="btn btn-primary">Ajouter une nouvelle question et réponse</a>
        </p>

        <!-- Tableau des questions et réponses -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Réponse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($chatbot as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['question']); ?></td>
                        <td><?= htmlspecialchars($item['answer']); ?></td>
                        <td>
                            <a href="editQuestion.php?id=<?= $item['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="deleteQuestion.php?id=<?= $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question et sa réponse ?')">Supprimer</a>
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
