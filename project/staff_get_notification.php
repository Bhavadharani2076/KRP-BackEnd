<?php
header('Content-Type: application/json');
require 'db_conn.php'; // Include your database connection

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Fetch notifications from the database
    $query = "SELECT msgid, type, message, brief_detail, date, file, uploaded_by FROM notifications";
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

    $conn->close();
} else {
    // If the request method is not POST, return an error message
    echo json_encode(["error" => "Invalid request method. Please use POST."]);
}
?>
