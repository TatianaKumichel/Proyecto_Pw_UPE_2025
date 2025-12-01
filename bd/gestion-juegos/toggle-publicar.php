<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_juegos');

require_once '../../inc/connection.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$estado = $data['estado'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

if ($estado != 0 && $estado != 1) {
    echo json_encode(['success' => false, 'error' => 'Estado inválido']);
    exit;
}

try {
    // Verificar existencia
    $stmt = $conn->prepare("SELECT COUNT(*) FROM JUEGO WHERE id_juego = :id");
    $stmt->execute([':id' => $id]);
    if ($stmt->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'error' => 'El juego no existe']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE JUEGO SET publicado = :estado WHERE id_juego = :id");
    $stmt->execute([
        ':estado' => $estado,
        ':id' => $id
    ]);

    echo json_encode([
        'success' => true,
        'message' => ($estado == 1 ? "Juego publicado." : "Juego despublicado.")
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
