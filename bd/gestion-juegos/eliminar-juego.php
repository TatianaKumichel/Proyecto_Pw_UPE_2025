<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_juegos');

require_once '../../inc/connection.php';

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

try {
    // ============================
    // Verificar existencia del juego
    // ============================
    $stmt = $conn->prepare("SELECT COUNT(*) FROM JUEGO WHERE id_juego = :id");
    $stmt->execute([':id' => $id]);
    if ($stmt->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'error' => 'El juego no existe']);
        exit;
    }

    // ============================
    // Borrar el juego
    // ============================
    // Las relaciones en tablas (juego_genero, juego_plataforma, juego_imagen,
    // comentarios, calificaciones, favoritos, notificaciones) se eliminan automáticamente
    // gracias a la restricción ON DELETE CASCADE definida en la base de datos.
    // Sin esa restricción, simplemente se tendría que eliminar manualmente cada una de las relaciones.
    $stmt = $conn->prepare("DELETE FROM JUEGO WHERE id_juego = :id");
    $stmt->execute([':id' => $id]);

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
