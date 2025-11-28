<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_plataformas');

require_once '../../inc/connection.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

try {
    $sql = "DELETE FROM plataforma WHERE id_plataforma = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $ok = $stmt->execute();

    echo json_encode(['success' => $ok, 'message' => "Plataforma eliminada correctamente"]);
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    
    if (strpos($errorMsg, 'a foreign key constraint fails') !== false) {
        echo json_encode([
            'success' => false,
            'message' => 'No se puede eliminar la plataforma porque está asociada a uno o más juegos.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => "No se pudo eliminar, intente más tarde"]);
    }
}
?>
