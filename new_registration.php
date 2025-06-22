<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>


<?php include 'connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Registration</title>
  <link rel="icon" type="image/png" href="./images/red_qr_favicon.png">
  <style>
    body {
      background: linear-gradient(45deg, rgba(0,212,255,1) 0%, rgba(11,3,45,1) 100%);
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
      height: 100vh;
      color: rgba(0,212,255,1);
      text-align: center;
      padding: 50px;
    }
    .container {
      background: #f0f5f1;
      max-width: 500px;
      margin: auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    .button {
      background-color: rgba(11,3,45,1);
      color: white;
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      width: 100%;
    }
    .button:hover {
      background-color: rgba(0,212,255,1);
      color: black;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Student Registration</h1>
    <form action="save_user.php" method="POST">
      <input type="text" name="name" placeholder="Name" required>
      <input type="text" name="phone" placeholder="Phone Number" required>
      <input type="text" name="semester" placeholder="Semester" required>
      <input type="text" name="roll" placeholder="College Roll Number" required>
      <input type="date" name="dob" required>
      <button class="button" type="submit">Register & Download ID</button>
    </form>
  </div>
</body>
</html>
