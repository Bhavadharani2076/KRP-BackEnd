<?php
if (isset($_POST['update'])) {
    $msgid = $_POST['msgid'];
    $type = $_POST['type'];
    $message = $_POST['message'];
    $brief_detail = $_POST['brief_detail'];
    
    $file = $_FILES['file']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file);
    
    if (!empty($file)) {
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            echo json_encode(["error" => "Error uploading file."]);
            exit;
        }
    } else {
        $file = ""; // If no new file is uploaded
    }

    // Database connection
    include "db_conn.php";
    if ($conn->connect_error) {
        die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
    }

    if (!empty($file)) {
        $sql = "UPDATE notifications SET type=?, message=?, brief_detail=?, file=? WHERE msgid=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $type, $message, $brief_detail, $file, $msgid);
    } else {
        $sql = "UPDATE notifications SET type=?, message=?, brief_detail=? WHERE msgid=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $type, $message, $brief_detail, $msgid);
    }
    
    if ($stmt->execute()) {
        echo json_encode(["success" => "Notification updated successfully.", "msgid" => $msgid, "type" => $type, "message" => $message]);
    } else {
        echo json_encode(["error" => "Error updating record: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    // Removed the header redirect for JSON response
    exit; // Make sure to exit after processing
}
?>
