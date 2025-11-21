<?php
<<<<<<< HEAD
session_start();
require_once '../../inc/connection.php';
header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado. Debes iniciar sesión.']);
    exit;
}

// Obtener el ID del usuario logueado
$id_usuario = $_SESSION['id_usuario'];

=======

include '../../inc/connection.php';
header('Content-Type: application/json');
// ejemplo, luego traer el id usuario logueado
$id_usuario = 1;
>>>>>>> 165ef6c (pasaron cosas con git)
try {
    $query = "SELECT 
            j.id_juego,
            j.titulo,
            j.descripcion,
            j.imagen_portada
<<<<<<< HEAD
        FROM FAVORITO f
        JOIN JUEGO j ON f.id_juego = j.id_juego
        WHERE f.id_usuario = :id_usuario
        ORDER BY f.fecha_agregado DESC";

=======
        FROM favorito f
        JOIN juego j ON f.id_juego = j.id_juego
        WHERE f.id_usuario = :id_usuario
        ORDER BY f.fecha_agregado DESC";
>>>>>>> 165ef6c (pasaron cosas con git)
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
<<<<<<< HEAD

    echo json_encode($favoritos);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener favoritos: ' . $e->getMessage()]);
}


=======
    echo json_encode($favoritos);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
>>>>>>> 165ef6c (pasaron cosas con git)
