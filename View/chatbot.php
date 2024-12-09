<?php
// Démarrer une session pour gérer l'historique
session_start();

// Si la limite de questions est atteinte ou si l'utilisateur demande une réinitialisation, réinitialiser la session
if (isset($_GET['reset'])) {
    session_destroy(); // Détruire la session actuelle
    session_start();   // Démarrer une nouvelle session

    // Ajouter uniquement le message de bienvenue
    $_SESSION['history'] = [
        [
            'user_message' => 'salut',
            'bot_message' => "salut! Je suis le chatbot. Posez-moi des questions sur les civilisations qui ont marqué l'histoire de la Tunisie."
        ]
    ];
    $_SESSION['question_count'] = 0; // Réinitialiser le compteur
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "patrimoine";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialiser l'historique et le compteur de questions pour l'utilisateur si non déjà configurés
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [
        [
            'user_message' => 'bonjour ',
            'bot_message' => "Bonjour ! Je suis le chatbot. Posez-moi des questions sur les civilisations qui ont marqué l'histoire de la Tunisie."
        ]
    ];
    $_SESSION['question_count'] = 0; // Compteur de questions
}

// Variables
$chatbotResponse = "";

// Gestion de la question posée par l'utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_question']) && $_SESSION['question_count'] < 25) {
    $user_question = trim($_POST['user_question']);

    if (!empty($user_question)) {
        // Recherche dans la base de données pour une correspondance exacte avec la question
        $user_question = $conn->real_escape_string($user_question);

        // Requête SQL pour rechercher la réponse à la question
        $stmt = $conn->prepare("SELECT answer FROM chatbot WHERE question = ?");
        $stmt->bind_param("s", $user_question);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $chatbotResponse = $row['answer'];
        } else {
            $chatbotResponse = "Désolé, je n'ai pas trouvé de réponse à cette question.";
        }

        // Ajouter la question et la réponse à l'historique
        $_SESSION['history'][] = ['user_message' => $user_question, 'bot_message' => $chatbotResponse];

        // Incrémenter le compteur de questions
        $_SESSION['question_count']++;
    } else {
        $chatbotResponse = "Veuillez poser une question.";
    }
}

// Récupérer l'historique des messages (spécifique à l'utilisateur)
$history = $_SESSION['history'];

// Récupérer toutes les questions disponibles dans la base de données
$sql = "SELECT question FROM chatbot ORDER BY RAND() LIMIT 2"; // Sélectionner 2 questions aléatoires
$result = $conn->query($sql);

// Si des questions sont trouvées, les stocker dans un tableau
$questions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row['question'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot - Discussion</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff;
            color: white;
            padding: 20px;
        }

        .header h1 {
            margin: 0;
        }

        .chat-box {
            max-height: 400px;
            overflow-y: auto;
            padding: 20px;
            background-color: #f1f1f1;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-message {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .user-message {
            align-items: flex-end;
        }

        .user-message strong {
            color: #007bff;
        }

        .bot-message {
            align-items: flex-start;
        }

        .bot-message strong {
            color: #28a745;
        }

        .form-input {
            width: calc(100% - 100px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-submit {
            width: 80px;
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-submit:hover {
            background-color: #0056b3;
        }

        .question-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .question-button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .question-button:hover {
            background-color: #218838;
        }

        .message-container {
            display: flex;
            justify-content: space-between;
        }

        .chat-box::-webkit-scrollbar {
            width: 8px;
        }

        .chat-box::-webkit-scrollbar-thumb {
            background-color: #007bff;
            border-radius: 10px;
        }

        .chat-box::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }

        /* Style pour le bouton de mise à jour */
        .refresh-btn {
            font-size: 24px;
            color: #007bff;
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.3s;
        }

        .refresh-btn:hover {
            color: #0056b3;
        }

        @media (max-width: 600px) {
            .container {
                width: 95%;
            }

            .form-input {
                width: 100%;
            }

            .form-submit {
                width: 100%;
                margin-top: 10px;
            }

            .question-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .question-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Conteneur de titre et flèche -->
    <div class="header">
        <h1>Chatbot - Discussion en temps réel</h1>
        <!-- Bouton pour réinitialiser la session -->
        <a href="?reset=true">
            <button class="refresh-btn">
                &#x21bb; Actualiser
            </button>
        </a>
    </div>

    <!-- Affichage de l'historique des messages -->
    <div class="chat-box" id="chat-box">
        <?php foreach ($history as $item): ?>
            <div class="chat-message user-message">
                <strong>Vous:</strong> <?php echo htmlspecialchars($item['user_message']); ?>
            </div>
            <div class="chat-message bot-message">
                <strong>Chatbot:</strong> <?php echo htmlspecialchars($item['bot_message']); ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($_SESSION['question_count'] < 25): ?>
        <!-- Affichage des questions suggérées -->
        <?php if (!empty($questions)): ?>
            <div class="question-buttons">
                <?php foreach ($questions as $q): ?>
                    <form action="" method="POST" style="margin: 0;">
                        <input type="hidden" name="user_question" value="<?php echo htmlspecialchars($q); ?>">
                        <button type="submit" class="question-button"><?php echo htmlspecialchars($q); ?></button>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <p>Vous avez atteint la limite de questions autorisées. <a href="?reset=true">Cliquez ici pour réinitialiser</a> et poser de nouvelles questions.</p>
    <?php endif; ?>

    <!-- Formulaire pour envoyer une question -->
    <form action="" method="POST">
        <input type="text" name="user_question" class="form-input" placeholder="Posez une question...">
        <button type="submit" class="form-submit">Envoyer</button>
    </form>
</div>

<script>
    // Script pour actualiser le chatbox en temps réel
    function refreshChatBox() {
        var chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
    }

    setInterval(refreshChatBox, 1000); // Call the function every 1 second
</script>

</body>
</html>

<?php
// Fermer la connexion à la base de données
$conn->close();
?>
