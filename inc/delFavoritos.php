<?php
require 'connection.php';
// ejemplo: id del usuario logueado
$id_usuario = 1;

// respuesta en formato JSON
header('Content-Type: application/json; charset=utf-8');

// JSON enviado desde fetch
$input = json_decode(file_get_contents('php://input'), true);

$errores = [];

if (!isset($input['id'])) {
    $errores['validacion'] = 'Error en id del juego.';
    echo json_encode(['ok' => false, 'error' => $errores['validacion']]);
    exit;
}

$id_juego = $input['id'];

try {
    $stmt = $conn->prepare("DELETE FROM favorito WHERE id_usuario = :id_usuario AND id_juego = :id_juego");
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':id_juego', $id_juego);
    $stmt->execute();

    echo json_encode(['ok' => true]);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>