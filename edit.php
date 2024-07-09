<?php
session_start();
include('config.local.php'); // Utilisez 'config.production.php' en production

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Lire le contenu de la page
$content = file_get_contents('page_content.txt');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Page</title>
</head>
<body>
    <h1>Edit Page</h1>
    <form method="post" action="save_changes.php">
        <textarea name="content" rows="10" cols="30"><?php echo htmlspecialchars($content); ?></textarea><br>
        <input type="submit" value="Save Changes">
    </form>
    <a href="index.php">Back to Home</a>
</body>
</html>
