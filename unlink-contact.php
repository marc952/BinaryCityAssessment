<?php
require_once 'ContactController.php';

// Instantiate the ContactController
$contactController = new ContactController();

// Check if the contact_id and client_id are provided in the query parameters
if (isset($_GET['contact_id']) && isset($_GET['client_id'])) {
  $contactId = $_GET['contact_id'];
  $clientId = $_GET['client_id'];

  // Unlink the contact from the client
  $contactController->unlinkFromClient($contactId, $clientId);

  // Redirect back to the linked contacts page
  header("Location: link-contacts.php");
  exit;
} else {
  // Redirect back to the linked contacts page if the necessary parameters are not provided
  header("Location: link-contacts.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Linked Contacts</title>
</head>
<body>
  <h1>Linked Contacts</h1>
  <?php if (empty($linkedContacts)) : ?>
    <p>No linked contacts found.</p>
  <?php else : ?>
    <form method="POST" action="link-contact.php">
      <table>
        <tr>
          <th>Id</th>
          <th>Name</th>
          <th>Surname</th>
          <th>Email Address</th>
          <th>Unlink</th>
        </tr>
        <?php foreach ($linkedContacts as $contact) : ?>
          <tr>
            <td><?php echo $contact->getId(); ?></td>
            <td><?php echo $contact->getName(); ?></td>
            <td><?php echo $contact->getSurname(); ?></td>
            <td><?php echo $contact->getEmail(); ?></td>
            <td>
              <input type="checkbox" name="contacts[]" value="<?php echo $contact->getId(); ?>">
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
      <input type="submit" value="Unlink Selected">
    </form>
  <?php endif; ?>
</body>
</html>