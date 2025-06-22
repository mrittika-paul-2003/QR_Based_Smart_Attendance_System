<?php 
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $semester = htmlspecialchars($_POST['semester'], ENT_QUOTES, 'UTF-8');
    $roll = htmlspecialchars($_POST['roll'], ENT_QUOTES, 'UTF-8');
    $dob = htmlspecialchars($_POST['dob'], ENT_QUOTES, 'UTF-8');

    $qr_code = "mmcc-" . $roll;

    // Check for duplicate roll number
    $check = $conn->prepare("SELECT * FROM users WHERE roll = ?");
    $check->bind_param("s", $roll);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Roll number already exists!'); window.location.href='new_registration.php';</script>";
        exit();
    }

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (name, phone, semester, roll, dob, qr_code) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $phone, $semester, $roll, $dob, $qr_code);

    if ($stmt->execute()) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>ID Card</title>
            <style>
                body{
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
                }
            </style>
        </head>
        <body style="text-align:center;padding:50px;font-family:sans-serif;">
            <h2>Registered Successfully!</h2>
            <canvas id="idCardCanvas" width="1000" height="600"></canvas>
            <br><br>
            <a id="downloadBtn" href="#" class="button" style="background:#3dbb57;color:white;padding:10px 20px;border-radius:5px;text-decoration:none;">Download ID Card</a>

            <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
            <script>
                const canvas = document.getElementById('idCardCanvas');
                const ctx = canvas.getContext('2d');

                // Background
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Draw Details
                ctx.fillStyle = 'rgba(11,3,45,1)';
                ctx.font = 'bold 30px Arial';
                ctx.fillText('ID Card', 40, 70);
                ctx.font = '28px Arial';
                ctx.fillText('Name: <?= $name ?>', 30, 120);
                ctx.fillText('Phone: <?= $phone ?>', 40, 220);
                ctx.fillText('Semester: <?= $semester ?>', 40, 290);
                ctx.fillText('Roll: <?= $roll ?>', 40, 360);
                ctx.fillText('DOB: <?= $dob ?>', 40, 430);

                // Generate QR and draw it
                QRCode.toDataURL('<?= $qr_code ?>', { width: 250, margin: 2 }, function(err, url) {
                    const img = new Image();
                    img.onload = function() {
                        ctx.drawImage(img, 780, 40, 180, 180);

                        const imageData = canvas.toDataURL('image/png');

                        // Save to server
                        fetch('save_idcard.php', {
                            method: 'POST',
                            body: JSON.stringify({
                                image: imageData,
                                filename: '<?= $roll ?>-id-card.png'
                            }),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        }).then(res => console.log('ID card saved on server'));

                        // Enable manual download
                        const link = document.getElementById('downloadBtn');
                        link.href = imageData;
                        link.download = '<?= $roll ?>-id-card.png';
                    };
                    img.src = url;
                });
            </script>
        </body>
        </html>
        <?php
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $check->close();
    $conn->close();
}
?>
