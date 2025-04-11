<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventariolbc";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener los datos enviados desde Flutter
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$numeroSerie = $_POST['numeroSerie'];
$estado = $_POST['estado'];

$target_dir = "uploads/";
$photo = '';

if(isset($_FILES["photo"])) {
    $photo = basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . $photo;
    if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen.']);
        exit();
    }
} else {
    $query = "SELECT photo FROM inventario WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $photo = $row['photo'];
    }
}

$sql = "UPDATE inventario SET nombre='$nombre', marca='$marca', modelo='$modelo', numeroSerie='$numeroSerie', estado='$estado', photo='$photo' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success', 'message' => 'Equipo actualizado correctamente.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el equipo: ' . $conn->error]);
}

$conn->close();
?>
