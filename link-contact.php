<?php
require_once 'ContactController.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $contactId = $_POST['contact_id']; 
  $clientId = $_POST['client_id']; 

  // Create an instance of ContactController and link the contact to the client
  $contactController = new ContactController();
  $contactController->linkToClient($contactId, $clientId);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Link Contact and Client</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    h1 {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
    }

    input[type="text"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    input[type="submit"] {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #4caf50;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      color: #888;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <h1>Link Contact and Client</h1>
  <form method="POST" action="link-contact.php">
    <label for="contact_id">Contact ID:</label>
    <input type="text" name="contact_id" id="contact_id">
    <br>
    <label for="client_id">Client ID:</label>
    <input type="text" name="client_id" id="client_id">
    <br>
    <input type="submit" value="Link">
  </form>
  <a href="index.php">Back</a>
</body>
</html>