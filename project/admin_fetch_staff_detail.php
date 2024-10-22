<?php
header('Content-Type: application/json'); // Set the response type to JSON
include "db_conn.php"; // Include your database connection

if (isset($_POST['staff_id'])) {
    $staff_id = mysqli_real_escape_string($conn, $_POST['staff_id']); // Sanitize input

    $sql = "SELECT * FROM staff_details WHERE staff_id = '$staff_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
        echo json_encode($staff); // Return staff details in JSON format
    } else {
        echo json_encode(['error' => 'No staff found']); // Return error message in JSON format
    }
} else {
    // Handle the case where staff_id is not provided
    echo json_encode(['error' => 'staff_id is required']); // Return error message in JSON format
}

$conn->close(); // Close the database connection
?>
