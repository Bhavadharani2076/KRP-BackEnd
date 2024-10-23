<?php
if (isset($_POST['update'])) {
    $hwid = $_POST['hwid'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $subject = $_POST['subject'];
    $date = $_POST['date'];
    $last_date = $_POST['last_date'];
    $description = $_POST['description'];
    $file = $_FILES['file']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file);

    // Check if file is uploaded
    if (!empty($file)) {
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            // Return error in JSON if file upload fails
            echo json_encode(['status' => 'error', 'message' => 'Error uploading file.']);
            exit;
        }
    } else {
        $file = ""; // If no new file is uploaded
    }

    // Database connection
    include "db_conn.php";
    if ($conn->connect_error) {
        // Return error in JSON if connection fails
        echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
        exit;
    }

    // Prepare SQL statement based on whether a file is uploaded or not
    if (!empty($file)) {
        $sql = "UPDATE homework SET class=?, section=?, subject=?, date=?, last_date=?, description=?, file=? WHERE hwid=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssi', $class, $section, $subject, $date, $last_date, $description, $file, $hwid);
    } else {
        $sql = "UPDATE homework SET class=?, section=?, subject=?, date=?, last_date=?, description=? WHERE hwid=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssi', $class, $section, $subject, $date, $last_date, $description, $hwid);
    }

    // Execute the statement
    if ($stmt->execute()) {
        // Return success in JSON
        echo json_encode(['status' => 'success', 'message' => 'Homework updated successfully.']);
    } else {
        // Return error in JSON if the statement execution fails
        echo json_encode(['status' => 'error', 'message' => 'Error updating record: ' . $stmt->error]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
    exit; // Ensure no further output is sent
}

// If the request is not valid
echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
?>
