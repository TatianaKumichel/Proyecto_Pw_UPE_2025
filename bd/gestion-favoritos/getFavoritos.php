<?php
require_once '../../inc/auth.php';
requierePermisoAPI('marcar_favorito');
require_once '../../inc/connection.php';
header('Content-Type: application/json');

// Obtener el ID del usuario logueado
$id_usuario = $_SESSION['id_usuario'];

try {
    $query = "SELECT 
            j.id_juego,
            j.titulo,
            j.descripcion,
            j.imagen_portada
        FROM FAVORITO f
        JOIN JUEGO j ON f.id_juego = j.id_juego
        WHERE f.id_usuario = :id_usuario
        ORDER BY f.fecha_agregado DESC";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($favoritos);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener favoritos: ' . $e->getMessage()]);
}


