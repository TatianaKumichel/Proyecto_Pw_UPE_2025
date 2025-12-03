<?php
/**
 * Obtiene todos los géneros de la base de datos
 * Uso publico
 */
require_once '../../inc/connection.php';
header('Content-Type: application/json');

try {
    $query = "SELECT id_empresa, nombre 
              FROM empresa 
              ORDER BY nombre ASC";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'empresas' => $empresas
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}