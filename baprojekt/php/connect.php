<?php
$servername = "localhost";   // The server is running locally on my machine (localhost)
$username = "root";          // Default XAMPP MySQL username is 'root'
$password = "";              // Default XAMPP MySQL password is empty (no password)
$dbname = "booke";           

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} /*else {
    echo "Connected successfully"; //   for testing if connections successful
}*/
?>