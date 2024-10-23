<?php
include "db_conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch input data from the form
    $class = $_POST['std'];
    $section = $_POST['sec'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    // $current_date = date('Y-m-d'); // Get current date
    $current_date = '2024-09-06'; // Custom date

    // Check if homework already exists for this class and section on the current date
    $query = "SELECT * FROM regularhomework WHERE class = ? AND section = ? AND date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $class, $section, $current_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Homework exists, so update the JSON field with the new subject and description
        $row = $result->fetch_assoc();
        $homework_data = json_decode($row['homework'], true);

        // Update homework data for the current subject
        $homework_data[$subject] = $description;

        // Convert the updated homework data back to JSON
        $updated_homework_json = json_encode($homework_data);

        // Update the record in the database
        $update_query = "UPDATE regularhomework SET homework = ? WHERE class = ? AND section = ? AND date = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('ssss', $updated_homework_json, $class, $section, $current_date);
        if ($update_stmt->execute()) {
            // Prepare the response
            $response = [
                "status" => "success",
                "message" => "Homework updated successfully!",
            ];
        } else {
            // Prepare error response
            $response = [
                "status" => "error",
                "message" => "Error updating homework.",
            ];
        }
    } else {
        // Homework does not exist, so insert a new record
        $homework_data = array($subject => $description);
        $homework_json = json_encode($homework_data);

        // Insert new homework data
        $insert_query = "INSERT INTO regularhomework (class, section, date, homework) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param('ssss', $class, $section, $current_date, $homework_json);
        if ($insert_stmt->execute()) {
            // Prepare the response
            $response = [
                "status" => "success",
                "message" => "Homework added successfully!",
            ];
        } else {
            // Prepare error response
            $response = [
                "status" => "error",
                "message" => "Error adding homework.",
            ];
        }
    }

    // Set the content type to JSON
    header('Content-Type: application/json');
    // Encode and return the response
    echo json_encode($response);

    $stmt->close();
    $conn->close();
}
?>
