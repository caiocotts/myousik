<?php
$status = session_start();
echo $status;
$user_id = $_SESSION['id'];
$song_id = intval($_GET['song_id']);

$conn = new mysqli("mysql", "root", "n01415150", "myousik");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO listens (user_id, song_id, listened_at) VALUES (?, ?, NOW())
        ON DUPLICATE KEY UPDATE listened_at = NOW()";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $song_id);
$stmt->execute();
$stmt->close();

$conn->close();
?>