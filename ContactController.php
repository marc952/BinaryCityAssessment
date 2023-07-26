<?php
require_once 'Contact.php';
require_once 'Client.php';
require_once 'connection.php';

class ContactController {
  // Show the list of contacts
public function index() {
  $contacts = Contact::getAll();

  // Check if there are any contacts
  if (empty($contacts)) {
    echo "<p>No contact(s) found.</p>";
    return; // Exit the method to prevent further execution
  }

  // Generate the HTML table for contacts
  echo "<table>";
  echo "<tr><th>Id</th><th>Name</th><th>Surname</th><th>Email address</th><th>No. of linked clients</th></tr>";
  foreach ($contacts as $contact) {
    echo "<tr>";
    echo "<td>{$contact->getId()}</td>";
    echo "<td>{$contact->getName()}</td>";
    echo "<td>{$contact->getSurname()}</td>";
    echo "<td>{$contact->getEmail()}</td>";
    // Query and display the number of linked clients for this contact
    $numberOfLinkedClients = $this->getNumberOfLinkedClients($contact->getId());
    echo "<td>{$numberOfLinkedClients}</td>";
    echo "<td>";
    if ($numberOfLinkedClients > 0) {
      // Display the unlinking URL only if the contact has linked clients
      $linkedClients = $this->getLinkedClients($contact->getId());
      foreach ($linkedClients as $client) {
        $unlinkURL = "unlink-contact.php?contact_id={$contact->getId()}&client_id={$client->getId()}";
        echo "<a href='{$unlinkURL}'>Unlink {$client->getName()}</a><br>";
      }
    }
    echo "</td>";
    echo "</tr>";
  }
  echo "</table>";
}
  // Show the form to create a new contact
  public function create() {
    // Display the contact creation form
    echo "<form method='POST' action='save-contact.php'>";
    echo "Name: <input type='text' name='name'><br>";
    echo "Surname: <input type='text' name='surname'><br>";
    echo "Email: <input type='email' name='email'><br>";
    echo "<input type='submit' value='Save'>";
    echo "</form>";
  }

  // Get the number of linked clients for a contact
  private function getNumberOfLinkedClients($contactId) {
    $conn = getDbConnection();

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT COUNT(*) FROM client_contact WHERE contact_id = ?");
    $stmt->bind_param("i", $contactId);
    $stmt->execute();
    $stmt->bind_result($numberOfLinkedClients);
    $stmt->fetch();
    $stmt->close();

    // Close the connection
    $conn->close();

    return $numberOfLinkedClients;
  }

  // Link a contact to a client
public function linkToClient($contactId, $clientId) {
  // Check if the contact ID exists
  $contact = Contact::getById($contactId);
  if (!$contact) {
    echo "Error: Contact with ID $contactId does not exist.";
    return;
  }

  // Check if the client ID exists
  $client = Client::getById($clientId);
  if (!$client) {
    echo "Error: Client with ID $clientId does not exist.";
    return;
  }

  $conn = getDbConnection();

  // Prepare the SQL statement
  $stmt = $conn->prepare("INSERT INTO client_contact (client_id, contact_id) VALUES (?, ?)");
  $stmt->bind_param("ii", $clientId, $contactId);

  // Execute the statement
  if ($stmt->execute()) {
    echo "Contact linked to client successfully.";
  } else {
    echo "Error linking contact to client: " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
  // Unlink a contact from a client
  public function unlinkFromClient($contactId, $clientId) {
    $conn = getDbConnection();

    // Prepare the SQL statement
    $stmt = $conn->prepare("DELETE FROM client_contact WHERE contact_id = ? AND client_id = ?");
    $stmt->bind_param("ii", $contactId, $clientId);

    // Execute the statement
    if ($stmt->execute()) {
      echo "Contact unlinked from client successfully.";
    } else {
      echo "Error unlinking contact from client: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }

  public function getLinkedClients($contactId) {
    $conn = getDbConnection();

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT clients.* FROM clients INNER JOIN client_contact ON clients.id = client_contact.client_id WHERE client_contact.contact_id = ?");
    $stmt->bind_param("i", $contactId);
    $stmt->execute();
    $result = $stmt->get_result();

    $linkedClients = array();

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $client = new Client($row['name'], $row['client_code']);
        $client->setId($row['id']);

        $linkedClients[] = $client;
      }
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    return $linkedClients;
  }
}
?>