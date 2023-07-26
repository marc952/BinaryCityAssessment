<?php
require_once 'Contact.php';
require_once 'Client.php';
require_once 'connection.php';

// Check if the client ID is provided
if (isset($_GET['client_id'])) {
  $clientId = $_GET['client_id'];

  // Check if the client exists
  $client = Client::getById($clientId);
  if (!$client) {
    echo "Error: Client with ID $clientId does not exist.";
    exit;
  }

  // Fetch the linked contacts of the client
  $linkedContacts = getLinkedContacts($clientId);

  // Display the list of linked contacts
  echo "<h1>Linked Contacts for Client: {$client->getName()}</h1>";
  echo "<table>";
  echo "<tr><th>Id</th><th>Name</th><th>Surname</th><th>Email</th><th>Action</th></tr>";
  foreach ($linkedContacts as $contact) {
    echo "<tr>";
    echo "<td>{$contact->getId()}</td>";
    echo "<td>{$contact->getName()}</td>";
    echo "<td>{$contact->getSurname()}</td>";
    echo "<td>{$contact->getEmail()}</td>";
    echo "<td><a href='unlink-contact.php?contact_id={$contact->getId()}&client_id={$client->getId()}'>Unlink</a></td>";
    echo "</tr>";
  }
  echo "</table>";
} else {
  echo "Error: Client ID not provided.";
  exit;
}

// Function to fetch the linked contacts of a client
function getLinkedContacts($clientId) {
  
  $conn = getDbConnection();

  // Prepare the SQL statement to fetch linked contacts
  $stmt = $conn->prepare("SELECT contacts.* FROM contacts INNER JOIN client_contact ON contacts.id = client_contact.contact_id WHERE client_contact.client_id = ?");
  $stmt->bind_param("i", $clientId);
  $stmt->execute();
  $result = $stmt->get_result();

  // Initialize an array to store the linked contacts
  $linkedContacts = array();

  // Check if the query result contains any rows (contacts)
  if ($result->num_rows > 0) {
	// Start a while loop to iterate through each row of the query result
    while ($row = $result->fetch_assoc()) {
	  // Retrieve the contact's name, surname, and email from the row
      $contact = new Contact($row['name'], $row['surname'], $row['email']);
	  // Set the contact's ID using the 'id' field from the row
      $contact->setId($row['id']);
	  // Add the current Contact object to the $linkedContacts array
      $linkedContacts[] = $contact;
    }
	 // The while loop will continue until all rows in the query result have been processed
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();

  return $linkedContacts;
}
?>