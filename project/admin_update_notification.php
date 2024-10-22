<?php
// Set the response type to JSON
header('Content-Type: application/json');

if (isset($_POST['update'])) {
    $msgid = $_POST['msgid'];
    $type = $_POST['type'];
    $message = $_POST['message'];
    $brief_detail = $_POST['brief_detail'];

    $file = $_FILES['file']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($file);

    // File handling
    if (!empty($file)) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            // File upload success
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error uploading file."
            ]);
            exit;
        }
    } else {
        $file = ""; // If no new file is uploaded
    }

    // Database connection
    include "db_conn.php";
    if ($conn->connect_error) {
        echo json_encode([
            "success" => false,
            "message" => "Connection failed: " . $conn->connect_error
        ]);
        exit();
    }

    // SQL Update based on whether the file is provided or not
    if (!empty($file)) {
        $sql = "UPDATE notifications SET type=?, message=?, brief_detail=?, file=? WHERE msgid=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $type, $message, $brief_detail, $file, $msgid);
    } else {
        $sql = "UPDATE notifications SET type=?, message=?, brief_detail=? WHERE msgid=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $type, $message, $brief_detail, $msgid);
    }

    // Execute and send a JSON response
    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Notification updated successfully.",
            "msgid" => $msgid,
            "type" => $type,
            "message" => $message
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error updating record: " . $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    // Invalid request
    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);
}
?>
