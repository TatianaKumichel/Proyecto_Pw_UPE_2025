<?php
require_once '../../inc/auth.php';
requierePermisoAPI('moderar_comentarios');
require_once '../../inc/connection.php';

$idModerador = $_SESSION['id_usuario'];


if (!isset($_POST["id_usuario"]) || !isset($_POST["id_reporte"])) {
    echo json_encode(["error" => "Faltan datos"]);
    exit;
}

$idUsuario = $_POST["id_usuario"];
$idReporte = $_POST["id_reporte"];

try {


    $sql = "SELECT id_comentario FROM reporte_comentario WHERE id_reporte = :id_reporte";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_reporte', $idReporte);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(["error" => "Reporte no encontrado"]);
        exit;
    }

    $idComentario = $row["id_comentario"];



    $sql = "UPDATE usuario 
            SET estado = 'restringido'
            WHERE id_usuario = :id_usuario";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_usuario', $idUsuario);
    $stmt->execute();

    $sql = "INSERT INTO restriccion_usuario (id_usuario, fecha_inicio, activo)
            VALUES (:id_usuario, NOW(), 1)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_usuario', $idUsuario);
    $stmt->execute();


    $sql = "UPDATE comentario 
            SET estado = 'eliminado'
            WHERE id_comentario = :id_comentario";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_comentario', $idComentario);
    $stmt->execute();


    $sql = "UPDATE reporte_comentario
            SET accion = 'restringir',
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