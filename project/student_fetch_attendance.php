<?php
include "db_conn.php";
session_start(); // Ensure the session is started

// Get the month and year from the AJAX request
$month = isset($_POST['month']) ? $_POST['month'] : null;
$admission_no = isset($_SESSION["admission_number"]) ? $_SESSION["admission_number"] : null;

// Check if both month and admission number are set
if ($month && $admission_no) {
    // SQL query to select attendance for the specific student and month
    $sql = "SELECT attendance_date, session, status
            FROM student_attendance
            WHERE regno = '$admission_no'
            AND attendance_date LIKE '$month%'";

    $result = $conn->query($sql);

    $attendance = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $date = $row['attendance_date'];
            $session = $row['session'];
            $status = $row['status'];

            // Initialize the date in the array if not already present
            if (!isset($attendance[$date])) {
                $attendance[$date] = ['FN' => '--', 'AN' => '--'];
            }

            // Update the session value based on the status
            if ($session == 'FN') {
                if ($status == 'P') {
                    $attendance[$date]['FN'] = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
                } elseif ($status == 'A') {
                    $attendance[$date]['FN'] = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
                }
            } elseif ($session == 'AN') {
                if ($status == 'P') {
                    $attendance[$date]['AN'] = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
                } elseif ($status == 'A') {
                    $attendance[$date]['AN'] = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
                }
            }
        }
    }

    // Return the attendance data as JSON
    echo json_encode($attendance);

} else {
    echo json_encode(['error' => 'Invalid month or admission number.']);
}

$conn->close();
