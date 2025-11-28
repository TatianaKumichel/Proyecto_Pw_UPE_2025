<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_faq');
require_once '../../inc/connection.php';

$id = isset($_GET['id_faq']) ? intval($_GET['id_faq']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

try {
    $sql = "SELECT id_faq, pregunta, respuesta FROM faq WHERE id_faq = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $faq = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$faq) {
        echo json_encode(['success' => false, 'message' => 'FAQ no encontrada']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $faq]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al obtener la FAQ.']);
}
