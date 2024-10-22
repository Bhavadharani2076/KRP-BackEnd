<?php
// Database connection
include "db_conn.php";

// Set the response to JSON
header('Content-Type: application/json');

// Check connection
if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Connection failed: " . $conn->connect_error
    ]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $message = $_POST['message'];
    $brief_detail = $_POST['brief_detail'];
    $date = $_POST['date'];
    $file = $_FILES['file'];
    $uploaded_by = $_POST['uploaded_by'];

    // File upload handling
    $target_dir = "../uploads/";
    $target_file = $target_dir . uniqid() . '_' . basename($file["name"]);
    $uploadOk = 1;

    // Allow certain file formats
    $allowedFileTypes = ["jpg", "jpeg", "png", "gif", "pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx"];
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedFileTypes)) {
        echo json_encode([
            "success" => false,
            "message" => "Sorry, only JPG, JPEG, PNG, GIF, PDF, DOC, DOCX, XLS, XLSX, PPT, and PPTX files are allowed."
        ]);
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo json_encode([
            "success" => false,
            "message" => "Sorry, file already exists."
        ]);
        $uploadOk = 0;
    }

    // Check file size (5MB limit)
    if ($file["size"] > 5000000) {
        echo json_encode([
            "success" => false,
            "message" => "Sorry, your file is too large."
        ]);
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo json_encode([
            "success" => false,
            "message" => "Sorry, there was an error uploading your file."
        ]);
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // Prepare and bind the SQL statement
            $stmt = $conn->prepare("INSERT INTO notifications (type, message, brief_detail, date, file, is_read, uploaded_by) VALUES (?, ?, ?, ?, ?, FALSE, ?)");
            $stmt->bind_param("ssssss", $type, $message, $brief_detail, $date, $target_file, $uploaded_by);

            // Execute the statement
            if ($stmt->execute()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Notification uploaded successfully."
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Error: " . $stmt->error
                ]);
            }

            // Close statement
            $stmt->close();
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Sorry, there was an error uploading your file."
            ]);
        }
    }
} else {
    // Return error if accessed directly without POST request
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}

$conn->close();
?>
