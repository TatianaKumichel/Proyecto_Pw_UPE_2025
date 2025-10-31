<?php
require 'connection.php';

$id = isset($_POST['id_genero']) ? intval($_POST['id_genero']) : 0;
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

if ($id <= 0 || empty($nombre)) {
    echo json_encode(['ok' => false, 'error' => 'Datos inválidos']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE genero SET nombre=:nombre WHERE id_genero=:id");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>