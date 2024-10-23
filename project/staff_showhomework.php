<?php
header('Content-Type: application/json');
include "db_conn.php";

$data = json_decode(file_get_contents('php://input'), true);

$class_name = $data['class_name'];
$section = $data['section'];
$attendance_date = $data['attendance_date']; // Can be null for fetching the latest data

if ($attendance_date) {
    // Fetch homework for a specific date
    $sql = "SELECT * FROM regularhomework WHERE class = ? AND section = ? AND date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $class_name, $section, $attendance_date);
} else {
    $class_name = 4;
    $section = 'A';
    // Fetch the most recent homework entry if no date is provided
    $sql = "SELECT * FROM regularhomework WHERE class = ? AND section = ? ORDER BY date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $class_name, $section);
}

$stmt->execute();
$result = $stmt->get_result();

$homeworks = [];

while ($row = $result->fetch_assoc()) {
    $homework = [
        'date' => $row['date'],
        'subjects' => json_decode($row['homework'], true) // Assuming 'subjects' is stored as JSON in your DB
    ];
    $homeworks[] = $homework;
}

echo json_encode($homeworks);

$stmt->close();
$conn->close();
