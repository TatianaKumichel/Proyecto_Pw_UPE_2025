<?php
require 'connection.php';

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

if (empty($nombre)) {
    echo json_encode(['ok' => false, 'error' => 'El nombre es obligatorio']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO genero(nombre) VALUES(:nombre)");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->execute();
    echo json_encode(['ok' => true]);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>