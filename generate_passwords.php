<?php
include('config.local.php'); // Utilisez 'config.production.php' selon l'environnement

// Définir un tableau d'utilisateurs avec leurs mots de passe en clair et leurs rôles
$users = [
    ['username' => 'user1', 'password' => 'password1', 'email' => 'user1@example.com', 'role' => 'admin'],
    ['username' => 'user2', 'password' => 'password2', 'email' => 'user2@example.com', 'role' => 'admin'],
    ['username' => 'user3', 'password' => 'password3', 'email' => 'user3@example.com', 'role' => 'user'],
];

// Boucler sur chaque utilisateur dans le tableau
foreach ($users as $user) {
    // Hacher le mot de passe en utilisant l'algorithme de hachage par défaut (actuellement BCRYPT)
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
    
    // Préparer une requête SQL pour insérer l'utilisateur dans la table `users`
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    
    // Lier les variables aux paramètres de la requête préparée
    $stmt->bind_param("ssss", $user['username'], $hashed_password, $user['email'], $user['role']);
    
    // Exécuter la requête
    $stmt->execute();
    
    // Afficher un message de confirmation pour chaque utilisateur inséré
    echo "User {$user['username']} inserted with hashed password $hashed_password<br>";
    
    // Fermer la requête préparée
    $stmt->close();
}

// Fermer la connexion à la base de données
$conn->close();
?>
