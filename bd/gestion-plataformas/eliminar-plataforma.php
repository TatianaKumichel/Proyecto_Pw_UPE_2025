<?php
include '../../inc/connection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'ID faltante']);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM PLATAFORMA WHERE id_plataforma = :id");
    $stmt->execute(['id' => $id]);
    echo json_encode(['success' => true, 'message' => 'Plataforma eliminada']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
