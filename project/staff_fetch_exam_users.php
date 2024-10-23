<?php
header('Content-Type: application/json');
include "db_conn.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Access class_name and section from the POST data
    $class_name = $_POST['class_name'] ?? null; // Use null coalescing to avoid undefined index
    $section = $_POST['section'] ?? null;       // Use null coalescing to avoid undefined index

    // Check if class_name and section are provided
    if (is_null($class_name) || is_null($section)) {
        echo json_encode(["status" => "error", "message" => "Missing class_name or section."]);
        exit;
    }

    $ubg = 2;

    // Prepare and execute the query
    $sql = "SELECT student_name, admission_number FROM userlogin WHERE class = ? AND section = ? AND user_belongs_group= ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $class_name, $section, $ubg); // Changed to "ssi" for the integer ubg
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = [
            'name' => $row['student_name'],
            'register_number' => $row['admission_number']
        ];
    }

    // Return the users as JSON
    echo json_encode($users);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$stmt->close();
$conn->close();
?>
