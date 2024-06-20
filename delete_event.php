<?php
// delete_event.php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['id'];

    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        echo "Event deleted!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
