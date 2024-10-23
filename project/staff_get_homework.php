<?php
header('Content-Type: application/json');
require 'db_conn.php'; // Include your database connection

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch notifications from the database
    $query = "SELECT hwid, class, section, subject, date, last_date, description, file FROM homework ORDER BY `homework`.`date` DESC";
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
    // If the request method is not POST, respond with an error message
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>
