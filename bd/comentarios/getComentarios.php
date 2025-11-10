<?php
/**
 * Obtener comentarios de un juego
 * Retorna los comentarios activos con información del usuario
 */

// Deshabilitar salida de errores de PHP
error_reporting(0);
ini_set('display_errors', 0);

session_start();

header('Content-Type: application/json');

try {
    require_once '../../inc/connection.php';
    $pdo = $conn;

    // Obtener id_juego
    $id_juego = isset($_GET['id_juego']) ? intval($_GET['id_juego']) : 0;

    if ($id_juego <= 0) {
        echo json_encode(['error' => 'ID de juego inválido']);
        exit;
    }

    // Obtener id del usuario logueado (si existe)
    $id_usuario_actual = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

    // Consulta para obtener comentarios activos
    $query = "SELECT
                c.id_comentario,
                c.id_usuario,
                c.contenido,
                c.fecha,
                c.estado,
                u.username,
                u.avatar
              FROM comentario c
              INNER JOIN usuario u ON c.id_usuario = u.id_usuario
              WHERE c.id_juego = :id_juego
                AND c.estado = 'activo'
              ORDER BY c.fecha DESC";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
    $stmt->execute();

    $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Agregar flag para identificar si el comentario es del usuario actual
    foreach ($comentarios as &$comentario) {
        $comentario['es_propio'] = ($id_usuario_actual && $comentario['id_usuario'] == $id_usuario_actual);
    }

    echo json_encode([
        'success' => true,
        'comentarios' => $comentarios,
        'total' => count($comentarios)
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => true,
        'comentarios' => [],
        'total' => 0
    ]);
}

