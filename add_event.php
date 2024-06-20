<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "INSERT INTO events (user_id, title, start_date, end_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $title, $start_date, $end_date);

    if ($stmt->execute()) {
        echo "Event added!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
