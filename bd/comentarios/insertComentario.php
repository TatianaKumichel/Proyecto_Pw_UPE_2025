<?php
/**
 * Insertar un nuevo comentario
 * Requiere usuario logueado
 */
require_once '../../inc/auth.php';
requierePermisoAPI('comentar');
require_once '../../inc/connection.php';

try {
    $pdo = $conn;

    // Obtener datos del POST
    $data = json_decode(file_get_contents('php://input'), true);

    $id_juego = isset($data['id_juego']) ? intval($data['id_juego']) : 0;
    $contenido = isset($data['contenido']) ? trim($data['contenido']) : '';
    $id_usuario = $_SESSION['id_usuario'];

    // Validaciones
    if ($id_juego <= 0) {
        echo json_encode(['error' => 'ID de juego inválido']);
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

    // Verificar que el juego existe y está publicado
    $queryJuego = "SELECT id_juego, publicado FROM juego WHERE id_juego = :id_juego";
    $stmtJuego = $pdo->prepare($queryJuego);
    $stmtJuego->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
    $stmtJuego->execute();

    if ($stmtJuego->rowCount() === 0) {
        echo json_encode(['error' => 'El juego no existe']);
        exit;
    }

    $juego = $stmtJuego->fetch(PDO::FETCH_ASSOC);
    if ($juego['publicado'] != 1) {
        echo json_encode(['error' => 'El juego no está publicado, no se pueden agregar comentarios']);
        exit;
    }

    // Verificar que el usuario no tenga ya un comentario activo en este juego
    $queryComentarioExistente = "SELECT id_comentario
                                  FROM comentario
                                  WHERE id_usuario = :id_usuario
                                    AND id_juego = :id_juego
                                    AND estado = 'activo'";
    $stmtComentarioExistente = $pdo->prepare($queryComentarioExistente);
    $stmtComentarioExistente->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmtComentarioExistente->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
    $stmtComentarioExistente->execute();

    if ($stmtComentarioExistente->rowCount() > 0) {
        echo json_encode([
            'error' => 'Ya tienes un comentario activo en este juego. Puedes editarlo o eliminarlo para publicar uno nuevo.'
        ]);
        exit;
    }

    // Insertar comentario
    $query = "INSERT INTO comentario (id_usuario, id_juego, contenido, fecha, estado) 
              VALUES (:id_usuario, :id_juego, :contenido, NOW(), 'activo')";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
    $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Comentario agregado exitosamente',
            'id_comentario' => $pdo->lastInsertId()
        ]);
    } else {
        echo json_encode(['error' => 'Error al agregar el comentario']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al agregar comentario: ' . $e->getMessage()]);
}

