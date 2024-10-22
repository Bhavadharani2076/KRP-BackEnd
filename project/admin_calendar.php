<?php

include "db_conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json'); // Set the response type to JSON

    $title = $_POST['title'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $every_year = $_POST['every_year'];
    $badge = $_POST['badge'];

    $insertSQL = "INSERT INTO `calender` (name, date, end_date, type, every_year, description, badge) 
                      VALUES ('$title', '$start_date', '$end_date', '$type', '$every_year', '$description', '$badge')";

    $insertResult = mysqli_query($conn, $insertSQL);

    if ($insertResult) {
        // Return a success JSON response
        echo json_encode([
            "success" => true,
            "message" => "Registered successfully."
        ]);
    } else {
        // Return a failure JSON response
        echo json_encode([
            "success" => false,
            "message" => "Error registering. Please try again later."
        ]);
    }
} else {
    // Return an error if accessed without a POST request
    header('Content-Type: application/json'); 
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);

    exit();
}
