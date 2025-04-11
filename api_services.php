<?php
header('Content-Type: application/json');
require 'db.php';
require 'inventory_item.php';

function respondWithJson($data) {
    echo json_encode($data);
    exit;
}

// Ver Equipos
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] === 'get_all_equipos') {
        $equipos = array();

        $sql = "SELECT * FROM inventario";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $equipos[] = array(
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'marca' => $row['marca'],
                    'modelo' => $row['modelo'],
                    'numeroSerie' => $row['numeroSerie'],
                    'estado' => $row['estado'],
                    'photo' => $row['photo']
                );
            }
            respondWithJson($equipos);
        } else {
            respondWithJson(array('message' => 'No se encontraron equipos'));
        }
    }

    // Buscar equipos
    if ($_GET['action'] === 'search' && isset($_GET['query'])) {
        $query = $_GET['query'];
        $equipos = array();

        $sql = "SELECT * FROM inventario WHERE nombre LIKE '%$query%' OR modelo LIKE '%$query%' OR numeroSerie LIKE '%$query%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $equipos[] = array(
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'marca' => $row['marca'],
                    'modelo' => $row['modelo'],
                    'numeroSerie' => $row['numeroSerie'],
                    'estado' => $row['estado'],
                    'photo' => $row['photo']
                );
            }
            respondWithJson($equipos);
        } else {
            respondWithJson(array('message' => 'No se encontraron equipos'));
        }
    }
}

// Buscar equipo por número de serie
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'search_by_serial' && isset($_GET['serialNumber'])) {
    $serialNumber = $_GET['serialNumber'];
    $equipos = array();

    $sql = "SELECT * FROM inventario WHERE numeroSerie = '$serialNumber'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $equipos[] = array(
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'marca' => $row['marca'],
                'modelo' => $row['modelo'],
                'numeroSerie' => $row['numeroSerie'],
                'estado' => $row['estado'],
                'photo' => $row['photo']
            );
        }
        respondWithJson($equipos);
    } else {
        respondWithJson(array('message' => 'No se encontró el equipo con el número de serie proporcionado'));
    }
}

// Baja de un equipo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'baja_equipo') {
    $id = $_POST['id'];
    $sql = "UPDATE inventario SET estado = 'BAJA' WHERE id = '$id' AND estado != 'BAJA'";

    if ($conn->query($sql) === TRUE) {
        respondWithJson(array('message' => 'Equipo dado de baja correctamente'));
    } else {
        respondWithJson(array('error' => 'Error al dar de baja el equipo: ' . $conn->error));
    }
}

// Eliminar un equipo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'delete_equipo') {
    $id = $_POST['id'];
    $sql = "DELETE FROM inventario WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        respondWithJson(array('message' => 'Equipo eliminado correctamente'));
    } else {
        respondWithJson(array('error' => 'Error al eliminar el equipo: ' . $conn->error));
    }
}

// Endpoint para imprimir equipo
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'print_equipo') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "SELECT * FROM inventario WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $equipo = $result->fetch_assoc();
            respondWithJson($equipo);
        } else {
            respondWithJson(array('message' => 'No se encontró el equipo'));
        }
    } else {
        respondWithJson(array('message' => 'ID no proporcionado'));
    }
}
?>
