<?php
include "db_conn.php";

header('Content-Type: application/json'); // Set the response type to JSON

$admission_no = $_SESSION["admission_number"];

$old_password = $_POST['oldPassword'];
$new_password = $_POST['newPassword'];
$confirm_password = $_POST['confirmPassword'];

// Check if the old password matches the password in the transporter_signup table
$check_sql = "SELECT * FROM `userlogin` WHERE admission_number = ? AND password = ?";
$check_stmt = $conn->prepare($check_sql);

if (!$check_stmt) {
    // Return JSON error if SQL query fails
    echo json_encode([
        "success" => false,
        "message" => "SQL query error: " . $conn->error
    ]);
    exit();
}

$check_stmt->bind_param("ss", $admission_no, $old_password);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows == 1) {
    // Old password is a match, check if new password and confirm password match
    if ($new_password === $confirm_password) {
        // Update the password in the transporter_signup table
        $update_sql = "UPDATE `userlogin` SET password = ? WHERE admission_number = ?";
        $update_stmt = $conn->prepare($update_sql);

        if (!$update_stmt) {
            // Return JSON error if SQL query fails
            echo json_encode([
                "success" => false,
                "message" => "SQL query error: " . $conn->error
            ]);
            exit();
        }

        $update_stmt->bind_param("ss", $new_password, $admission_no);

        if ($update_stmt->execute()) {
            // Password updated successfully, return JSON success response
            echo json_encode([
                "success" => true,
                "message" => "Password updated successfully."
            ]);
        } else {
            // Return JSON error if update fails
            echo json_encode([
                "success" => false,
                "message" => "Error updating password: " . $conn->error
            ]);
        }

        $update_stmt->close();
    } else {
        // New password and confirm password do not match
        echo json_encode([
            "success" => false,
            "message" => "New password and confirm password do not match."
        ]);
    }
} else {
    // Old password does not match
    echo json_encode([
        "success" => false,
        "message" => "Old password does not match."
    ]);
}

$check_stmt->close();
$conn->close();
?>
