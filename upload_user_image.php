<?php
header('Content-Type: application/json');
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    if (isset($_FILES['image'])) {
        $image = $_FILES['image'];
        $imageName = basename($image['name']);
        $imagePath = 'user_images/' . $imageName;

        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            // Verificar si el usuario ya existe
            $sql = "SELECT * FROM user_images WHERE username=? AND email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Si el usuario existe, actualizar la imagen
                $sql = "UPDATE user_images SET image=? WHERE username=? AND email=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $imageName, $username, $email);
            } else {
                // Si el usuario no existe, insertar un nuevo registro
                $sql = "INSERT INTO user_images (username, email, image) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $username, $email, $imageName);
            }

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'imageUrl' => $imagePath]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save image information to the database']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No image file provided']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
