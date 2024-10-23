<?php
include "db_conn.php";

// Set the response type to JSON
header('Content-Type: application/json');

try {
    // Start the session to access session variables
    $admission_no = $_SESSION["admission_number"];

    // Query to fetch all details from userlogin table
    $sql = "SELECT * FROM userlogin WHERE admission_number = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("s", $admission_no);
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("No user details found for the admission number.");
    }

    $row = $result->fetch_assoc();
    echo json_encode($row);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}

// Close the prepared statement and connection
$stmt->close();
$conn->close();
?>
