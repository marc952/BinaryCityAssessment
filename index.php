<!DOCTYPE html>
<html>
<head>
  <title>Client and Contact Management</title>
  <style>
    .button {
      display: inline-block;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      text-decoration: none;
      border-radius: 4px;
    }

    .button:hover {
      background-color: #45a049;
    }

    .container {
      margin-top: 20px;
    }

    h1, h2 {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Client and Contact Management</h1>
    <div class="container">
      <h2>Clients</h2>
      <a href="create-client.php" class="button">Create New Client</a>
      <br><br>
      <a href="clients.php" class="button">View Clients</a>
    </div>

    <div class="container">
      <h2>Contacts</h2>
      <a href="create-contact.php" class="button">Create New Contact</a>
      <br><br>
      <a href="contacts.php" class="button">View Contacts</a>
    </div>

    <div class="container">
      <h2>Linking Contacts and Clients</h2>
      <a href="link-contact.php" class="button">Link Contacts and Clients</a>
    </div>
  </div>
</body>
</html>
