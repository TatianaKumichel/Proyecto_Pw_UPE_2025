<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_generos');

require_once __DIR__ . '/../../inc/connection.php';

// Obtener datos JSON
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id_genero']) ? intval($input['id_genero']) : 0;

if ($id <= 0) {
    echo json_encode(['ok' => false, 'error' => 'ID inválido']);
    exit;
}

// Verificar si el género existe
try {
    $stmt = $conn->prepare("SELECT nombre FROM genero WHERE id_genero = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $genero = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$genero) {
        echo json_encode(['ok' => false, 'error' => 'El género no existe']);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al verificar género: ' . $e->getMessage()]);
    exit;
}

// Verificar integridad referencial - si hay juegos asociados
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM juego_genero WHERE id_genero = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $juegosAsociados = $stmt->fetchColumn();

    if ($juegosAsociados > 0) {
        echo json_encode([
            'ok' => false,
            'error' => "No se puede eliminar el género '{$genero['nombre']}' porque tiene {$juegosAsociados} juego(s) asociado(s)."
        ]);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al verificar juegos asociados: ' . $e->getMessage()]);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM genero WHERE id_genero=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['ok' => true, 'message' => "Género '{$genero['nombre']}' eliminado correctamente"]);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al eliminar género: ' . $e->getMessage()]);
}
