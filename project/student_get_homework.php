<?php

include "db_conn.php";

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in.');
    }

    $user_id = $_SESSION['user_id'];

    // Fetch the student's class based on the session ID
    $sql = "SELECT class, section FROM userlogin WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("User not found.");
    }

    $user = $result->fetch_assoc();
    $class = $user['class'];
    $section = $user['section'];

    // Fetch homework for the specific class and section
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
        // Return a JSON response if no homework is found
        echo json_encode(['message' => 'No Homework Found']);
        exit();
    }

    // Return the homework data as JSON
    echo json_encode($homeworkData);

} catch (Exception $e) {
    // Return an error message as JSON
    echo json_encode(['error' => $e->getMessage()]);
}
?>
