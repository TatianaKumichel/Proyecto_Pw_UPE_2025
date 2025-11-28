<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_juegos');

require_once '../../inc/connection.php';

try {
    // =====================================
    // 1) Obtener juegos principales
    // =====================================
    $stmt = $conn->prepare("
        SELECT 
            j.id_juego,
            j.titulo,
            j.descripcion,
            j.fecha_lanzamiento,
            j.imagen_portada,
            j.publicado,
            j.id_empresa,
            e.nombre AS empresa
        FROM JUEGO j
        LEFT JOIN EMPRESA e ON j.id_empresa = e.id_empresa
        ORDER BY j.id_juego DESC
    ");
    $stmt->execute();
    $juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$juegos) {
        echo json_encode(['success' => true, 'data' => []]);
        exit;
    }

    // Lista de IDs
    $ids = array_column($juegos, 'id_juego');
    $idsList = implode(",", array_map('intval', $ids));

    // =====================================
    // 2) Géneros por juego
    // =====================================
    $generosStmt = $conn->prepare("
        SELECT 
            jg.id_juego,
            g.id_genero,
            g.nombre AS genero
        FROM JUEGO_GENERO jg
        INNER JOIN GENERO g ON jg.id_genero = g.id_genero
        WHERE jg.id_juego IN ($idsList)
    ");
    $generosStmt->execute();
    $generosRaw = $generosStmt->fetchAll(PDO::FETCH_ASSOC);

    $generos = [];
    foreach ($generosRaw as $g) {
        $generos[$g['id_juego']][] = [
            'id_genero' => $g['id_genero'],
            'nombre' => $g['genero']
        ];
    }

    // =====================================
    // 3) Plataformas por juego
    // =====================================
    $platStmt = $conn->prepare("
        SELECT 
            jp.id_juego,
            p.id_plataforma,
            p.nombre AS plataforma
        FROM JUEGO_PLATAFORMA jp
        INNER JOIN PLATAFORMA p ON jp.id_plataforma = p.id_plataforma
        WHERE jp.id_juego IN ($idsList)
    ");
    $platStmt->execute();
    $plataformasRaw = $platStmt->fetchAll(PDO::FETCH_ASSOC);

    $plataformas = [];
    foreach ($plataformasRaw as $p) {
        $plataformas[$p['id_juego']][] = [
            'id_plataforma' => $p['id_plataforma'],
            'nombre' => $p['plataforma']
        ];
    }

    // =====================================
    // 4) IMÁGENES EXTRA por juego
    // =====================================
    $imgStmt = $conn->prepare("
        SELECT 
            id_juego,
            id_imagen,
            url_imagen
        FROM JUEGO_IMAGEN
        WHERE id_juego IN ($idsList)
    ");
    $imgStmt->execute();
    $imagenesRaw = $imgStmt->fetchAll(PDO::FETCH_ASSOC);

    $imagenesExtra = [];
    foreach ($imagenesRaw as $img) {
        $imagenesExtra[$img['id_juego']][] = [
            'id_imagen' => $img['id_imagen'],
            'url_imagen' => $img['url_imagen']
        ];
    }

    // =====================================
    // 5) Mezclar todo y enviar al front
    // =====================================
    foreach ($juegos as &$j) {
        $id = $j['id_juego'];
        $j['generos'] = $generos[$id] ?? [];
        $j['plataformas'] = $plataformas[$id] ?? [];
        $j['imagenes_extra'] = $imagenesExtra[$id] ?? [];
    }

    echo json_encode([
        'success' => true,
        'data' => $juegos
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
