<?php
include('config.local.php'); // Inclure le fichier de configuration de la base de données

$sql = "SELECT username, score FROM scores ORDER BY score DESC, date ASC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['username' => $row['username'], 'score' => $row['score']]);
} else {
    echo json_encode(['username' => '', 'score' => 0]);
}

$conn->close();
?>
