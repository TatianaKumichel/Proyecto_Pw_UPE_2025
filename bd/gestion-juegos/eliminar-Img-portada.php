<?php
include '../../inc/connection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

try {
    // obtener la portada actual
    $stmt = $conn->prepare("SELECT imagen_portada FROM JUEGO WHERE id_juego = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();

    if ($img) {
        $path = __DIR__ . "/../../" . $img;

        if (file_exists($path)) {
            unlink($path);
        }
    }

    // borrar en la base
    $stmt = $conn->prepare("UPDATE JUEGO SET imagen_portada = NULL WHERE id_juego = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
