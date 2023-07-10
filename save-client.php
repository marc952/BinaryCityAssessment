<?php
require_once 'Client.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $name = $_POST['name'];

  // Validate input
  if (empty($name)) {
    echo "Error: Client name is required.";
    exit;
  }

  // Generate client code
  $clientCode = generateClientCode($name);

  // Create a new Client object
  $client = new Client($name, $clientCode);

  // Save the client to the database
  $client->save();
}

function generateClientCode($name) {
  // Generate client code based on the provided name
  $name = strtoupper($name);
  $name = preg_replace('/[^A-Z]/', '', $name);

  if (strlen($name) < 3) {
    $name .= str_repeat('A', 3 - strlen($name));
  } else {
    $name = substr($name, 0, 3);
  }

  // Find a unique client code by checking the database
  $servername = "127.0.0.1";
  $username = "root";
  $password = "";
  $database = "binary_city_db";

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("SELECT COUNT(*) FROM clients WHERE client_code LIKE ?");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  $stmt->close();

  $numericPart = $count + 1;
  $clientCode = $name . str_pad($numericPart, 3, '0', STR_PAD_LEFT);

  $conn->close();

  return $clientCode;
}
?>
<!DOCTYPE html>
<html>
<body>
<br><br>
<a href="index.php">Home</a>
</body>
</html>