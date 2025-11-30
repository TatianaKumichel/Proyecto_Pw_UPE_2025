<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_generos');

require_once __DIR__ . '/../../inc/connection.php';

// Obtener datos JSON
$input = json_decode(file_get_contents('php://input'), true);
$nombre = isset($input['nombre']) ? trim($input['nombre']) : '';

/*antigua validacion 
if (empty($nombre)) {
    echo json_encode(['ok' => false, 'error' => 'El nombre es obligatorio']);
    exit;
}*/
$errores = [];

// validar nombre

if ($nombre === "") {
    $errores[] = "El nombre es obligatorio.";
} else {

    if (strlen($nombre) < 3) {
        $errores[] = "El nombre debe tener al menos 3 caracteres.";
    }

    if (strlen($nombre) > 50) {
        $errores[] = "El nombre no puede superar los 50 caracteres.";
    }


    if (!preg_match('/^[A-Za-z0-9\sáéíóúÁÉÍÓÚñÑ.,-]+$/', $nombre)) {
        $errores[] = "El nombre contiene caracteres inválidos.";
    }

    if (ctype_digit($nombre)) {
        $errores[] = "El nombre no puede ser solo números.";
    }

    if (!preg_match('/[A-Za-záéíóúÁÉÍÓÚñÑ]/', $nombre)) {
        $errores[] = "El nombre debe contener al menos una letra.";
    }
}

// si hay errores retorno
if (!empty($errores)) {
    echo json_encode(['ok' => false, 'error' => $errores]);
    exit;
}

// Verificar si el género ya existe
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM genero WHERE nombre = :nombre");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['ok' => false, 'error' => 'Ya existe un género con ese nombre']);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al verificar duplicados: ' . $e->getMessage()]);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO genero(nombre) VALUES(:nombre)");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->execute();
    echo json_encode(['ok' => true, 'message' => 'Género creado correctamente']);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al crear género: ' . $e->getMessage()]);
}