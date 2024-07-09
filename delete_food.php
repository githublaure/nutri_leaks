<?php
session_start();
include('config.local.php'); // Utilisez 'config.production.php' en production

if ($_SESSION['role'] != 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$stmt = $conn->prepare("DELETE FROM foods WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting record']);
}
$stmt->close();

$conn->close();
?>
