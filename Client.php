<?php
require_once 'connection.php';
class Client {
  private $id;
  private $name;
  private $clientCode;

  // Constructor
  public function __construct($name, $clientCode) {
	// Set the name property of the client object
    $this->name = $name;
	// Set the clientCode property of the client object
    $this->clientCode = $clientCode;
  }

  // Getters and setters
  public function getId() {
    return $this->id;
  }
  
  public function setId($id) {
    $this->id = $id;
  }

  public function getName() {
    return $this->name;
  }

  public function getClientCode() {
    return $this->clientCode;
  }

  // Save the client to the database
  public function save() {
    $conn = getDbConnection();

    // Validate input
    if (empty($this->name)) {
      echo "Error: Client name is required.";
      return;
    }

    // Generate a unique client code
    $this->generateUniqueClientCode($conn);

    // Prepare the SQL statement for inserting a new client into the database
    $stmt = $conn->prepare("INSERT INTO clients (name, client_code) VALUES (?, ?)");
	// Bind the values to the prepared statement
    $stmt->bind_param("ss", $this->name, $this->clientCode);

    // Execute the statement
    if ($stmt->execute()) {
      echo "Client saved successfully.";
    } else {
      echo "Error saving client: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }

  // Fetch all clients from the database
  public static function getAll() {
    $conn = getDbConnection();

    // Select all clients from the database and order them by name in ascending order
    $sql = "SELECT * FROM clients ORDER BY name ASC";
	// Execute the SQL query
    $result = $conn->query($sql);

	// Create an empty array to store the retrieved clients
    $clients = array();

	// Check if there are any rows returned from the query
    if ($result->num_rows > 0) {
	  // Loop through each row returned from the query
      while ($row = $result->fetch_assoc()) {
		// Create a new Client object for each row
        $client = new Client($row['name'], $row['client_code']);
		// Set the ID of the client object based on the 'id' column in the row
        $client->id = $row['id'];
		// Add the client object to the clients array
        $clients[] = $client;
      }
    }

    // Close the connection
    $conn->close();

    return $clients;
  }

// Generate a unique client code for the current Client object
private function generateUniqueClientCode($conn) {
  // Create an array of alphabets from 'A' to 'Z'
  $alphaPart = range('A', 'Z');
  // Initialize the numeric part of the code
  $numericPart = 1;

  // Split the client name into words
  $nameWords = explode(' ', $this->name);
  // Get the first letter of each word and join them together to create the code prefix
  $codePrefix = '';
  foreach ($nameWords as $word) {
    $codePrefix .= strtoupper(substr($word, 0, 1));
  }

  // Check if the generated code is unique
  $stmt = $conn->prepare("SELECT COUNT(*) FROM clients WHERE client_code = ?");
  $stmt->bind_param("s", $codePrefix);
  // Execute the prepared statement
  $stmt->execute();
  // Bind the result to a variable
  $stmt->bind_result($count);
  // Fetch the result
  $stmt->fetch();
  // Close the prepared statement
  $stmt->close();

  // Increment the numeric part until a unique code is found
  while ($count > 0) {
    $numericPart++;
    $codePrefix = strtoupper(implode('', array_map(function ($word) {
      return substr($word, 0, 1);
    }, $nameWords))) . str_pad($numericPart, 3, '0', STR_PAD_LEFT);

    $stmt = $conn->prepare("SELECT COUNT(*) FROM clients WHERE client_code = ?");
    $stmt->bind_param("s", $codePrefix);
    // Execute the prepared statement
    $stmt->execute();
    // Bind the result to a variable
    $stmt->bind_result($count);
    // Fetch the result
    $stmt->fetch();
    // Close the prepared statement
    $stmt->close();
  }

  // Set the generated client code for the current Client object
  $this->clientCode = $codePrefix . str_pad($numericPart, 3, '0', STR_PAD_LEFT);
}
// Get a client by ID from the database
public static function getById($id) {
    $conn = getDbConnection();

    // Prepare the SQL statement to select a client by ID
    $stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->bind_param("i", $id);
	// Execute the SQL statement
    $stmt->execute();
	// Get the result of the executed statement
    $result = $stmt->get_result();

	// Check if there is at least one row returned from the query
    if ($result->num_rows > 0) {
	  // Fetch the first row from the result set
      $row = $result->fetch_assoc();
	  // Create a new Client object with the values from the row
      $client = new Client($row['name'], $row['client_code']);
	  // Set the ID of the client object based on the 'id' column in the row
      $client->setId($row['id']);
	  // Return the client object
      return $client;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    return null; // Return null if no client is found with the given ID
  }
}