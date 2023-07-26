<?php
require_once 'Client.php';
require_once 'connection.php';

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
  // Convert the name to uppercase for consistency
  $name = strtoupper($name);
  // Remove any characters that are not uppercase letters from the name
  $name = preg_replace('/[^A-Z]/', '', $name);

  // Check the length of the cleaned name
  if (strlen($name) < 3) {
	// If the name has fewer than 3 characters, pad it with 'A's to create a 3-character client code
    $name .= str_repeat('A', 3 - strlen($name));
  } else {
	// If the name has 3 or more characters, truncate it to the first 3 characters
    $name = substr($name, 0, 3);
  }

  // Find a unique client code by checking the database
  $conn = getDbConnection();

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