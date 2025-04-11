<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST");

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);

        // Verifica si el directorio de subida existe, si no, lo crea
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Mueve el archivo subido al directorio de destino
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            echo json_encode(['status' => 'success', 'message' => 'Image uploaded successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No image uploaded or there was an error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
