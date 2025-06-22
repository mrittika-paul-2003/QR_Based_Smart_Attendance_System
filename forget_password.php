<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "attendance_system";

// Create DB connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update password logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $new_pass = $_POST['new_password'];

    // Simple update query
    $stmt = $conn->prepare("UPDATE admin SET pass = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_pass, $username);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Password updated successfully!');</script>";
    } else {
        echo "<script>alert('Username not found or password unchanged.');</script>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" type="image/png" href="./images/red_qr_favicon.png">
    <style>
        /* Reusing the same CSS from login page */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(45deg, rgba(0,212,255,1) 0%, rgba(11,3,45,1) 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 28px;
            letter-spacing: 1px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #a18cd1;
            outline: none;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: rgba(0,212,255,1);
            color: rgba(11,3,45,1);
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover {
            background-color: rgba(11,3,45,1);
            color: rgb(255, 255, 255);
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Reset Password</h2>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="text" name="new_password" placeholder="Enter New Password" required>
        <button type="submit" class="login-btn">Update Password</button>
    </form>
</div>

</body>
</html>