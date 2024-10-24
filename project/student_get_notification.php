<?php
header('Content-Type: application/json');
require 'db_conn.php'; // Include your database connection

// Fetch notifications from the database
$query = "SELECT msgid, type, message,brief_detail,date,file FROM notifications ORDER BY date DESC";
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
?>
