<?php 
include "db_conn.php";

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$searchDate = $_GET['searchDate'] ?? ''; // Get search date from query parameter

// SQL query with conditional date filtering
$sql = "SELECT id, event_date, event_title, event_description FROM events"; // Assuming there's an `id` column in the `events` table
if (!empty($searchDate)) {
    $sql .= " WHERE event_date = '" . mysqli_real_escape_string($conn, $searchDate) . "'";
}

$result = $conn->query($sql);

// Handle events output
$events = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = [
            "event_date" => htmlspecialchars($row["event_date"]), // Ensure HTML safety
            "event_title" => htmlspecialchars($row["event_title"]),
            "event_description" => htmlspecialchars($row["event_description"]),
        ];
    }
}

// Respond with JSON
header('Content-Type: application/json');
echo json_encode($events);

$conn->close();
?>
