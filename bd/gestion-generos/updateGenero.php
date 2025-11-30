<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_generos');

require_once __DIR__ . '/../../inc/connection.php';

// Obtener datos JSON
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id_genero']) ? intval($input['id_genero']) : 0;
$nombre = isset($input['nombre']) ? trim($input['nombre']) : '';
/*
if ($id <= 0 || empty($nombre)) {
    echo json_encode(['ok' => false, 'error' => 'Datos inválidos']);
    exit;
}*/
$errores = [];
if ($id <= 0) {  /**id invalido */
    echo json_encode(['ok' => false, 'error' => 'Ocurrio un error para editar']);
    exit;
}

// Validaciones iguales a las de INSERT
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

if (!empty($errores)) {
    echo json_encode(['ok' => false, 'error' => $errores]);
    exit;
}

// Verificar si el género existe
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM genero WHERE id_genero = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->fetchColumn() == 0) {
        echo json_encode(['ok' => false, 'error' => 'El género no existe']);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al verificar género: ' . $e->getMessage()]);
    exit;
}

// Verificar si ya existe otro género con el mismo nombre
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM genero WHERE nombre = :nombre AND id_genero != :id");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['ok' => false, 'error' => 'Ya existe otro género con ese nombre']);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al verificar duplicados: ' . $e->getMessage()]);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE genero SET nombre=:nombre WHERE id_genero=:id");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['ok' => true, 'message' => 'Género actualizado correctamente']);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al actualizar género: ' . $e->getMessage()]);
}
