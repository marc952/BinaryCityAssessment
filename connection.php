<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";

// Create a connection
$conn = new mysqli($servername, $username, $password);
$conn->select_db("your_database_name");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";

?>