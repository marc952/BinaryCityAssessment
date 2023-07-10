<?php
require_once 'Contact.php';
require_once 'Client.php';

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

  // Prepare the SQL statement to fetch linked contacts
  $stmt = $conn->prepare("SELECT contacts.* FROM contacts INNER JOIN client_contact ON contacts.id = client_contact.contact_id WHERE client_contact.client_id = ?");
  $stmt->bind_param("i", $clientId);
  $stmt->execute();
  $result = $stmt->get_result();

  $linkedContacts = array();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $contact = new Contact($row['name'], $row['surname'], $row['email']);
      $contact->setId($row['id']);

      $linkedContacts[] = $contact;
    }
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();

  return $linkedContacts;
}
?>