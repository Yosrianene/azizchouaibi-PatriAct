<?php
// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=patrimoine;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Initialisation des variables d'erreur
$questionErr = $answerErr = ""; 
$question = $answer = ""; // Initialisation des variables

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    // Validation côté serveur
    if (empty($question)) {
        $questionErr = "La question est requise";
    }

    if (empty($answer)) {
        $answerErr = "La réponse est requise";
    }

    if (empty($questionErr) && empty($answerErr)) {
        try {
            // Préparer la requête pour insérer les données dans la base
            $stmt = $pdo->prepare("INSERT INTO chatbot (question, answer) VALUES (:question, :answer)");

            // Lier les paramètres
            $stmt->bindParam(':question', $question);
            $stmt->bindParam(':answer', $answer);

            // Exécuter la requête
            $stmt->execute();

            // Rediriger vers une page de succès après l'insertion
            header("Location: index.php?success=1"); // redirection avec un message de succès
            exit();

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Question et Réponse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Validation du formulaire avec JavaScript
        function validateForm() {
            let question = document.getElementById("question").value;
            let answer = document.getElementById("answer").value;
            let valid = true;

            // Validation de la question
            if (question === "") {
                document.getElementById("questionErr").innerHTML = "La question est requise";
                valid = false;
            } else {
                document.getElementById("questionErr").innerHTML = "";
            }

            // Validation de la réponse
            if (answer === "") {
                document.getElementById("answerErr").innerHTML = "La réponse est requise";
                valid = false;
            } else {
                document.getElementById("answerErr").innerHTML = "";
            }

            return valid;
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Ajouter une Question et Réponse</h1>

        <!-- Formulaire d'ajout -->
        <form action="addQuestion.php" method="POST" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="question" class="form-label">Question</label>
                <input type="text" class="form-control" id="question" name="question" value="">
                <div class="text-danger" id="questionErr"><?php echo isset($questionErr) ? $questionErr : ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="answer" class="form-label">Réponse</label>
                <textarea class="form-control" id="answer" name="answer" rows="3"></textarea>
                <div class="text-danger" id="answerErr"><?php echo isset($answerErr) ? $answerErr : ''; ?></div>
            </div>

            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
