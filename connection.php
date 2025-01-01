<?php
// Connection details
$servername = "localhost";
$username = "root";  // default XAMPP username
$password = "";  // default XAMPP password (empty)
$dbname = "library_management_db"; // Make sure this is your correct database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>