<?php
include('config.local.php'); // Utilisez 'config.production.php' en production

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $benefit = $_POST['benefit'];

    $sql = "INSERT INTO foods (name, benefit) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $benefit);

    if ($stmt->execute()) {
        // Redirection vers index.php après l'ajout réussi
        header("Location: index.php");
        exit();
    } else {
        echo "Erreur lors de l'ajout de l'aliment: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
