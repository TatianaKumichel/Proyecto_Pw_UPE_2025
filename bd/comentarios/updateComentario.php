<?php
/**
 * Actualizar un comentario propio
 * Solo el autor puede editar su comentario
 */

session_start();

header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'Debes iniciar sesión para editar comentarios']);
    exit;
}

try {
    require_once '../../inc/connection.php';
    $pdo = $conn;

    // Obtener datos del POST
    $data = json_decode(file_get_contents('php://input'), true);

    $id_comentario = isset($data['id_comentario']) ? intval($data['id_comentario']) : 0;
    $contenido = isset($data['contenido']) ? trim($data['contenido']) : '';
    $id_usuario = $_SESSION['id_usuario'];

    // Validaciones
    if ($id_comentario <= 0) {
        echo json_encode(['error' => 'ID de comentario inválido']);
        exit;
    }

    if (empty($contenido)) {
        echo json_encode(['error' => 'El comentario no puede estar vacío']);
        exit;
    }

    if (strlen($contenido) > 500) {
        echo json_encode(['error' => 'El comentario no puede exceder 500 caracteres']);
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
        echo json_encode(['error' => 'No tienes permiso para editar este comentario']);
        exit;
    }

    // Actualizar comentario
    $query = "UPDATE comentario 
              SET contenido = :contenido 
              WHERE id_comentario = :id_comentario";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
    $stmt->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Comentario actualizado exitosamente'
        ]);
    } else {
        echo json_encode(['error' => 'Error al actualizar el comentario']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al actualizar comentario: ' . $e->getMessage()]);
}

