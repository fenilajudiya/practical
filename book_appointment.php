<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "practical_task";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$date = $_POST['date'];
$time_slot = $_POST['time_slot'];

// Calculate the start and end of the week for the selected date
$start_of_week = date('Y-m-d', strtotime('monday this week', strtotime($date)));
$end_of_week = date('Y-m-d', strtotime('sunday this week', strtotime($date)));

// Query to check if the user has already booked within the same week
$sql = "SELECT * FROM appointments WHERE email = ? AND date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $email, $start_of_week, $end_of_week);
$stmt->execute();
$result = $stmt->get_result();

// If an appointment already exists for the user in this week, prevent booking
if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'You have already booked an appointment this week.']);
} else {
    // Check if the selected slot is available
    $sql = "SELECT * FROM appointments WHERE date = ? AND time_slot = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $date, $time_slot);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'This time slot is already booked.']);
    } else {
        // Insert new appointment into the database
        $insert_sql = "INSERT INTO appointments (name, email, phone, date, time_slot) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssss", $name, $email, $phone, $date, $time_slot);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Thank you for booking an appointment!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to book appointment.']);
        }
    }
}

$stmt->close();
$conn->close();
?>
