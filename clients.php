<!DOCTYPE html>
<html>
<head>
  <title>Client List</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    h1 {
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
    }

    a {
      display: inline-block;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      text-decoration: none;
      border-radius: 4px;
    }

    a:hover {
      background-color: #45a049;
    }

    .empty-message {
      margin-top: 20px;
      font-style: italic;
      color: #888;
    }
  </style>
</head>
<body>
  <h1>Client List</h1>
  <?php
  require_once 'ClientController.php';
  $clientController = new ClientController();
  $clients = $clientController->index();
  ?>
  <?php if (empty($clients)) : ?>
    <p class="empty-message"> </p>
  <?php else : ?>
    <table>
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Client Code</th>
        <th>No. of Linked Contacts</th>
      </tr>
      <?php foreach ($clients as $client) : ?>
        <tr>
          <td><?php echo $client->getId(); ?></td>
          <td><?php echo $client->getName(); ?></td>
          <td><?php echo $client->getClientCode(); ?></td>
          <td><?php echo $clientController->getNumberOfLinkedContacts($client->getId()); ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
  <a href="index.php">Back</a>
</body>
</html>