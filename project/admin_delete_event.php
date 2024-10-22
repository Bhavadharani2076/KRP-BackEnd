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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // SQL query to delete the event
    $sql = "DELETE FROM events WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        // Return success response in JSON
        echo json_encode([
            "success" => true,
            "message" => "Record deleted successfully"
        ]);
    } else {
        // Return error response in JSON
        echo json_encode([
            "success" => false,
            "message" => "Error deleting record: " . $conn->error
        ]);
    }

    $conn->close();
} else {
    // Return error response for invalid request method
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
}
?>
