<?php
/**
 * Quita el rol de moderador a un usuario
 * POST: id_usuario
 */

header('Content-Type: application/json');
require_once '../../inc/connection.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
    exit;
}

// Obtener datos
$data = json_decode(file_get_contents('php://input'), true);
$id_usuario = $data['id_usuario'] ?? null;

// Validar datos
if (!$id_usuario) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ID de usuario requerido'
    ]);
    exit;
}

try {
    // Verificar que el usuario existe
    $stmt = $conn->prepare("SELECT id_usuario, username FROM USUARIO WHERE id_usuario = :id_usuario");
    $stmt->execute([':id_usuario' => $id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ]);
        exit;
    }

    // Verificar si tiene el rol de moderador
    $stmt = $conn->prepare("
        SELECT COUNT(*) as tiene_rol 
        FROM USUARIO_ROL 
        WHERE id_usuario = :id_usuario AND id_rol = 2
    ");
    $stmt->execute([':id_usuario' => $id_usuario]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado['tiene_rol'] == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'El usuario no tiene el rol de moderador'
        ]);
        exit;
    }

    // Quitar rol de moderador (id_rol = 2)
    $stmt = $conn->prepare("
        DELETE FROM USUARIO_ROL 
        WHERE id_usuario = :id_usuario AND id_rol = 2
    ");
    $stmt->execute([':id_usuario' => $id_usuario]);

    echo json_encode([
        'success' => true,
        'message' => "Rol de moderador removido de {$usuario['username']} exitosamente"
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al quitar rol: ' . $e->getMessage()
    ]);
}
?>