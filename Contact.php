<?php
require_once 'connection.php';
class Contact {
  private $id;
  private $name;
  private $surname;
  private $email;

  // Constructor
  public function __construct($name, $surname, $email) {
	// Set the name property of the contact object
    $this->name = $name;
	// Set the surname property of the contact object
    $this->surname = $surname;
	// Set the email property of the contact object
    $this->email = $email;
  }

  // Getters and setters
  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function getSurname() {
    return $this->surname;
  }

  public function getEmail() {
    return $this->email;
  }
  
  public function setId($id) {
    $this->id = $id;
  }

  // Save the contact to the database
  public function save() {
    $conn = getDbConnection();

    // Validate input
    if (empty($this->name)) {
      echo "Error: Contact name is required.";
      return;
    }

    if (empty($this->surname)) {
      echo "Error: Contact surname is required.";
      return;
    }

    if (empty($this->email)) {
      echo "Error: Contact email is required.";
      return;
    } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      echo "Error: Invalid email format.";
      return;
    }

    // Prepare the SQL statement for inserting a new contact into the database
    $stmt = $conn->prepare("INSERT INTO contacts (name, surname, email) VALUES (?, ?, ?)");
	// Bind the values to the prepared statement
    $stmt->bind_param("sss", $this->name, $this->surname, $this->email);

    // Execute the statement
    if ($stmt->execute()) {
      echo "Contact saved successfully.";
    } else {
      echo "Error saving contact: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }

  // Fetch all contacts from the database
  public static function getAll() {
    $conn = getDbConnection();

    // Fetch all contacts from the database and order them by name in ascending order
    $sql = "SELECT * FROM contacts ORDER BY surname, name ASC";
	// Execute the SQL query
    $result = $conn->query($sql);

    // Create an empty array to store the retrieved contacts
    $contacts = array();

	// Check if there are any rows returned from the query
    if ($result->num_rows > 0) {
	  // Loop through each row returned from the query
      while ($row = $result->fetch_assoc()) {
		// Create a new Contact object for each row
        $contact = new Contact($row['name'], $row['surname'], $row['email']);
		// Set the ID of the contact object based on the 'id' column in the row
        $contact->id = $row['id'];
		// Add the contact object to the clients array
        $contacts[] = $contact;
      }
    }

    // Close the connection
    $conn->close();

    return $contacts;
  }
  
  // Get a contact by ID from the database
  public static function getById($id) {
    $conn = getDbConnection();

    // Prepare the SQL statement to select a contact by ID
    $stmt = $conn->prepare("SELECT * FROM contacts WHERE id = ?");
    $stmt->bind_param("i", $id);
	// Execute the SQL statement
    $stmt->execute();
	// Get the result of the executed statement
    $result = $stmt->get_result();

    // Check if there is at least one row returned from the query
    if ($result->num_rows > 0) {
	  // Fetch the first row from the result set
      $row = $result->fetch_assoc();
	  // Create a new Contact object with the values from the row
      $contact = new Contact($row['name'], $row['surname'], $row['email']);
	  // Set the ID of the contact object based on the 'id' column in the row
      $contact->setId($row['id']);
	  // Return the contact object
      return $contact;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    return null; // Return null if no contact is found with the given ID
  }
}