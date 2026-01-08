<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_system";

// Create connection with error reporting disabled in production
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed. Please contact administrator.");
}

// Set charset to prevent SQL injection via charset vulnerabilities
$conn->set_charset("utf8mb4");

// Enable error reporting for development (disable in production)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>