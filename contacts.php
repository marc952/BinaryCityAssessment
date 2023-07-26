<!DOCTYPE html>
<html>
<head>
  <title>Contact List</title>
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
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    tr:last-child td {
      border-bottom: none;
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
  <h1>Contact List</h1>
  <?php
  require_once 'ContactController.php';
  $contactController = new ContactController();
  $contacts = $contactController->index();
  ?>
  <?php if (empty($contacts)) : ?>
    <p> </p>
  <?php else : ?>
    <table>
      <tr>
	    <th>Id</th>
        <th>Name</th>
        <th>Surname</th>
        <th>Email Address</th>
        <th>No. of linked clients</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($contacts as $contact) : ?>
        <tr>
		  <td><?php echo $contact->getId(); ?></td>
          <td><?php echo $contact->getName(); ?></td>
          <td><?php echo $contact->getSurname(); ?></td>
          <td><?php echo $contact->getEmail(); ?></td>
          <td><?php echo $contactController->getNumberOfLinkedClients($contact->getId()); ?></td>
          <td>
            <a href="unlink-contact.php?contact_id=<?php echo $contact->getId(); ?>">Unlink</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
  <a href="index.php">Back</a>
</body>
</html>