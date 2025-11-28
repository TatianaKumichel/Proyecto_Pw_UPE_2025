<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_generos');

require_once __DIR__ . '/../../inc/connection.php';

// Obtener datos JSON
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id_genero']) ? intval($input['id_genero']) : 0;
$nombre = isset($input['nombre']) ? trim($input['nombre']) : '';

if ($id <= 0 || empty($nombre)) {
    echo json_encode(['ok' => false, 'error' => 'Datos inválidos']);
    exit;
}

// Verificar si el género existe
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM genero WHERE id_genero = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->fetchColumn() == 0) {
        echo json_encode(['ok' => false, 'error' => 'El género no existe']);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al verificar género: ' . $e->getMessage()]);
    exit;
}

// Verificar si ya existe otro género con el mismo nombre
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM genero WHERE nombre = :nombre AND id_genero != :id");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['ok' => false, 'error' => 'Ya existe otro género con ese nombre']);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al verificar duplicados: ' . $e->getMessage()]);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE genero SET nombre=:nombre WHERE id_genero=:id");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['ok' => true, 'message' => 'Género actualizado correctamente']);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al actualizar género: ' . $e->getMessage()]);
}
