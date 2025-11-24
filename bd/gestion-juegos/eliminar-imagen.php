<?php
include '../../inc/connection.php';
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit;
}

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
