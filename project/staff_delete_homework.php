<?php
include "db_conn.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['hwid']);

    // SQL query to delete the event
    $sql = "DELETE FROM homework WHERE hwid='$id'";

    // Set the content type to JSON
    header('Content-Type: application/json');

    if ($conn->query($sql) === TRUE) {
        // Return success response as JSON
        echo json_encode(["status" => "success", "message" => "Record deleted successfully"]);
    } else {
        // Return error response as JSON
        echo json_encode(["status" => "error", "message" => "Error deleting record: " . $conn->error]);
    }

    $conn->close();
}
?>
