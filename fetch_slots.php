<?php
require 'db_connect.php';

// Get the date selected by the user
$date = $_POST['date'];

// Define all available time slots for the day (10:00 AM - 7:00 PM)
$available_slots = [
    '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00',
    '15:00:00', '16:00:00', '17:00:00', '18:00:00', '19:00:00'
];

// Fetch already booked time slots for the selected date from the database
$sql = "SELECT time_slot FROM appointments WHERE date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

// Store booked time slots in an array
$booked_slots = [];
while ($row = $result->fetch_assoc()) {
    $booked_slots[] = $row['time_slot'];
}

// Close the database connection
$stmt->close();
$conn->close();

// Generate the time slot options for the dropdown
for ($i = 0; $i < count($available_slots) - 1; $i++) {
    $start_time = $available_slots[$i];
    $end_time = $available_slots[$i + 1];

    // Format the time from 'H:i:s' to 'h A' format for display (e.g., "10 AM to 11 AM")
    $formatted_start = date("g A", strtotime($start_time));
    $formatted_end = date("g A", strtotime($end_time));

    // If the time slot is already booked, disable the option in the dropdown
    if (in_array($start_time, $booked_slots)) {
        echo "<option value='$start_time' disabled>$formatted_start to $formatted_end (Booked)</option>";
    } else {
        echo "<option value='$start_time'>$formatted_start to $formatted_end</option>";
    }
}
?>
