<?php
require_once 'Client.php';
require_once 'connection.php';
class ClientController {
  // Show the list of clients
public function index() {
  $clients = Client::getAll();

  // Check if there are any clients
  if (empty($clients)) {
    echo "No client(s) found.";
  } else {
    // Create an HTML table to display the list of clients
    echo "<table>";
	// Create a table row (header row) with column headers for each field
    echo "<tr><th>Id</th><th>Name</th><th>Client code</th><th>No. of linked contacts</th></tr>";
	// Loop through each client in the $clients array and display their information in a table row
    foreach ($clients as $client) {
	  // Start a new table row for each client
      echo "<tr>";
	  // Display the client ID in a table data cell
      echo "<td>{$client->getId()}</td>";
	  // Display the client's name in a table data cell
      echo "<td>{$client->getName()}</td>";
	  // Display the client's code in a table data cell
      echo "<td>{$client->getClientCode()}</td>";
      // Get the number of linked contacts for the current client using the getNumberOfLinkedContacts method
      $numberOfLinkedContacts = $this->getNumberOfLinkedContacts($client->getId());
	  // Display the number of linked contacts in a table data cell for the current client
      echo "<td>{$numberOfLinkedContacts}</td>";
      echo "<td>";
	  // Check if the number of linked contacts for the current client is greater than zero
	  if ($numberOfLinkedContacts > 0) {
          // Display the unlinking URL only if the client has linked contacts
          $unlinkURL = "unlink-client.php?client_id={$client->getId()}";
		  // Display the "Unlink Contacts" option as a link in a table data cell
          echo "<a href='{$unlinkURL}'>Unlink Contacts</a>";
        }
      echo "</td>";
      echo "</tr>";
    }
    echo "</table>";
  }
}

  // Show the form to create a new client
  public function create() {
    // Display the client creation form
    echo "<form method='POST' action='save-client.php'>";
    echo "Name: <input type='text' name='name'><br>";
    echo "Client code: <input type='text' name='client_code'><br>";
    echo "<input type='submit' value='Save'>";
    echo "</form>";
  }

  // Get the number of linked contacts for a client
  private function getNumberOfLinkedContacts($clientId) {
    $conn = getDbConnection();

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT COUNT(*) FROM client_contact WHERE client_id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $stmt->bind_result($numberOfLinkedContacts);
    $stmt->fetch();
    $stmt->close();

    // Close the connection
    $conn->close();

    return $numberOfLinkedContacts;
  }
}