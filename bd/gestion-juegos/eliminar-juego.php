<?php
include '../../inc/connection.php';
header('Content-Type: application/json');
session_start();

// 🔒 Solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit;
}

// 📌 Datos enviados desde fetch
$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

try {
    // ============================
    // 1️⃣ Obtener la imagen para borrarla después
    // ============================
    $stmt = $conn->prepare("SELECT imagen_portada FROM JUEGO WHERE id_juego = :id");
    $stmt->execute([':id' => $id]);
    $imagen = $stmt->fetchColumn();

    // ============================
    // 2️⃣ Borrar relaciones en tablas puente
    // ============================
    $stmt = $conn->prepare("DELETE FROM JUEGO_GENERO WHERE id_juego = :id");
    $stmt->execute([':id' => $id]);

    $stmt = $conn->prepare("DELETE FROM JUEGO_PLATAFORMA WHERE id_juego = :id");
    $stmt->execute([':id' => $id]);

    // ============================
    // 3️⃣ Finalmente borrar el juego
    // ============================
    $stmt = $conn->prepare("DELETE FROM JUEGO WHERE id_juego = :id");
    $stmt->execute([':id' => $id]);

    // ============================
    // 4️⃣ Borrar imagen del servidor
    // ============================
    if ($imagen && file_exists(__DIR__ . '/../../' . $imagen)) {
        unlink(__DIR__ . '/../../' . $imagen);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Juego eliminado correctamente.'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
}
?>