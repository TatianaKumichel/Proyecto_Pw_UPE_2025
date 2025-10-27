<?php
require 'connection.php';
// ejemplo, luego traer el id usuario logueado
$id_usuario = 1;
header('Content-Type: application/json; charset=utf-8');
try {
    $query = "SELECT 
            j.id_juego,
            j.titulo,
            j.descripcion,
            j.imagen_portada
        FROM favorito f
        JOIN juego j ON f.id_juego = j.id_juego
        WHERE f.id_usuario = :id_usuario
        ORDER BY f.fecha_agregado DESC";
    $stmt = $conn->prepare($query);
    //$stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

    $favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Validamos si no hay resultados
    if (!$favoritos) {
        echo json_encode([]);
        exit;
    }

    echo json_encode($favoritos);

} catch (PDOException $e) {
    //http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>