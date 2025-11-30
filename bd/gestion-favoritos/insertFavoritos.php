<?php
require_once '../../inc/auth.php';
requierePermisoAPI('marcar_favorito');
require_once '../../inc/connection.php';

// Obtener el ID del usuario logueado
$id_usuario = $_SESSION['id_usuario'];

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (strpos($contentType, 'application/json') !== false) {
    // json en el body
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);
} else {
    // formulario nativo multipart/form-data o application/x-www-form-urlencoded
    $data = $_POST;
}

// Verificar si se recibieron datos
if (empty($data) || !isset($data['id_juego'])) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'No se recibió el ID del juego',
    ]);
    exit;
}

$id_juego = $data['id_juego'];
header('Content-Type: application/json');

try {
    // Verificar que el juego existe y está publicado
    $stmtJuego = $conn->prepare("SELECT publicado FROM JUEGO WHERE id_juego = :id_juego");
    $stmtJuego->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
    $stmtJuego->execute();
    
    if ($stmtJuego->rowCount() === 0) {
        echo json_encode(['success' => false, 'error' => 'El juego no existe']);
        exit;
    }

    $publicado = $stmtJuego->fetchColumn();
    if ($publicado != 1) {
        echo json_encode(['success' => false, 'error' => 'El juego no está publicado']);
        exit;
    }

    // Verificar si ya existe como favorito
    $stmt = $conn->prepare("SELECT COUNT(*) FROM FAVORITO WHERE id_usuario = :id_usuario AND id_juego = :id_juego");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'error' => 'Este juego ya está como favorito']);
        exit;
    }

    // Insertar el favorito
    $query = 'INSERT INTO FAVORITO (id_juego, id_usuario, fecha_agregado) 
              VALUES (:id_juego, :id_usuario, NOW())';

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $ok = $stmt->execute();

    if ($ok) {
        $idResultante = $conn->lastInsertId();
        echo json_encode(['success' => true, 'message' => 'Favorito agregado correctamente', 'id' => $idResultante]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo agregar como favorito']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al agregar como favorito: ' . $e->getMessage()]);
}


