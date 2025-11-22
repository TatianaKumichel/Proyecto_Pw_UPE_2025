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
if (!isset($_POST["id_reporte"])) {
    echo json_encode(["error" => "Ocurrio un error"]);
    exit;
}

$id_reporte = $_POST["id_reporte"];

try {


    $sql = "SELECT id_comentario FROM reporte_comentario WHERE id_reporte = :id_reporte";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_reporte', $id_reporte);
    $stmt->execute();
    $reporte = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reporte) {
        echo json_encode(["error" => "Reporte no encontrado"]);
        exit;
    }

    $id_comentario = $reporte["id_comentario"];


    $sql = "UPDATE reporte_comentario 
            SET accion = 'ignorar',
                fecha_accion = NOW(),
                  id_moderador_accion = :id_moderador
            WHERE id_reporte = :id_reporte";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_moderador', $idModerador);
    $stmt->bindParam(':id_reporte', $id_reporte);
    $stmt->execute();

    $sql = "UPDATE comentario 
            SET estado = 'activo' 
            WHERE id_comentario = :id_comentario";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_comentario', $id_comentario);
    $stmt->execute();

    echo json_encode(["ok" => true]);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}