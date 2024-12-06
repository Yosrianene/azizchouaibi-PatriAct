<?php
require_once '../../patriAct-main/Controller/CivilisationController.php';
require_once '../../patriAct-main/Controller/CivilisationItemsController.php';
require_once '../../patriAct-main/view/config.php';



// Fetch all posts
$civController = new CivilisationController();
$civs = $civController->listAll();

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Civilisations</title>

    <!-- Google Fonts & Vendor CSS Files -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600|Nunito:300,400,600|Poppins:300,400,500" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">NiceAdmin</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
    </header>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.html">
                    <i class="bi bi-grid"></i><span>Dashboard</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main -->
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Gestion des Civilisations</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
                    <li class="breadcrumb-item active">Civilisations</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Liste des Civilisations</h5>

                            <!-- Message de succès -->
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

                            <!-- Bouton d'ajout -->
                            <a href="addCivilisation.php" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Ajouter une nouvelle civilisation</a>

                            <!-- Tableau -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">Nom</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
 <?php foreach ($civilisations as $civilisation): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($civilisation['name']); ?></td>
                                            <td><?= htmlspecialchars($civilisation['description']); ?></td>
                                            <td>
                                                <a href="editCivilisation.php?id=<?= $civilisation['id']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Modifier</a>
                                                <a href="deleteCivilisation.php?id=<?= $civilisation['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette civilisation ?')">
                                                    <i class="bi bi-trash"></i> Supprimer
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- End Tableau -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
