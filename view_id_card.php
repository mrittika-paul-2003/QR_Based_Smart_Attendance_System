<?php
include 'connection.php';

if (isset($_GET['roll'])) {
    $roll = $_GET['roll'];

    // Query to fetch user data
    $sql = "SELECT name, phone, semester, roll, dob, qr_code FROM users WHERE roll = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $roll);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Display ID card (simple design for example)
        echo "<html><head><title>ID Card for " . $user['name'] . "</title></head><body style='text-align:center;'>";

        ?>
        
    <img src="./idcards/<?= $user['roll'] ?>-id-card.png"  alt="ID Card"  style="width: 600px; height: auto; display: block; margin: 0 auto;">

        <?php

    } else {
        echo "User not found.";
    }

    $stmt->close();
    $conn->close();
}
?>