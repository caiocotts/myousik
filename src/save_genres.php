<?php
$status = session_start();
echo $status;
$user_id = $_SESSION['id'];
$genres = $_POST['genres'];

$conn = new mysqli("mysql", "root", "n01415150", "myousik");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO preferences (user_id, genre) VALUES (?, ?)
        ON DUPLICATE KEY UPDATE genre = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iv", $user_id, $genres, $genres);
$stmt->execute();
$stmt->close();

$conn->close();
?>