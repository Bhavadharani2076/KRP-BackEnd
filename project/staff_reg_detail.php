<?php

include "db_conn.php";

header('Content-Type: application/json'); // Set the header for JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the last admission number from the database
    $lastAdmissionSQL = "SELECT MAX(staff_id) AS last_admission FROM `staff_details` WHERE user_belongs_group = 4";
    $lastAdmissionResult = mysqli_query($conn, $lastAdmissionSQL);

    if (!$lastAdmissionResult) {
        echo json_encode([
            "status" => "error",
            "message" => "Error fetching last admission number: " . mysqli_error($conn)
        ]);
        exit();
    }

    $row = mysqli_fetch_assoc($lastAdmissionResult);
    $lastAdmissionNumber = $row['last_admission'];

    // If there is no previous data in the database, start from 1000
    $admissionNumber = empty($lastAdmissionNumber) ? 1000 : $lastAdmissionNumber + 1;
    $staff_id = $admissionNumber;
    $staff_name = $_POST['staff_name'];
    $email = $_POST['email'];
    $password = 'Welcome';
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $degree = $_POST['degree'];
    $subject = $_POST['subject'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dateofjoining = $_POST['dateofjoining'];
    $experience = $_POST['experience'];
    $status = $_POST['status'];
    $user_group = "4";

    // Handle the image upload
    if (isset($_FILES['staff_img']) && $_FILES['staff_img']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['staff_img']['tmp_name'];
        $imageName = $_FILES['staff_img']['name'];
        $imageSize = $_FILES['staff_img']['size'];
        $imageType = $_FILES['staff_img']['type'];
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $imageNewName = 'staff_' . $admissionNumber . '.' . $imageExtension;

        // Specify the directory where the image will be saved
        $uploadFileDir = '../staff_image/';
        $destPath = $uploadFileDir . $imageNewName;

        // Move the image to the destination directory
        $imageUploadSuccess = move_uploaded_file($imageTmpPath, $destPath);
    } else {
        $imageUploadSuccess = false;
    }

    // Check if the Admission Number already exists
    $checkExistingSQL = "SELECT * FROM `staff_details` WHERE staff_id = '$admissionNumber'";
    $existingResult = mysqli_query($conn, $checkExistingSQL);

    if (!$existingResult) {
        echo json_encode([
            "status" => "error",
            "message" => "Error checking existing admission number: " . mysqli_error($conn)
        ]);
        exit();
    }

    if (mysqli_num_rows($existingResult) > 0) {
        // Admission Number already exists
        echo json_encode([
            "status" => "error",
            "message" => "Admission Number already exists. Please choose a different Admission Number."
        ]);
    } else {
        // Admission Number is unique, proceed with insertion
        $insertSQL = "INSERT INTO `staff_details` (staff_id, staff_name, email, password, gender, dob, degree, staff_subject, mobile_number, staff_address, date_of_joining, experience, staff_status, user_belongs_group, image_name) 
                      VALUES ('$staff_id','$staff_name', '$email', '$password', '$gender', '$dob', '$degree', '$subject', '$phone', '$address', '$dateofjoining', '$experience', '$status', '$user_group', '$imageNewName')";
        
        $insertResult = mysqli_query($conn, $insertSQL);

        if ($insertResult) {
            // User is successfully inserted
            if ($imageUploadSuccess) {
                echo json_encode([
                    "status" => "success",
                    "message" => "User registered successfully with image upload."
                ]);
            } else {
                echo json_encode([
                    "status" => "success",
                    "message" => "User registered successfully, but image upload failed."
                ]);
            }
        } else {
            // Handle insertion error
            echo json_encode([
                "status" => "error",
                "message" => "Error registering user. Please try again later."
            ]);
        }
    }
} else {
    // Return error if accessed directly without a POST request
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method. Only POST allowed."
    ]);
    exit();
}

$conn->close();
?>
