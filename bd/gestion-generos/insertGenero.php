<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../inc/connection.php';

// Obtener datos JSON
$input = json_decode(file_get_contents('php://input'), true);
$nombre = isset($input['nombre']) ? trim($input['nombre']) : '';

if (empty($nombre)) {
    echo json_encode(['ok' => false, 'error' => 'El nombre es obligatorio']);
    exit;
}

// Verificar si el género ya existe
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM genero WHERE nombre = :nombre");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['ok' => false, 'error' => 'Ya existe un género con ese nombre']);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al verificar duplicados: ' . $e->getMessage()]);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO genero(nombre) VALUES(:nombre)");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->execute();
    echo json_encode(['ok' => true, 'message' => 'Género creado correctamente']);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al crear género: ' . $e->getMessage()]);
}