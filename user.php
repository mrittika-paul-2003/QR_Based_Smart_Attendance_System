<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>


<?php
// Database connection (modify as needed)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL Query to get all users
$sql = "SELECT id, name, phone, semester, roll, dob, qr_code FROM users";

// Execute the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <link rel="icon" type="image/png" href="./images/red_qr_favicon.png">
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file here -->
    <style>
        body {
            background: linear-gradient(45deg, rgba(0,212,255,1) 0%, rgba(11,3,45,1) 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            border: 1px solid black;
            background-color: white;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color:rgb(114, 119, 212);
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Users List</h1>

        <?php
        // Display the result in a table if records are found
        if ($result->num_rows > 0) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Semester</th>
                            <th>Roll</th>
                            <th>DOB</th>
                            <th>QR Code</th>
                            <th>Action</th> <!-- Added Action column -->
                        </tr>
                    </thead>
                    <tbody>";

            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["phone"] . "</td>
                        <td>" . $row["semester"] . "</td>
                        <td>" . $row["roll"] . "</td>
                        <td>" . $row["dob"] . "</td>
                        <td>" . $row["qr_code"] . "</td>
                        <td>
                            <!-- View ID Card Button -->
                            <a href='view_id_card.php?roll=" . $row["roll"] . "' target='_blank' class='btn'>View ID Card</a>
                        </td>
                      </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "No users found.";
        }

        // Close the database connection
        $conn->close();
        ?>
        
    </div>

</body>
</html>
