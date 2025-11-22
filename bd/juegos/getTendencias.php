<?php
require_once __DIR__ . '/../../inc/connection.php';
header('Content-Type: application/json');

try {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2;
    $min_calificaciones = isset($_GET['min_cal']) ? (int)$_GET['min_cal'] : 2;

    // FORZAR VALORES SEGUROS
    $limit = max(1, $limit);
    $min_calificaciones = max(1, $min_calificaciones);

    $sql = "SELECT
                j.id_juego,
                j.titulo,
                j.imagen_portada,
                e.nombre AS empresa,
                ROUND(AVG(c.puntuacion), 1) AS calificacion_promedio,
                COUNT(c.id_usuario) AS total_calificaciones
            FROM JUEGO j
            INNER JOIN EMPRESA e ON j.id_empresa = e.id_empresa
            INNER JOIN CALIFICACION c ON j.id_juego = c.id_juego
            WHERE j.publicado = TRUE
            GROUP BY j.id_juego
            HAVING total_calificaciones >= :min_cal
            ORDER BY calificacion_promedio DESC, total_calificaciones DESC
            LIMIT $limit";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':min_cal', $min_calificaciones, PDO::PARAM_INT);
    $stmt->execute();

    $juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'juegos' => $juegos,
        'total' => count($juegos)
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage() // para debugging
    ]);
}
