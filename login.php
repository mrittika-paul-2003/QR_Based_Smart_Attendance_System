<?php
session_start();  // Start the session at the beginning of the script

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "attendance_system";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Simple query to check credentials
    $stmt = $conn->prepare("SELECT id, username, pass FROM admin WHERE username = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        if ($input_password === $admin['pass']) {
            // Login successful
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid username or password.');</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="icon" type="image/png" href="./images/red_qr_favicon.png">
    <style>
        /* Global reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
            background: linear-gradient(45deg, rgba(0,212,255,1) 0%, rgba(11,3,45,1) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
            transition: transform 0.5s ease;
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

        .forgot-password {
            margin-top: 10px;
            font-size: 14px;
            color: rgba(11,3,45,1);
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .hidden {
            transform: translateY(100%);
        }

        .slide-in {
            animation: slideIn 0.5s ease forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateY(100%);
            }
            to {
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<div class="login-container" id="loginContainer">
    <h2>Login</h2>

    <!-- Login form -->
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="login-btn">Log In</button>
    </form>
    <a href="./forget_password.php" class="forgot-password">Forgot Password?</a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var loginContainer = document.getElementById('loginContainer');
        loginContainer.classList.remove('hidden');
        loginContainer.classList.add('slide-in');
    });
</script>

</body>
</html>
