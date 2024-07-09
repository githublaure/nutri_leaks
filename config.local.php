<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "beta_mon_site"; // Nom de la base de données locale

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
