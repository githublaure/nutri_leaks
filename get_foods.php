<?php
session_start();
include('config.local.php'); // Utilisez 'config.production.php' en production

$result = $conn->query("SELECT id, name, benefit FROM foods");
$foods = array();

while ($row = $result->fetch_assoc()) {
    $foods[] = $row;
}

echo json_encode([
    'foods' => $foods,
    'role' => isset($_SESSION['role']) ? $_SESSION['role'] : '' // Inclure le rôle de l'utilisateur dans la réponse
]);

$conn->close();
?>
