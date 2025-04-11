<?php
require 'db.php';

class InventoryItem {
    // Propiedades de la clase
    public $id;
    public $nombre;
    public $marca;
    public $modelo;
    public $numeroSerie;
    public $estado;
    public $photo;

    // Constructor
    public function __construct($id, $nombre, $marca, $modelo, $numeroSerie, $estado, $photo) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->numeroSerie = $numeroSerie;
        $this->estado = $estado;
        $this->photo = $photo;
    }

    // Métodos para CRUD
    public static function getAll() {
        global $conn;
        $sql = "SELECT * FROM inventario";
        $result = $conn->query($sql);
        $inventario = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $equipos[] = new InventoryItem(
                    $row['id'],
                    $row['nombre'],
                    $row['marca'],
                    $row['modelo'],
                    $row['numero_serie'],
                    $row['estado'],
                    $row['photo']
                );
            }
        }
        return $inventario;
    }

    // Implementa otros métodos para insertar, actualizar y eliminar según sea necesario

}
?>
