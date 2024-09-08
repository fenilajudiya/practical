<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "practical_task";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
