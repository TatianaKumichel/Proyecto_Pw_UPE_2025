<?php
<<<<<<< HEAD
session_start();
require_once '../../inc/connection.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autorizado. Debes iniciar sesión.']);
    exit;
}

// Obtener el ID del usuario logueado
$id_usuario = $_SESSION['id_usuario'];
=======
include '../../inc/connection.php';
>>>>>>> 165ef6c (pasaron cosas con git)

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (strpos($contentType, 'application/json') !== false) {
    // json en el body
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);
} else {
    // formulario nativo multipart/form-data o application/x-www-form-urlencoded
    $data = $_POST;
}

// Verificar si se recibieron datos
<<<<<<< HEAD
if (empty($data) || !isset($data['id_juego'])) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'No se recibió el ID del juego',
=======
if (empty($data)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
        'error' => 'No se recibieron datos',
>>>>>>> 165ef6c (pasaron cosas con git)
    ]);
    exit;
}

<<<<<<< HEAD
$id_juego = $data['id_juego'];
header('Content-Type: application/json');

try {
    // Verificar si ya existe el favorito
    $stmt = $conn->prepare("SELECT COUNT(*) FROM FAVORITO WHERE id_usuario = :id_usuario AND id_juego = :id_juego");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'error' => 'Este juego ya está en tus favoritos']);
        exit;
    }

    // Insertar el favorito
    $query = 'INSERT INTO FAVORITO (id_juego, id_usuario, fecha_agregado) 
              VALUES (:id_juego, :id_usuario, NOW())';

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $ok = $stmt->execute();

    if ($ok) {
        $idResultante = $conn->lastInsertId();
        echo json_encode(['success' => true, 'message' => 'Favorito agregado correctamente', 'id' => $idResultante]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo agregar el favorito']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al agregar favorito: ' . $e->getMessage()]);
}


=======
$query = 'INSERT INTO favorito
SET id_juego = :id_juego,
id_usuario = :id_usuario,
fecha_agreagado = NOW()
';

$stmt = $conn->prepare($query);
$stmt->bindParam(':id_juego', $data['id_juego']);
$stmt->bindParam(':id_usuario', $data['id_usuario']);
$stmt->execute();

$idResultante = $conn->lastInsertId();
header('Content-Type: application/json');

echo json_encode(['success' => $ok, 'message' => "Favorito agregado correctamente"]);
>>>>>>> 165ef6c (pasaron cosas con git)
