<?php
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['image']) && isset($data['filename'])) {
    $img = $data['image'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $fileData = base64_decode($img);

    // Define save path (you can change this)
    $folder = __DIR__ . '/idcards';
    if (!file_exists($folder)) {
        mkdir($folder, 0755, true); // Create folder if not exists
    }

    $filePath = $folder . '/' . basename($data['filename']);
    file_put_contents($filePath, $fileData);

    echo json_encode(['status' => 'success', 'message' => 'ID card saved']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
?>
