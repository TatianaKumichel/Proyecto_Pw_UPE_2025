<?php
include '../../inc/connection.php';
header('Content-Type: application/json');

$id = isset($_GET['id_empresa']) ? intval($_GET['id_empresa']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

try {
    $sql = "SELECT id_empresa, nombre, sitio_web FROM empresa WHERE id_empresa = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $empresa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$empresa) {
        echo json_encode(['success' => false, 'message' => 'empresano encontrada']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $empresa]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al obtener la empresa.']);
}
