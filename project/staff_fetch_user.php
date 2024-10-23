<?php
require 'db_conn.php'; // Include your database connection

// Check if class and section are set in the POST request
if (isset($_POST['class']) && isset($_POST['section'])) {
    $class = $_POST['class'];
    $section = $_POST['section'];

    // Prepare the SQL query
    $sql = "SELECT * FROM userlogin WHERE class = ? AND section = ? AND user_belongs_group = ?";
    $ubg = 2; // Assuming user_belongs_group is a constant value
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $class, $section, $ubg);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = []; // Initialize response array

    if ($result->num_rows > 0) {
        $students = []; // Array to hold student data
        while ($row = $result->fetch_assoc()) {
            $students[] = [
                'admission_number' => htmlspecialchars($row['admission_number']),
                'student_name' => htmlspecialchars($row['student_name']),
                'present' => false, // Initialize checkbox status
                'absent' => false // Initialize checkbox status
            ];
        }
        $response = [
            'status' => 'success',
            'data' => $students
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'No users found for the selected class and section.'
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Please select both class and section.'
    ];
}

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>
