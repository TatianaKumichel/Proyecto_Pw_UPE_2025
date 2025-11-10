<?php
include '../../inc/connection.php';
header('Content-Type: application/json');

try {
    // Obtener parámetros de filtro
    $nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
    $id_genero = isset($_GET['id_genero']) ? intval($_GET['id_genero']) : 0;
    $id_plataforma = isset($_GET['id_plataforma']) ? intval($_GET['id_plataforma']) : 0;

    // Construir la consulta base
    $query = "SELECT DISTINCT
                j.id_juego,
                j.titulo,
                j.descripcion,
                j.fecha_lanzamiento,
                j.imagen_portada,
                e.nombre AS empresa,
                GROUP_CONCAT(DISTINCT g.nombre ORDER BY g.nombre SEPARATOR ', ') AS generos,
                GROUP_CONCAT(DISTINCT p.nombre ORDER BY p.nombre SEPARATOR ', ') AS plataformas
              FROM juego j
              INNER JOIN empresa e ON j.id_empresa = e.id_empresa
              LEFT JOIN juego_genero jg ON j.id_juego = jg.id_juego
              LEFT JOIN genero g ON jg.id_genero = g.id_genero
              LEFT JOIN juego_plataforma jp ON j.id_juego = jp.id_juego
              LEFT JOIN plataforma p ON jp.id_plataforma = p.id_plataforma
              WHERE j.publicado = 1";

    $params = [];

    // Filtro por nombre
    if (!empty($nombre)) {
        $query .= " AND j.titulo LIKE :nombre";
        $params[':nombre'] = '%' . $nombre . '%';
    }

    // Filtro por género
    if ($id_genero > 0) {
        $query .= " AND j.id_juego IN (
                        SELECT id_juego 
                        FROM juego_genero 
                        WHERE id_genero = :id_genero
                    )";
        $params[':id_genero'] = $id_genero;
    }

    // Filtro por plataforma
    if ($id_plataforma > 0) {
        $query .= " AND j.id_juego IN (
                        SELECT id_juego 
                        FROM juego_plataforma 
                        WHERE id_plataforma = :id_plataforma
                    )";
        $params[':id_plataforma'] = $id_plataforma;
    }

    $query .= " GROUP BY j.id_juego, j.titulo, j.descripcion, j.fecha_lanzamiento, 
                         j.imagen_portada, e.nombre
                ORDER BY j.fecha_lanzamiento DESC, j.titulo ASC";

    $stmt = $conn->prepare($query);

    // Ejecutar con parámetros
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

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
        'error' => $e->getMessage()
    ]);
}