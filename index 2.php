<?php
session_start();
include('config.production.php'); // Utilisez 'config.production.php' en production

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$welcome_message = "";
if (isset($_SESSION['welcome_message'])) {
    $welcome_message = $_SESSION['welcome_message'];
    unset($_SESSION['welcome_message']); // Optionnel, si vous voulez afficher le message une seule fois
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jeu de Correspondance Aliment-Bienfait</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Associez chaque aliment à son bienfait</h2>
    <div class="container">
        <div id="foods" class="items">
            <!-- Les aliments seront générés ici par JavaScript -->
        </div>
        <div id="benefits" class="items">
            <!-- Les bienfaits seront générés ici par JavaScript -->
        </div>
    </div>
    <div class="admin-options">
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <a href="add_food.html" class="admin-link">Ajouter un aliment</a>
            <a href="#" id="show-delete-options" class="admin-link">Supprimer un aliment</a>
        <?php endif; ?>
    </div>
    <div id="score-board">
        <!-- Le tableau des scores sera généré ici par JavaScript -->
    </div>
    <div id="best-score-board">
        <!-- Le meilleur score sera généré ici par JavaScript -->
    </div>
    <div class="game-controls">
        <button id="restart-game">Recommencer</button>
    </div>
    <script>
        const currentUser = <?php echo json_encode($_SESSION['username']); ?>;
        const currentRole = <?php echo json_encode($_SESSION['role']); ?>;
        console.log('Current User:', currentUser); // Debugging log
        console.log('Current Role:', currentRole); // Debugging log
    </script>
    <script src="script.js"></script>
</body>
</html>
