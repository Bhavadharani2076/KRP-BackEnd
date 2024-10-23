<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
  require 'db_conn.php';

  $class_name = $_POST['class_name'];
  $section = $_POST['section'];
  $exam_name = $_POST['exam_name'];
  $attendance_date = $_POST['attendance_date'];
  $students = json_decode($_POST['students'], true); 

  // Prepare SQL query to insert data
  $stmt = $conn->prepare("INSERT INTO exam_marks (class_name, section, exam_name, attendance_date, register_number, student_name, subjects, total_grade) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

  foreach ($students as $student) {
    // Only keep subjects in the $subjects array
    $subjects = [
      'maths' => $student['maths'],
      'science' => $student['science'],
      'english' => $student['english'],
      'tamil' => $student['tamil'],
      'social_science' => $student['social_science']
    ];

    // Encode subjects as JSON
    $subjectsJson = json_encode($subjects);

    // Bind the parameters
    $stmt->bind_param(
      'ssssssss',
      $class_name,
      $section,
      $exam_name,
      $attendance_date,
      $student['register_number'],
      $student['name'],
      $subjectsJson, // Store only the subjects as JSON
      $student['total_grade']
    );

    if (!$stmt->execute()) {
      echo json_encode(['status' => 'error', 'message' => 'Error inserting data.']);
      exit;
    }
  }

  $stmt->close();
  $conn->close();

  echo json_encode(['status' => 'success', 'message' => 'Data inserted successfully.']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
