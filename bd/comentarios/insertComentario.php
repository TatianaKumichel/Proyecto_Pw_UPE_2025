<?php
/**
 * Insertar un nuevo comentario
 * Requiere usuario logueado
 */

session_start();

header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'Debes iniciar sesión para comentar']);
    exit;
}

try {
    require_once '../../inc/connection.php';
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

    // Verificar que el juego existe
    $queryJuego = "SELECT id_juego FROM juego WHERE id_juego = :id_juego";
    $stmtJuego = $pdo->prepare($queryJuego);
    $stmtJuego->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
    $stmtJuego->execute();

    if ($stmtJuego->rowCount() === 0) {
        echo json_encode(['error' => 'El juego no existe']);
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

