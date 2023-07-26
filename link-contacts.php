<?php
require_once 'ContactController.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $contactId = $_POST['contact_id']; // Assuming you have a form field named 'contact_id'
  $clientId = $_POST['client_id']; // Assuming you have a form field named 'client_id'

  // Create an instance of ContactController and link the contact to the client
  $contactController = new ContactController();
  $contactController->linkToClient($contactId, $clientId);
}
?>

<!-- HTML form to enter the contact and client IDs -->
<!DOCTYPE html>
<html>
<head>
  <title>Client and Contact Successfully Unlinked</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    h1 {
      margin-bottom: 20px;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #4caf50;
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
    }

    a:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <h1>Client and Contact Successfully Unlinked</h1>
  <a href="index.php">Back</a>
</body>
</html>
