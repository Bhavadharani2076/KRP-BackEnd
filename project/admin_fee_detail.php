<?php

include ".\includes\db_conn.php";

header('Content-Type: application/json'); // Set the header for JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $feetype = $_POST['feetype'];
    $fee_cat = $_POST['fee_cat'];
    $feeamount = $_POST['feeamount'];
    $strd = $_POST['strd'];
    $last_date = $_POST['last_date'];
    
    // Insert SQL
    $insertSQL = "INSERT INTO `student_fees` (feetype, feecategory, feeamount, class, last_date) 
                  VALUES ('$feetype', '$fee_cat', '$feeamount', '$strd', '$last_date')";
    
    $insertResult = mysqli_query($conn, $insertSQL);

    if ($insertResult) {
        // Return success message in JSON format
        echo json_encode([
            "status" => "success",
            "message" => "Added Fees Details successfully."
        ]);
    } else {
        // Return error message in JSON format
        echo json_encode([
            "status" => "error",
            "message" => "Error registering. Please try again later."
        ]);
    }
    
} else {
    // Return error if the script is accessed without a POST request
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method. Only POST allowed."
    ]);
    exit();
}

?>
