<?php
// Include the database connection file
include('db_conn.php');

// Create an empty array to hold the data
$response = array();

// Query for Tution Fees
$sql = "SELECT u.student_name, u.class, sf.feetype, sf.feeamount, f.paid_fees, f.fees_paid_on 
        FROM userlogin u 
        LEFT JOIN student_fees sf ON u.class = sf.class 
        LEFT JOIN fees_table f ON u.admission_number = f.admission_no 
        AND JSON_UNQUOTE(JSON_EXTRACT(f.paid_fees, CONCAT('$.\"', sf.feetype, '\"'))) = sf.feeamount 
        WHERE u.user_belongs_group = 2 AND sf.feecategory='Tution Fee' 
        ORDER BY u.class, u.student_name";

$result = mysqli_query($conn, $sql);

if ($result) {
    // Initialize an empty array for Tution Fees data
    $tutionFeesData = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $student_name = htmlspecialchars($row['student_name']);
        $class = htmlspecialchars($row['class']);
        $feetype = htmlspecialchars($row['feetype']);
        $feeamount = number_format($row['feeamount']);
        $paid_fees = json_decode($row['paid_fees'], true);
        $fee_date = !empty($row['fees_paid_on']) ? date('d/m/Y', strtotime($row['fees_paid_on'])) : 'N/A';

        // Determine if the fee is paid or not
        if ($paid_fees && isset($paid_fees[$feetype])) {
            $status = 'Paid';
        } else {
            $status = 'Not paid';
        }

        // Add data to the Tution Fees array
        $tutionFeesData[] = array(
            'student_name' => $student_name,
            'class' => $class,
            'status' => $status,
            'date' => $fee_date,
            'feetype' => $feetype,
            'feeamount' => $feeamount
        );
    }

    // Add Tution Fees data to the response
    $response['tution_fees'] = $tutionFeesData;
} else {
    $response['error'] = 'Error: ' . mysqli_error($conn);
}

// Query for Transport Fees
$sql = "SELECT u.student_name, u.class, sf.feetype, sf.feeamount, f.paid_fees, f.fees_paid_on 
        FROM userlogin u 
        LEFT JOIN student_fees sf ON u.class = sf.class 
        LEFT JOIN fees_table f ON u.admission_number = f.admission_no 
        AND JSON_UNQUOTE(JSON_EXTRACT(f.paid_fees, CONCAT('$.\"', sf.feetype, '\"'))) = sf.feeamount 
        WHERE u.user_belongs_group = 2 AND sf.feecategory='Transport Fee' 
        ORDER BY u.class, u.student_name";

$result = mysqli_query($conn, $sql);

if ($result) {
    // Initialize an empty array for Transport Fees data
    $transportFeesData = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $student_name = htmlspecialchars($row['student_name']);
        $class = htmlspecialchars($row['class']);
        $feetype = htmlspecialchars($row['feetype']);
        $feeamount = number_format($row['feeamount']);
        $paid_fees = json_decode($row['paid_fees'], true);
        $fee_date = !empty($row['fees_paid_on']) ? date('d/m/Y', strtotime($row['fees_paid_on'])) : 'N/A';

        // Determine if the fee is paid or not
        if ($paid_fees && isset($paid_fees[$feetype])) {
            $status = 'Paid';
        } else {
            $status = 'Not paid';
        }

        // Add data to the Transport Fees array
        $transportFeesData[] = array(
            'student_name' => $student_name,
            'class' => $class,
            'status' => $status,
            'date' => $fee_date,
            'feetype' => $feetype,
            'feeamount' => $feeamount
        );
    }

    // Add Transport Fees data to the response
    $response['transport_fees'] = $transportFeesData;
} else {
    $response['error'] = 'Error: ' . mysqli_error($conn);
}

// Query for Activity Fees
$sql = "SELECT u.student_name, u.admission_number, u.class, sf.feetype, sf.feeamount, f.paid_fees, f.fees_paid_on 
        FROM userlogin u 
        LEFT JOIN student_fees sf ON u.class = sf.class 
        LEFT JOIN fees_table f ON u.admission_number = f.admission_no 
        AND JSON_UNQUOTE(JSON_EXTRACT(f.paid_fees, CONCAT('$.\"', sf.feetype, '\"'))) = sf.feeamount 
        WHERE u.user_belongs_group = 2 AND sf.feecategory='Activity Fee' 
        ORDER BY u.class, u.student_name";

$result = mysqli_query($conn, $sql);

if ($result) {
    // Initialize an empty array for Activity Fees data
    $activityFeesData = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $student_name = htmlspecialchars($row['student_name']);
        $admission_number = htmlspecialchars($row['admission_number']);
        $class = htmlspecialchars($row['class']);
        $feetype = htmlspecialchars($row['feetype']);
        $feeamount = number_format($row['feeamount']);
        $paid_fees = json_decode($row['paid_fees'], true);
        $fee_date = !empty($row['fees_paid_on']) ? date('d/m/Y', strtotime($row['fees_paid_on'])) : 'N/A';

        // Determine if the fee is paid or not
        if ($paid_fees && isset($paid_fees[$feetype])) {
            $status = 'Paid';
        } else {
            $status = 'Not paid';
        }

        // Add data to the Activity Fees array
        $activityFeesData[] = array(
            'admission_number' => $admission_number,
            'class' => $class,
            'status' => $status,
            'date' => $fee_date,
            'feetype' => $feetype,
            'feeamount' => $feeamount
        );
    }

    // Add Activity Fees data to the response
    $response['activity_fees'] = $activityFeesData;
} else {
    $response['error'] = 'Error: ' . mysqli_error($conn);
}

// Set the content-type to JSON and output the response
header('Content-Type: application/json');
echo json_encode($response);

?>
