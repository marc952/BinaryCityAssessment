<?php
function getDbConnection() {
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "binary_city_db";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>