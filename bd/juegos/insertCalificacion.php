<?php
require_once '../../inc/auth.php';
requierePermisoAPI('calificar_juego');
require_once '../../inc/connection.php';
header('Content-Type: application/json');

$id_usuario = $_SESSION['id_usuario'];
$input = json_decode(file_get_contents("php://input"), true);
$id_juego = $input['id_juego'] ?? 0;
$puntuacion = $input['puntuacion'] ?? 0;

// Validar id_juego y rango de puntuación (0 para eliminar, 1-5 para calificar)
if (!$id_juego || $puntuacion < 0 || $puntuacion > 5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos inválidos. La puntuación debe estar entre 0 y 5.']);
    exit;
}

try {
    // Verificar si ya existe una calificación
    $checkQuery = "SELECT COUNT(*) FROM calificacion WHERE id_usuario = :u AND id_juego = :j";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':u', $id_usuario, PDO::PARAM_INT);
    $checkStmt->bindParam(':j', $id_juego, PDO::PARAM_INT);
    $checkStmt->execute();
    $existe = $checkStmt->fetchColumn() > 0;

    // Si puntuación es 0, eliminar la calificación
    if ($puntuacion == 0) {
        if ($existe) {
            $query = "DELETE FROM calificacion WHERE id_usuario = :u AND id_juego = :j";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':u', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':j', $id_juego, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'message' => 'Calificación eliminada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'No había calificación para eliminar'
            ]);
        }
    } else {
        // Puntuación entre 1-5: insertar o actualizar
        if ($existe) {
            // Actualizar calificación existente
            $query = "UPDATE calificacion
                      SET puntuacion = :p, fecha = NOW()
                      WHERE id_usuario = :u AND id_juego = :j";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':p', $puntuacion, PDO::PARAM_INT);
            $stmt->bindParam(':u', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':j', $id_juego, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'message' => 'Calificación actualizada correctamente'
            ]);
        } else {
            // Insertar nueva calificación
            $query = "INSERT INTO calificacion (id_usuario, id_juego, puntuacion, fecha)
                      VALUES (:u, :j, :p, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':u', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':j', $id_juego, PDO::PARAM_INT);
            $stmt->bindParam(':p', $puntuacion, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'message' => 'Calificación registrada correctamente'
            ]);
        }
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al guardar calificación: ' . $e->getMessage()
    ]);
}
