<?php
// Fetch unread notifications count from the database
$unreadCount = 0; // Default value
include "db_conn.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) AS count FROM notifications WHERE is_read = FALSE";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    $unreadCount = $row['count'];
}
?>
