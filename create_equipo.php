<?php

// Configura los detalles de conexión a la base de datos
$host = 'localhost'; // Cambia esto si tu base de datos está en otro host
$dbname = 'inventariolbc'; // Reemplaza con el nombre de tu base de datos
$username = 'root'; // Reemplaza con tu usuario de base de datos
$password = ''; // Reemplaza con tu contraseña de base de datos

// Crea una conexión a la base de datos
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si se recibieron los campos requeridos
    if (isset($_POST['nombre'], $_POST['marca'], $_POST['modelo'], $_POST['numeroSerie'], $_POST['estado'])) {
        // Obtiene los datos del formulario
        $nombre = $_POST['nombre'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $numeroSerie = $_POST['numeroSerie'];
        $estado = $_POST['estado'];

        // Verifica si se ha subido un archivo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $fileSize = $_FILES['photo']['size'];
            $fileType = $_FILES['photo']['type'];

            // Define el directorio de destino
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $fileName;

            // Mueve el archivo subido al directorio de destino
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Inserta los datos en la base de datos
                $sql = "INSERT INTO inventario (nombre, marca, modelo, numeroSerie, estado, photo) VALUES (:nombre, :marca, :modelo, :numeroSerie, :estado, :photo)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':marca' => $marca,
                    ':modelo' => $modelo,
                    ':numeroSerie' => $numeroSerie,
                    ':estado' => $estado,
                    ':photo' => $fileName,
                ]);
                echo json_encode(['status' => 'success', 'message' => 'Equipo registrado correctamente']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al mover el archivo al directorio de destino']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se recibió el archivo o hubo un error al subirlo']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Faltan campos en la solicitud']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}

?>
