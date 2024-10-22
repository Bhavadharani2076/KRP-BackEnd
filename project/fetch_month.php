<?php
// Include the database connection

include "db_conn.php";

// Retrieve the month index from the POST request
$monthIndex = $_POST['monthIndex'];

// Create the SQL query to fetch events for the given month
$query = "SELECT * FROM calender WHERE MONTH(date) = $monthIndex";
$result = mysqli_query($conn, $query);

// Initialize an empty array for the response
$events = [];

// Checking if the query was successful
if ($result) {
    // Fetch associative array row by row
    while ($row = mysqli_fetch_assoc($result)) {
        $event = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'date' => $row['date']
        ];

        // Add the event to the events array
        $events[] = $event;
    }
}

// Return the events as JSON
echo json_encode($events);

// Close the database connection
mysqli_close($conn);
?>
