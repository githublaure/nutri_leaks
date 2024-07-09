<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (file_exists('config.local.php')) {
    include('config.local.php');
} else {
    include('config.production.php');
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["username"]. " - Email: " . $row["email"]. "<br>";
    }
} else {
    echo "0 results";
}

$conn->close();
?>
