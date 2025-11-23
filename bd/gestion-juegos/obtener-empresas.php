<?php
include '../../inc/connection.php';
header('Content-Type: application/json');

try {
    $stmt = $conn->query("SELECT id_empresa, nombre FROM EMPRESA ORDER BY nombre");

    echo json_encode([
        'success' => true,
        'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
