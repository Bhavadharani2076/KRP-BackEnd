<?php
header('Content-Type: application/json');
require 'db_conn.php'; // Include your database connection

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch notifications from the database
    $query = "SELECT msgid, type, message, brief_detail, date, file, uploaded_by FROM notifications ORDER BY date DESC;";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        echo json_encode($notifications);
    } else {
        echo json_encode([]);
    }
} else {
    // Return an error response if the method is not POST
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method. Please use POST."
    ]);
}

$conn->close();
?>
