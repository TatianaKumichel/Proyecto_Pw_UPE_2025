<?php

include '../../inc/connection.php';
header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("SELECT id_empresa, nombre,sitio_web FROM empresa");
    $stmt->execute();
    $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($empresas);



} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
