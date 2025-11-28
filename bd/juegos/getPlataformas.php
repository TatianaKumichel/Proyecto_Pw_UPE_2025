<?php
/**
 * Obtiene todos las plataformas de la base de datos
 * Uso publico
 */
require_once '../../inc/connection.php';
header('Content-Type: application/json');

try {
    $query = "SELECT id_plataforma, nombre 
              FROM plataforma 
              ORDER BY nombre ASC";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $plataformas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'plataformas' => $plataformas
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}