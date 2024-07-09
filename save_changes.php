<?php
session_start();
include('config.local.php'); // Utilisez 'config.production.php' en production

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST['content'];

    // Sauvegarder le contenu dans un fichier
    file_put_contents('page_content.txt', $content);

    header("Location: index.php");
    exit();
}
?>
