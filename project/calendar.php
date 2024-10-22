<?php
include "db_conn.php";// Assuming you have a file for your DB connection

// Fetch the events from the database
$query = "SELECT * FROM calender";  // Assuming your table name is 'calender'
$result = mysqli_query($conn, $query);

// Initialize an empty array to store the events
$myEvents = [];

// Checking if the query was successful
if ($result) {
    // Fetch associative array of the result row by row
    while ($row = mysqli_fetch_assoc($result)) {

        $everyYear = $row['every_year'] == 1 ? true : false;

        // Convert start date to required format
        $startDate = new DateTime($row['date'], new DateTimeZone("Asia/Kolkata"));
        $formattedStartDate = $startDate->format("m/d/Y");

        // Check if end_date is empty or null
        if (empty($row['end_date'])) {
            $formattedDate = $formattedStartDate; // Use only the start date
        } else {
            // Convert end date to required format
            $endDate = new DateTime($row['end_date'], new DateTimeZone("Asia/Kolkata"));
            $formattedEndDate = $endDate->format("m/d/Y");
            $formattedDate = [$formattedStartDate, $formattedEndDate]; // Use both dates
        }

        // Construct the event object
        $event = [
            'id' => $row['id'],
            'name' => $row['name'],
            'badge' => $row['badge'],
            'date' => $formattedDate,
            'description' => $row['description'],
            'everyYear' => $everyYear,
            'type' => $row['type']
        ];

        // Add the event to the myEvents array
        $myEvents[] = $event;
    }
}

// Convert the PHP array to JSON format for use in the frontend
$myEventsJson = json_encode($myEvents);

// Close the database connection
mysqli_close($conn);
?>
