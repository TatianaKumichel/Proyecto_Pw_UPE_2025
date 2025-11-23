<?php
include '../../inc/connection.php';
header('Content-Type: application/json');
session_start();

// Solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit;
}

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
