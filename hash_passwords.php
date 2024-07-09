<?php
include('config.local.php'); // Utilisez 'config.production.php' selon l'environnement

// Récupérer tous les utilisateurs avec leurs mots de passe en clair
$result = $conn->query("SELECT id, username, password FROM users");

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $username = $row['username'];
    $plain_password = $row['password'];

    // Hacher le mot de passe en clair
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    // Mettre à jour l'utilisateur avec le mot de passe haché
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $id);
    $stmt->execute();
    $stmt->close();

    echo "Updated password for user $username (ID: $id) with hashed password.<br>";
}

$conn->close();
?>
