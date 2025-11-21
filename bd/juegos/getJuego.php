<?php
include '../../inc/connection.php';
header('Content-Type: application/json');

$id_juego = isset($_GET['id_juego']) ? intval($_GET['id_juego']) : 0;

if ($id_juego <= 0) {
    echo json_encode(['error' => 'ID de juego inválido']);
    exit;
}

try {
    // Datos principales
    $query = "SELECT j.*, e.nombre AS empresa
            FROM juego j
            JOIN empresa e ON j.id_empresa = e.id_empresa
            WHERE j.id_juego = :id_juego";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
    $stmt->execute();
    $juego = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$juego) {
        echo json_encode(['error' => 'Juego no encontrado']);
        exit;
    }

    // Imágenes
    $stmtImg = $conn->prepare("SELECT url_imagen FROM juego_imagen WHERE id_juego = :id");
    $stmtImg->bindParam(':id', $id_juego);
    $stmtImg->execute();
    $juego['imagenes'] = $stmtImg->fetchAll(PDO::FETCH_COLUMN);

    // Géneros
    $stmtGen = $conn->prepare("SELECT g.nombre FROM genero g
                             JOIN juego_genero jg ON g.id_genero = jg.id_genero
                             WHERE jg.id_juego = :id");
    $stmtGen->bindParam(':id', $id_juego);
    $stmtGen->execute();
    $juego['generos'] = $stmtGen->fetchAll(PDO::FETCH_COLUMN);

    // Plataformas
    $stmtPlat = $conn->prepare("SELECT p.nombre FROM plataforma p
                              JOIN juego_plataforma jp ON p.id_plataforma = jp.id_plataforma
                              WHERE jp.id_juego = :id");
    $stmtPlat->bindParam(':id', $id_juego);
    $stmtPlat->execute();
    $juego['plataformas'] = $stmtPlat->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($juego);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
