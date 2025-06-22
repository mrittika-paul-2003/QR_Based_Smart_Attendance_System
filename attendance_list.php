<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>


<?php
// Step 1: Database connection (modify these variables to match your setup)
$servername = "localhost";  // Database host
$username = "root";         // Database username
$password = "";             // Database password
$dbname = "attendance_system"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: SQL Query to get attendance data joined with user details
$sql = "
    SELECT 
        a.id, 
        a.user_id, 
        u.name, 
        u.semester, 
        a.timestamp
    FROM 
        attendance a
    INNER JOIN 
        users u ON a.user_id = u.id
    ORDER BY 
        a.timestamp 
";

// Step 3: Execute the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance List</title>
    <link rel="icon" type="image/png" href="./images/red_qr_favicon.png">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
            font-family: Arial, sans-serif;
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
            border: 1px solid black;
            background-color: white;
        }
        th, td {
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
</style>
<body>
    <div class="container">
        <h1>Attendance List</h1>
        
        <?php
        // Step 4: Display the result in a table if records are found
        if ($result->num_rows > 0) {
            echo "<table border='1'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Semester</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>";

            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["user_id"] . "</td>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["semester"] . "</td>
                        <td>" . $row["timestamp"] . "</td>
                      </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "No attendance records found.";
        }

        // Close the database connection
        $conn->close();
        ?>

    </div>
</body>
</html>
