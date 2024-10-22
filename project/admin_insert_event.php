<?php
// Database connection
include "db_conn.php";

// Set the response type to JSON
header('Content-Type: application/json');

// Check connection
if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Connection failed: " . $conn->connect_error
    ]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_title = $_POST['event_title'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO events (event_title, event_description, event_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $event_title, $event_description, $event_date);

    // Execute the statement
    if ($stmt->execute()) {
        // Return success response in JSON
        echo json_encode([
            "success" => true,
            "message" => "Event uploaded successfully."
        ]);
    } else {
        // Return error response in JSON
        echo json_encode([
            "success" => false,
            "message" => "Error: " . $stmt->error
        ]);
    }

    // Close statement
    $stmt->close();
} else {
    // Return error for invalid request method
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method. Please submit the form using POST."
    ]);
}

$conn->close();
?>
