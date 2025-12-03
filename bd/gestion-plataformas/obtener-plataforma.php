<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_plataformas');
require_once '../../inc/connection.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

try {
    $sql = " SELECT id_plataforma, nombre FROM plataforma WHERE id_plataforma  = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $plataforma = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$plataforma) {
        echo json_encode(['success' => false, 'message' => 'plataforma no encontrada']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $plataforma]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al obtener la plataforma.']);
}
