<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_empresas');
require_once '../../inc/connection.php';

try {
    $stmt = $conn->prepare("SELECT id_empresa, nombre,sitio_web FROM empresa");
    $stmt->execute();
    $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($empresas);



} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
