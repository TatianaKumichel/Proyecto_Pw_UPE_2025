<?php
require_once '../../inc/auth.php';
//requierePermisoAPI('calificar_juego');
require_once '../../inc/connection.php';
header('Content-Type: application/json');

// Verificar que el usuario esté logueado

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['calificacion' => null]); 
    exit;
}


$id_usuario = $_SESSION['id_usuario'];
$id_juego = isset($_GET['id_juego']) ? intval($_GET['id_juego']) : 0;

if ($id_juego <= 0) {
    echo json_encode(['calificacion' => null]);
    exit;
}

try {
    $query = "SELECT puntuacion 
              FROM calificacion 
              WHERE id_usuario = :id_usuario AND id_juego = :id_juego";

    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':id_juego' => $id_juego
    ]);

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'calificacion' => $resultado ? intval($resultado['puntuacion']) : null
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'calificacion' => null,
        'error' => $e->getMessage()
    ]);
}
