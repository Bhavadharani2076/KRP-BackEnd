<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

include "db_conn.php";

$data = file_get_contents('php://input');
$json_data = json_decode($data, true);

$RequestMethod = $_SERVER["REQUEST_METHOD"];

if ($RequestMethod == "POST") {
    $admission_no = addslashes(trim($_REQUEST['admission_no']));
    $password = addslashes(trim($_REQUEST['password']));
    $platform = 'web';

    // Check in userlogin table
    $CheckUserQuery = "SELECT * FROM userlogin WHERE (admission_number = '$admission_no' OR parent_adno = '$admission_no') AND password = '$password'";
    $CheckUserQueryResults = mysqli_query($conn, $CheckUserQuery);

    if ($CheckUserQueryResults && mysqli_num_rows($CheckUserQueryResults) > 0) {
        // User found in userlogin table
        $record = mysqli_fetch_assoc($CheckUserQueryResults);
        $AccountType = "";

        // Determine the user_belongs_group based on which admission_no is used
        if ($record['admission_number'] == $admission_no) {
            $user_belongs_group = "2"; // Student
        }elseif ($record['parent_adno'] == $admission_no) {
            $user_belongs_group = "3"; // Parent
        }else {
            $user_belongs_group = "Unknown";
        }

        if ($record["user_belongs_group"] == "1") {
            $AccountType = "Admin";
        } elseif ($user_belongs_group == "2") {
            $AccountType = "Student";
        } elseif ($user_belongs_group == "3") {
            $AccountType = "Parent";
        }
        $admission_no = $record['admission_number'];

    } else {
        // If no user found in userlogin, check in staff_details table
        $CheckStaffQuery = "SELECT * FROM staff_details WHERE staff_id = '$admission_no' AND password = '$password'";
        $CheckStaffQueryResults = mysqli_query($conn, $CheckStaffQuery);

        if ($CheckStaffQueryResults && mysqli_num_rows($CheckStaffQueryResults) > 0) {
            // User found in staff_details table
            $record = mysqli_fetch_assoc($CheckStaffQueryResults);
            $user_belongs_group = "4"; // Faculty
            $AccountType = "Faculty";
        } else {
            $Data = [
                'status' => 404,
                'message' => 'No User Found'
            ];
        
            header("HTTP/1.0 404 No User Found");
            echo json_encode($Data);
            exit;
        }
        $admission_no = $record['staff_id'];
    }

    if ($platform == "web") {
        $_SESSION["user_logged_in"] = true;
        $_SESSION["user_id"] = $record["id"];
        $_SESSION["user_name"] = isset($record["student_name"]) ? $record["student_name"] : $record["staff_name"];
        $_SESSION["user_email"] = $record["email"];
        $_SESSION["admission_number"] = $admission_no;

        $Data = [
            'status' => 200,
            'message' => 'Success',
            'user_type' => $AccountType,
            'staff_name' => $_SESSION["user_name"] 
        ];

        header("HTTP/1.0 200 Success");
        echo json_encode($Data);
    } else {
        $Data = [
            'status' => 200,
            'message' => 'Login Success',
            'user_logged_in' => 'true',
            'user_id' => $record["id"],
            'user_name' => isset($record["student_name"]) ? $record["student_name"] : $record["staff_name"],
            'user_email' => $record["email"],
            'user_type' => $AccountType,
            'user_profile_image' => PROFILE . $record["photo"]
        ];

        header("HTTP/1.0 200 Success");
        echo json_encode($Data);
    }

} else {
    $Data = [
        'status' => 405,
        'message' => $RequestMethod . ' Method Not Allowed'
    ];

    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($Data);
}
?>
