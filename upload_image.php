<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST");

require 'db.php';

// Subir imagen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    // Directorio donde se guardarán las imágenes
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si el archivo es una imagen
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo json_encode(['error' => 'El archivo no es una imagen.']);
        $uploadOk = 0;
    }

    // Verificar si el archivo ya existe
    if (file_exists($target_file)) {
        echo json_encode(['error' => 'Lo siento, el archivo ya existe.']);
        $uploadOk = 0;
    }

    // Limitar el tamaño del archivo
    if ($_FILES["image"]["size"] > 500000) {
        echo json_encode(['error' => 'Lo siento, tu archivo es demasiado grande.']);
        $uploadOk = 0;
    }

    // Permitir ciertos formatos de archivo
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo json_encode(['error' => 'Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.']);
        $uploadOk = 0;
    }

    // Verificar si $uploadOk está configurado en 0 por algún error
    if ($uploadOk == 0) {
        echo json_encode(['error' => 'Lo siento, tu archivo no fue subido.']);
    // Si todo está bien, intentar subir el archivo
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo json_encode(['imageUrl' => $target_file]);
        } else {
            echo json_encode(['error' => 'Lo siento, hubo un error al subir tu archivo.']);
        }
    }
} else {
    echo json_encode(['error' => 'No se recibió ninguna imagen.']);
}
?>
