<?php 
header('Content-Type: application/json'); // Set the content type to JSON
include "parent-header"; // Include your database connection

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error])); // Return error in JSON format
}

$searchDate = $_GET['searchDate'] ?? ''; // Get search date from query parameter

// SQL query with conditional date filtering
$sql = "SELECT id, event_date, event_title, event_description FROM events"; // Assuming there's an `id` column in the `events` table
if (!empty($searchDate)) {
    $sql .= " WHERE event_date = '" . mysqli_real_escape_string($conn, $searchDate) . "'";
}

$result = $conn->query($sql);

$events = []; // Initialize an array to hold event data
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Append each event to the events array
        $events[] = [
            "id" => $row["id"],
            "event_date" => htmlspecialchars($row["event_date"]), // Ensure HTML safety
            "event_title" => htmlspecialchars($row["event_title"]),
            "event_description" => htmlspecialchars($row["event_description"])
        ];
    }
}

// Return events as JSON
echo json_encode($events);

// Close the connection
$conn->close();
?>
