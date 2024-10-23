<?php
include "db_conn.php";

try {
    // Use the admission number from POST data for testing in Thunder Client
    $admission_no = $_POST["admission_number"] ?? $_SESSION["admission_number"]; // Fallback to session variable if not testing

    if (!$admission_no) {
        throw new Exception("Admission number is required.");
    }

    // Fetch the last added row for the given admission number and join with userlogin table
    $sql = "SELECT ft.paid_fees, ul.student_name, ul.city, ul.contact_number, ft.transaction_id, ft.fees_paid_on 
            FROM fees_table ft 
            JOIN userlogin ul ON ft.admission_no = ul.admission_number 
            WHERE ft.admission_no = ? 
            ORDER BY ft.id DESC 
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("s", $admission_no);
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("No fees data found for the admission number.");
    }

    $row = $result->fetch_assoc();
    echo json_encode($row);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}
?>
