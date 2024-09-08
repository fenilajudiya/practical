<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "practical_task";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$available_slots = [
    '10:00:00',
    '11:00:00',
    '12:00:00',
    '13:00:00',
    '14:00:00',
    '15:00:00',
    '16:00:00',
    '17:00:00',
    '18:00:00'
];

$total_slots = count($available_slots);

$sql = "SELECT date, COUNT(*) as booked_slots 
        FROM appointments 
        GROUP BY date 
        HAVING booked_slots >= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $total_slots);
$stmt->execute();
$result = $stmt->get_result();

$fully_booked_dates = [];

while ($row = $result->fetch_assoc()) {
    $fully_booked_dates[] = $row['date']; // Add the fully booked date
}

$stmt->close();
$conn->close();

// Return the fully booked dates as JSON
echo json_encode($fully_booked_dates);
