<?php
/**
 * Reportar un comentario de otro usuario
 * Crea un registro en la tabla REPORTE_COMENTARIO
 */

session_start();

header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'Debes iniciar sesión para reportar comentarios']);
    exit;
}

try {
    require_once '../../inc/connection.php';
    $pdo = $conn;

    // Obtener datos del POST
    $data = json_decode(file_get_contents('php://input'), true);

    $id_comentario = isset($data['id_comentario']) ? intval($data['id_comentario']) : 0;
    $motivo = isset($data['motivo']) ? trim($data['motivo']) : '';
    $id_usuario_reporta = $_SESSION['id_usuario'];

    // Validaciones
    if ($id_comentario <= 0) {
        echo json_encode(['error' => 'ID de comentario inválido']);
        exit;
    }

    if (empty($motivo)) {
        echo json_encode(['error' => 'Debes especificar un motivo para el reporte']);
        exit;
    }

    if (strlen($motivo) > 255) {
        echo json_encode(['error' => 'El motivo no puede exceder 255 caracteres']);
        exit;
    }

    // Verificar que el comentario existe
    $queryVerificar = "SELECT id_usuario, estado FROM comentario WHERE id_comentario = :id_comentario";
    $stmtVerificar = $pdo->prepare($queryVerificar);
    $stmtVerificar->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);
    $stmtVerificar->execute();

    $comentario = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

    if (!$comentario) {
        echo json_encode(['error' => 'El comentario no existe']);
        exit;
    }

    // No se puede reportar el propio comentario
    if ($comentario['id_usuario'] == $id_usuario_reporta) {
        echo json_encode(['error' => 'No puedes reportar tu propio comentario']);
        exit;
    }

    // Verificar si ya reportó este comentario
    $queryYaReportado = "SELECT id_reporte FROM reporte_comentario 
                         WHERE id_comentario = :id_comentario 
                         AND id_usuario_reporta = :id_usuario_reporta";
    $stmtYaReportado = $pdo->prepare($queryYaReportado);
    $stmtYaReportado->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);
    $stmtYaReportado->bindParam(':id_usuario_reporta', $id_usuario_reporta, PDO::PARAM_INT);
    $stmtYaReportado->execute();

    if ($stmtYaReportado->rowCount() > 0) {
        echo json_encode(['error' => 'Ya has reportado este comentario anteriormente']);
        exit;
    }

    // Insertar reporte
    $query = "INSERT INTO reporte_comentario (id_comentario, id_usuario_reporta, motivo, fecha_reporte) 
              VALUES (:id_comentario, :id_usuario_reporta, :motivo, NOW())";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario_reporta', $id_usuario_reporta, PDO::PARAM_INT);
    $stmt->bindParam(':motivo', $motivo, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Actualizar estado del comentario a 'reportado'
        $queryActualizar = "UPDATE comentario SET estado = 'reportado' WHERE id_comentario = :id_comentario";
        $stmtActualizar = $pdo->prepare($queryActualizar);
        $stmtActualizar->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);
        $stmtActualizar->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Comentario reportado exitosamente. Será revisado por un moderador.'
        ]);
    } else {
        echo json_encode(['error' => 'Error al reportar el comentario']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al reportar comentario: ' . $e->getMessage()]);
}

