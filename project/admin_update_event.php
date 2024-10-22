<?php
include "db_conn.php";

// Set the response to JSON
header('Content-Type: application/json');

// Check connection
if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Connection failed: " . $conn->connect_error
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $event_title = mysqli_real_escape_string($conn, $_POST['event_title']);
    $event_description = mysqli_real_escape_string($conn, $_POST['event_description']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);

    // SQL query to update the event
    $sql = "UPDATE events SET event_title='$event_title', event_description='$event_description', event_date='$event_date' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        // Return success response in JSON
        echo json_encode([
            "success" => true,
            "message" => "Event updated successfully."
        ]);
    } else {
        // Return error response in JSON
        echo json_encode([
            "success" => false,
            "message" => "Error updating the event: " . $conn->error
        ]);
    }

    $conn->close();
} else {
    // Return error for invalid request
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method or missing update flag."
    ]);
}
?>
