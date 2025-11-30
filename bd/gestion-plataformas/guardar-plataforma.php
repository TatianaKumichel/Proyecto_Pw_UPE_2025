<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_plataformas');

require_once '../../inc/connection.php';

$nombre = trim($_POST['nombre'] ?? '');
$action = $_POST['action'] ?? null;
$id = $_POST['id'] ?? null;
/* antigua validacion
if ($nombre === "" || !$action) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit;
}
*/

$errores = [];
if (!$action || !in_array($action, ['create', 'update'])) {
    $errores['general'] = "Acción inválida.";
}

// se valida que si se va a editar el id este
if ($action === 'update' && (!$id || !ctype_digit(strval($id)))) {
    $errores['id'] = "Ocurrio un error para editar.";
}

if (!empty($errores)) {
    echo json_encode(['success' => false, 'errors' => $errores]);
    exit;
}



if ($nombre === "") {
    $errores['nombre'] = "Debe ingresar el nombre de la plataforma.";
} else {


    if (strlen($nombre) < 2) {
        $errores['nombre'] = "El nombre debe tener al menos 2 caracteres.";
    }

    if (strlen($nombre) > 50) {
        $errores['nombre'] = "El nombre no puede superar los 50 caracteres.";
    }

    if ($nombre !== trim($nombre)) {
        $errores['nombre'] = "El nombre no puede iniciar o terminar con espacios.";
    }

    if (!preg_match('/^[A-Za-z0-9\sáéíóúÁÉÍÓÚñÑ.,-]+$/', $nombre)) {
        $errores['nombre'] = "El nombre contiene caracteres inválidos.";
    }

    if (ctype_digit($nombre)) {
        $errores['nombre'] = "El nombre no puede ser solo números.";
    }
}
if (!empty($errores)) {
    echo json_encode(['success' => false, 'errors' => $errores]);
    exit;
}

try {
    // Verificar si ya existe
    if ($id) {
        $sqlCheck = "SELECT COUNT(*) FROM plataforma WHERE nombre = :nombre AND id_plataforma <> :id";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':nombre', $nombre);
        $stmtCheck->bindParam(':id', $id);
    } else {
        $sqlCheck = "SELECT COUNT(*) FROM plataforma WHERE nombre = :nombre";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':nombre', $nombre);
    }

    $stmtCheck->execute();
    $existe = $stmtCheck->fetchColumn();

    if ($existe > 0) {
        echo json_encode(['success' => false, 'errors' => ['nombre' => 'Ya existe una plataforma con ese nombre.']]);
        exit;
    }

    if ($id) {
        // EDITAR
        $sql = "UPDATE plataforma SET nombre = :nombre WHERE id_plataforma = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
    } else {
        // CREAR
        $sql = "INSERT INTO plataforma (nombre) VALUES (:nombre)";
        $stmt = $conn->prepare($sql);
    }

    $stmt->bindParam(':nombre', $nombre);
    $ok = $stmt->execute();

    echo json_encode([
        'success' => $ok,
        'message' => $id ? "Plataforma actualizada correctamente." : "Plataforma creada correctamente."
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'errors' => ['general' => 'Error en la base de datos: ' . $e->getMessage()]]);
}
?>