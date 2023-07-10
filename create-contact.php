<!DOCTYPE html>
<html>
<head>
  <title>Create Contact</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    h1 {
      margin-bottom: 20px;
    }

    form {
      width: 300px;
    }

    label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="email"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    input[type="submit"] {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #45a049;
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
  <h1>Create Contact</h1>
  <form method="POST" action="save-contact.php">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>
    <label for="surname">Surname:</label>
    <input type="text" id="surname" name="surname" required><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>
    <input type="submit" value="Save">
  </form>
  <a href="index.php">Back</a>
</body>
</html>