<?php
include('config.local.php'); // Inclure le fichier de configuration de la base de données

$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'];
$score = $data['score'];

$sql = "INSERT INTO scores (username, score) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $username, $score);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement du score.']);
}

$stmt->close();
$conn->close();
?>
