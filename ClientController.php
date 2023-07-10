<?php
require_once 'Client.php';
class ClientController {
  // Show the list of clients
public function index() {
  $clients = Client::getAll();

  // Check if there are any clients
  if (empty($clients)) {
    echo "No client(s) found.";
  } else {
    // Display the list of clients
    echo "<table>";
    echo "<tr><th>Id</th><th>Name</th><th>Client code</th><th>No. of linked contacts</th></tr>";
    foreach ($clients as $client) {
      echo "<tr>";
      echo "<td>{$client->getId()}</td>";
      echo "<td>{$client->getName()}</td>";
      echo "<td>{$client->getClientCode()}</td>";
      // Query and display the number of linked contacts for this client
      $numberOfLinkedContacts = $this->getNumberOfLinkedContacts($client->getId());
      echo "<td>{$numberOfLinkedContacts}</td>";
      echo "<td>";
	  if ($numberOfLinkedContacts > 0) {
          // Display the unlinking URL only if the client has linked contacts
          $unlinkURL = "unlink-client.php?client_id={$client->getId()}";
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