<?php
include '../../inc/connection.php';
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Ocurrio un error']);
    exit;
}

try {
    $sql = "DELETE FROM faq WHERE id_faq = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $ok = $stmt->execute();

    echo json_encode(['success' => $ok, 'message' => "Pregunta frecuente eliminada correctamente"]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => "No se pudo eliminar, intente mas tarde"]);
}
?>