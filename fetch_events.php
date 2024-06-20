<?php
// fetch_events.php
include('config.php');
session_start();

$user_id = $_SESSION['user_id'];

$sql = "SELECT id, title, start_date AS start FROM events WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode($events);
?>
