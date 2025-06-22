<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtered Attendance Records</title>
    <link rel="icon" type="image/png" href="./images/red_qr_favicon.png">
    <link rel="icon" href="./images/attendance-icon.png" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.0/xlsx.full.min.js"></script>
    <style>
        body {
            background: linear-gradient(45deg, rgba(0,212,255,1) 0%, rgba(11,3,45,1) 100%);
            height: 100vh;
            padding: 20px;;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
        }

        h1 {
            font-size: 50px;
            width: 100%;
            text-align: center;
            background: #fff;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 20px;
            margin: auto;
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

        button {
            background: -webkit-linear-gradient(#03C04A, #234F1E);
            color: #fff;
            margin: auto;
            font-size: 25px;
            width: 250px;
            height: 40px;
            display: block;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Filtered Attendance Records</h1>

    <table id="attendanceTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Semester</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "attendance_system";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Initialize conditions for filtering
        $conditions = array();

        // Check if the name filter is set
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $name = $conn->real_escape_string($_POST['name']);
            $conditions[] = "u.name LIKE '%$name%'";
        }

        // Check if the semester filter is set
        if (isset($_POST['student_sem']) && !empty($_POST['student_sem'])) {
            $student_sem = $conn->real_escape_string($_POST['student_sem']);
            $conditions[] = "u.semester = '$student_sem'";
        }

        // Construct the SQL query with the dynamic WHERE clause
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
        ";

        // Add the WHERE clause if there are any conditions
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        // Output the query for debugging (remove or comment out after testing)
        // echo $sql;

        // Execute the query
        $result = $conn->query($sql);

        // Display the result in the table
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['semester'] . "</td>";
                echo "<td>" . $row['timestamp'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No records found with the current filter.</td></tr>";
        }

        // Close the connection
        $conn->close();
        ?>
        </tbody>
    </table>

    <button onclick="exportToExcel()">Download as Excel</button>

    <script>
        function exportToExcel() {
            var table = document.getElementById("attendanceTable");
            var wb = XLSX.utils.table_to_book(table, {sheet: "Attendance"});

            var wbout = XLSX.write(wb, {bookType: 'xlsx', bookSST: true, type: 'binary'});

            function s2ab(s) {
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            var blob = new Blob([s2ab(wbout)], {type: 'application/octet-stream'});
            var fileName = 'filtered_attendance_records.xlsx';
            if (navigator.msSaveBlob) {
                navigator.msSaveBlob(blob, fileName);
            } else {
                var link = document.createElement('a');
                if (link.download !== undefined) {
                    var url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', fileName);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }
        }
    </script>
</body>
</html>
