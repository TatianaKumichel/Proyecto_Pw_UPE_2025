<?php
include '../../inc/connection.php';
header('Content-Type: application/json');

try {
    $stmt = $conn->query("SELECT id_plataforma, nombre FROM PLATAFORMA ORDER BY id_plataforma ASC;");
    echo json_encode([
        'success' => true,
        'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>