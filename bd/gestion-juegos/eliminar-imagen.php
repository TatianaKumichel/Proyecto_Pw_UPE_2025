<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_juegos');

require_once '../../inc/connection.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'ID faltante']);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM JUEGO_IMAGEN WHERE id_imagen = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Imagen eliminada']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
