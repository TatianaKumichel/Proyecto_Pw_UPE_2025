<?php
session_start();
include '../../inc/connection.php';
header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autorizado. Debes iniciar sesión.']);
    exit;
}


$idModerador = $_SESSION['id_usuario'];

if (!isset($_POST["id_comentario"]) || !isset($_POST["id_reporte"])) {
    echo json_encode(["error" => "Error, Faltan datos"]);
    exit;
}

$idComentario = $_POST["id_comentario"];
$idReporte = $_POST["id_reporte"];

try {

    $sql = "UPDATE comentario 
            SET estado = 'eliminado' 
            WHERE id_comentario = :id_comentario";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_comentario', $idComentario);
    $stmt->execute();


    $sql = "UPDATE reporte_comentario
            SET accion = 'eliminar',
                fecha_accion = NOW(),
                 id_moderador_accion = :id_moderador
            WHERE id_reporte = :id_reporte";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_moderador', $idModerador);
    $stmt->bindParam(':id_reporte', $idReporte);
    $stmt->execute();

    echo json_encode(["ok" => true]);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}