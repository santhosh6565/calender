<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $start_date = $_POST['start'];
    $end_date = $_POST['end'];

    $sql = "UPDATE events SET title = ?, start_date = ?, end_date = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $start_date, $end_date, $id);

    if ($stmt->execute()) {
        echo "Event updated!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>