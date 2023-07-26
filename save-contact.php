<?php
require_once 'Contact.php';
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $name = $_POST['name'];
  $surname = $_POST['surname'];
  $email = $_POST['email'];

  // Validate input
  if (empty($name)) {
    echo "Error: Contact name is required.";
    exit;
  }

  if (empty($surname)) {
    echo "Error: Contact surname is required.";
    exit;
  }

  if (empty($email)) {
    echo "Error: Contact email is required.";
    exit;
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Error: Invalid email format.";
    exit;
  }

  // Create a new Contact object
  $contact = new Contact($name, $surname, $email);

  // Save the contact to the database
  $contact->save();
}
?>
<!DOCTYPE html>
<html>
<body>
<br><br>
<a href="index.php">Home</a>
</body>
</html>