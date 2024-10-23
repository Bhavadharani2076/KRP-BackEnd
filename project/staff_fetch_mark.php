<?php
header('Content-Type: application/json'); // Set the response content type to JSON
require 'db_conn.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch data from exam_marks table
    $sql = "SELECT register_number, student_name, subjects, total_grade FROM exam_marks";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $students = [];
        while($row = $result->fetch_assoc()) {
            // Decode the JSON 'subjects' column before returning it
            $row['subjects'] = json_decode($row['subjects'], true);
            $students[] = $row;
        }

        echo json_encode(['status' => 'success', 'data' => $students]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No marks data found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Only POST is allowed.']);
}

$conn->close();
?>
