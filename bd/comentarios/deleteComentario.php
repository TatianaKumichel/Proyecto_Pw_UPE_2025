<?php
/**
 * Eliminar un comentario propio
 * Solo el autor puede eliminar su comentario
 * Cambia el estado a 'eliminado' en lugar de borrar físicamente
 */

require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_comentarios_propios');
require_once '../../inc/connection.php';

try {
    $pdo = $conn;

    // Obtener datos del POST
    $data = json_decode(file_get_contents('php://input'), true);

    $id_comentario = isset($data['id_comentario']) ? intval($data['id_comentario']) : 0;
    $id_usuario = $_SESSION['id_usuario'];

    // Validaciones
    if ($id_comentario <= 0) {
        echo json_encode(['error' => 'ID de comentario inválido']);
        exit;
    }

    // Verificar que el comentario existe y pertenece al usuario
    $queryVerificar = "SELECT id_usuario FROM comentario WHERE id_comentario = :id_comentario";
    $stmtVerificar = $pdo->prepare($queryVerificar);
    $stmtVerificar->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);
    $stmtVerificar->execute();

    $comentario = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

    if (!$comentario) {
        echo json_encode(['error' => 'El comentario no existe']);
        exit;
    }

    if ($comentario['id_usuario'] != $id_usuario) {
        echo json_encode(['error' => 'No tienes permiso para eliminar este comentario']);
        exit;
    }

    // Cambiar estado a 'eliminado' (soft delete)
    $query = "UPDATE comentario 
              SET estado = 'eliminado' 
              WHERE id_comentario = :id_comentario";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Comentario eliminado exitosamente'
        ]);
    } else {
        echo json_encode(['error' => 'Error al eliminar el comentario']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al eliminar comentario: ' . $e->getMessage()]);
}

