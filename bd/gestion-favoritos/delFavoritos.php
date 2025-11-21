<?php
<<<<<<< HEAD
session_start();
require_once '../../inc/connection.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'No autorizado. Debes iniciar sesión.']);
    exit;
}

// Obtener el ID del usuario logueado
$id_usuario = $_SESSION['id_usuario'];
=======
include '../../inc/connection.php';
// ejemplo: id del usuario logueado
$id_usuario = 1;
>>>>>>> 165ef6c (pasaron cosas con git)

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
<<<<<<< HEAD
$id_juego = $data['id_juego'] ?? $data['id'] ?? null;

if (empty($id_juego)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'No se recibió el ID del juego',
    ]);
    exit;
}
header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("DELETE FROM FAVORITO WHERE id_usuario = :id_usuario AND id_juego = :id_juego");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
    $ok = $stmt->execute();

    if ($ok && $stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Favorito eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se encontró el favorito o ya fue eliminado']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al eliminar favorito: ' . $e->getMessage()]);
}


=======
if (empty($data)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
        'error' => 'No se recibieron datos',
    ]);
    exit;
}

$id_juego = $data['id'];
header('Content-Type: application/json');
try {
    $stmt = $conn->prepare("DELETE FROM favorito WHERE id_usuario = :id_usuario AND id_juego = :id_juego");
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':id_juego', $id_juego);
    $ok = $stmt->execute();
    echo json_encode(['success' => $ok, 'message' => "Favorito eliminado correctamente"]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => "No se pudo eliminar, intente mas tarde"]);
}
>>>>>>> 165ef6c (pasaron cosas con git)
