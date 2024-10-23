<?php
include "db_conn.php";

// Set the Content-Type to application/json
header('Content-Type: application/json');

// Check if user_id is provided in POST data (simulate session for Thunder Client testing)
if (!isset($_POST['user_id'])) {
    echo json_encode(['error' => 'User ID not provided.']);
    exit();
}

$user_id = $_POST['user_id']; // Use user_id from POST data

// Fetch the student's class based on the provided user ID
$sql = "SELECT class, section FROM userlogin WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => "Failed to prepare statement: " . $conn->error]);
    exit();
}

$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    echo json_encode(['error' => "Failed to execute statement: " . $stmt->error]);
    exit();
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['error' => "User not found."]);
    exit();
}

$user = $result->fetch_assoc();
$class = $user['class'];
$section = $user['section'];

// Fetch homework based on class and section
$sql = "SELECT hwid, class, section, subject, date, last_date, description, file FROM homework WHERE class = ? AND section = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $class, $section);
$stmt->execute();
$result = $stmt->get_result();

$homeworkData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $homeworkData[] = $row;
    }
} else {
    echo json_encode(['error' => 'No Homework Found']);
    exit();
}

// Output the homework data in JSON format
echo json_encode($homeworkData);
?>
