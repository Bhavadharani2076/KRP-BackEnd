<?php
include "db_conn.php";

header('Content-Type: application/json'); // Set the header for JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the last admission number from the database
    $lastAdmissionSQL = "SELECT MAX(admission_number) AS last_admission FROM `userlogin` WHERE user_belongs_group = 2";
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

    // If there is no previous data in the database, start from 5000
    $admissionNumber = empty($lastAdmissionNumber) ? 5000 : $lastAdmissionNumber + 1;
    $parentad_no = "p$admissionNumber";

    $stu_name = $_POST['stu_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = 'Welcome';
    $dob = $_POST['dob'];
    $class = $_POST['class'];
    $blood = $_POST['blood'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $user_group = "2";

    // Handle the image upload
    if (isset($_FILES['student_img']) && $_FILES['student_img']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['student_img']['tmp_name'];
        $imageName = $_FILES['student_img']['name'];
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $imageNewName = 'student_' . $admissionNumber . '.' . $imageExtension;

        // Specify the directory where the image will be saved
        $uploadFileDir = '../student_image/';
        $destPath = $uploadFileDir . $imageNewName;

        // Move the image to the destination directory
        $imageUploadSuccess = move_uploaded_file($imageTmpPath, $destPath);
    } else {
        $imageUploadSuccess = false;
    }

    // Check if the Admission Number already exists
    $checkExistingSQL = "SELECT * FROM `userlogin` WHERE admission_number = '$admissionNumber'";
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
        $insertSQL = "INSERT INTO `userlogin` (admission_number, parent_adno, student_name, email, contact_number, password, date_of_birth, class, blood_group, gender, address, country, city, pincode, user_belongs_group, image_name) 
                      VALUES ('$admissionNumber','$parentad_no', '$stu_name', '$email', '$phone', '$password', '$dob', '$class', '$blood', '$gender', '$address', '$country', '$city', '$pincode', '$user_group', '$imageNewName')";
        
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
