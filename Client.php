<?php
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

  // Check if the client name is shorter than 3 characters
  if (strlen($this->name) < 3) {
    // If the client name is shorter than 3 characters, fill up with alpha characters
    $codePrefix = strtoupper(substr($this->name, 0, 3));
    $alphaPartIndex = 0;

    // Append the first alphabet from the $alphaPart array to the code prefix
    $codePrefix .= $alphaPart[$alphaPartIndex];
    $alphaPartIndex++;

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

    // Increment the alpha part until a unique code is found
    while ($count > 0) {
	  // Check if the alpha part index has reached the end of the array
      if ($alphaPartIndex >= count($alphaPart)) {
        // Reset the alpha part index and increment the numeric part
        $alphaPartIndex = 0;
        $numericPart++;
      }

	  // Generate a new code prefix with the first two characters of the name and the next alphabet
      $codePrefix = strtoupper(substr($this->name, 0, 2)) . $alphaPart[$alphaPartIndex];
      $numericPart = 1;

      // Check if the generated code is unique
      $stmt = $conn->prepare("SELECT COUNT(*) FROM clients WHERE client_code = ?");
      $stmt->bind_param("s", $codePrefix . str_pad($numericPart, 3, '0', STR_PAD_LEFT));
	  // Execute the prepared statement
      $stmt->execute();
	  // Bind the result to a variable
      $stmt->bind_result($count);
	  // Fetch the result
      $stmt->fetch();
	  // Close the prepared statement
      $stmt->close();

      // Increment the alpha part or numeric part until a unique code is found
      while ($count > 0) {
		// Check if the numeric part has reached the maximum value
        if ($numericPart >= 999) {
          // Reset the numeric part and increment the alpha part
          $numericPart = 1;
          $alphaPartIndex++;
        } else {
		  // Increment the numeric part
          $numericPart++;
        }

		// Generate a new code prefix with the first two characters of the name and the updated alpha and numeric parts
        $codePrefix = strtoupper(substr($this->name, 0, 2)) . $alphaPart[$alphaPartIndex];

        // Check if the generated code is unique
        $stmt = $conn->prepare("SELECT COUNT(*) FROM clients WHERE client_code = ?");
        $stmt->bind_param("s", $codePrefix . str_pad($numericPart, 3, '0', STR_PAD_LEFT));
		// Execute the prepared statement
        $stmt->execute();
		// Bind the result to a variable
        $stmt->bind_result($count);
		// Fetch the result
        $stmt->fetch();
		// Close the prepared statement
        $stmt->close();
      }
    }
  } else {
    // If the client name is 3 or more characters, use the first 3 characters
    $codePrefix = strtoupper(substr($this->name, 0, 3));

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
      $codePrefix = strtoupper(substr($this->name, 0, 3)) . str_pad($numericPart, 3, '0', STR_PAD_LEFT);

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
  }

  // Set the generated client code for the current Client object
  $this->clientCode = $codePrefix . str_pad($numericPart, 3, '0', STR_PAD_LEFT);
}
// Get a client by ID from the database
public static function getById($id) {
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