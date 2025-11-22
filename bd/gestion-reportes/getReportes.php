<?php

include '../../inc/connection.php';
header('Content-Type: application/json');


$sql = "
    SELECT 
        r.id_reporte,
        r.motivo,
        r.fecha_reporte,
        r.accion,

        ur.username AS usuario_reporta,

        c.id_comentario,
        c.contenido AS comentario_texto,
        c.id_usuario AS id_usuario_comentario,
        c.id_juego,

        u.username AS usuario_comentario,
        j.titulo AS juego_nombre

    FROM reporte_comentario r
    INNER JOIN comentario c ON r.id_comentario = c.id_comentario
    INNER JOIN usuario u ON c.id_usuario = u.id_usuario
    INNER JOIN usuario ur ON r.id_usuario_reporta = ur.id_usuario
    LEFT JOIN juego j ON c.id_juego = j.id_juego
    ORDER BY r.id_reporte DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute();

$lista = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($lista as &$row) {
    $row["estado"] = $row["accion"] === null ? "pendiente" : "resuelto";
}

echo json_encode($lista, JSON_UNESCAPED_UNICODE);