<?php
require_once __DIR__ . '/../../inc/connection.php';

try {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;

    $sql = "SELECT
                j.id_juego,
                j.titulo,
                j.descripcion,
                j.imagen_portada,
                j.fecha_lanzamiento,
                e.nombre AS empresa,
                GROUP_CONCAT(DISTINCT g.nombre ORDER BY g.nombre SEPARATOR ', ') AS generos,
                GROUP_CONCAT(DISTINCT p.nombre ORDER BY p.nombre SEPARATOR ', ') AS plataformas
            FROM JUEGO j
            INNER JOIN EMPRESA e ON j.id_empresa = e.id_empresa
            LEFT JOIN JUEGO_GENERO jg ON j.id_juego = jg.id_juego
            LEFT JOIN GENERO g ON jg.id_genero = g.id_genero
            LEFT JOIN JUEGO_PLATAFORMA jp ON j.id_juego = jp.id_juego
            LEFT JOIN PLATAFORMA p ON jp.id_plataforma = p.id_plataforma
            WHERE j.publicado = TRUE
              AND (j.fecha_lanzamiento IS NULL OR j.fecha_lanzamiento > CURDATE())
            GROUP BY j.id_juego
            ORDER BY j.fecha_lanzamiento ASC
            LIMIT :limit";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
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
        'error' => 'Error al obtener próximos lanzamientos'
    ]);
}