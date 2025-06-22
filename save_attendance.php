<?php
$host = "localhost";
$db = "attendance_system";
$user = "root";
$pass = ""; // change if your MySQL password is set

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$qr = $_POST['qr'] ?? '';

if (!$qr) {
    echo "❌ No QR code received.";
    exit;
}

// Step 1: Check if user is registered
$stmt = $conn->prepare("SELECT id, name FROM users WHERE qr_code = ?");
$stmt->bind_param("s", $qr);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "❌ User not found. Please register first.";
} else {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $userName = $user['name'];

    // Step 2: Insert attendance record
    $insert = $conn->prepare("INSERT INTO attendance (user_id) VALUES (?)");
    $insert->bind_param("i", $userId);
    if ($insert->execute()) {
        echo "✅ Welcome, $userName! Attendance marked";
    } else {
        echo "⚠️ Failed to save attendance. Try again.";
    }
}

$conn->close();
?>
