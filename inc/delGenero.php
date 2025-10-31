<?php
require 'connection.php';

$id = isset($_POST['id_genero']) ? intval($_POST['id_genero']) : 0;

if ($id <= 0) {
    echo json_encode(['ok' => false, 'error' => 'ID inválido']);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM genero WHERE id_genero=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>