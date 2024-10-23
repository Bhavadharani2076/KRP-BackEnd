<?php
header('Content-Type: application/json'); // Set the content type to JSON
include('parent-header.php'); // Include the header

$response = []; // Initialize response array

// Update all unread notifications to "read"
$sql = "UPDATE notifications SET is_read = TRUE WHERE is_read = FALSE";
if ($conn->query($sql) === TRUE) {
    $response['success'] = true;
    $response['message'] = 'All unread notifications marked as read.';
    $response['updated_count'] = $conn->affected_rows; // Get the number of updated rows
} else {
    $response['success'] = false;
    $response['message'] = 'Error updating notifications: ' . $conn->error;
}

// Close the database connection
$conn->close();

// Return JSON response
echo json_encode($response);
?>
