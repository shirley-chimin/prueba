<?php
class Medicamento {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        return $this->conn->query("SELECT * FROM medicamentos ORDER BY id DESC");
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM medicamentos WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function crear($data) {
    $precio = floatval($data['precio']); // convertir a número decimal
    $stock = intval($data['stock']);     // convertir a entero

    $stmt = $this->conn->prepare(
        "INSERT INTO medicamentos (nombre, descripcion, precio, stock, fecha_vencimiento) VALUES (?,?,?,?,?)"
    );
    $stmt->bind_param(
        "ssdsi",
        $data['nombre'],
        $data['descripcion'],
        $precio,
        $stock,
        $data['fecha_vencimiento']
    );

    if (!$stmt->execute()) {
        die("Error al ejecutar INSERT: " . $stmt->error);
    }
    return true;
}

public function actualizar($data) {
    $precio = floatval($data['precio']);
    $stock = intval($data['stock']);
    $fecha_vencimiento = intval($data['fecha_vencimiento']); // convertir a entero
    $id = intval($data['id']);

    $stmt = $this->conn->prepare(
        "UPDATE medicamentos 
         SET nombre=?, descripcion=?, precio=?, stock=?, fecha_vencimiento=? 
         WHERE id=?"
    );

    if (!$stmt) {
        die("Error en prepare: " . $this->conn->error);
    }

    $stmt->bind_param(
        "ssdi i i",  // s=string, d=decimal, i=integer final
        $data['nombre'],
        $data['descripcion'],
        $precio,
        $stock,
        $fecha_vencimiento,
        $id
    );

    if (!$stmt->execute()) {
        die("Error al ejecutar UPDATE: " . $stmt->error);
    }

    return true;
}

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM medicamentos WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
