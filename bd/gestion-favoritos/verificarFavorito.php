<?php
session_start();
include '../../inc/connection.php';
header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['esFavorito' => false, 'error' => 'Usuario no logueado']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_juego = isset($_GET['id_juego']) ? intval($_GET['id_juego']) : 0;

if ($id_juego <= 0) {
    echo json_encode(['esFavorito' => false, 'error' => 'ID de juego inválido']);
    exit;
}

try {
    $query = "SELECT COUNT(*) as existe 
              FROM favorito 
              WHERE id_usuario = :id_usuario AND id_juego = :id_juego";

    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':id_juego' => $id_juego
    ]);

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'esFavorito' => $resultado['existe'] > 0
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'esFavorito' => false,
        'error' => $e->getMessage()
    ]);
}