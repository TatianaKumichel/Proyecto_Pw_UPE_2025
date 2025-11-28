<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_plataformas');

require_once '../../inc/connection.php';

$nombre = trim($_POST['nombre'] ?? '');
$id = $_POST['id'] ?? null;

$errores = [];

if ($nombre === '') {
    $errores['nombre'] = "Debe ingresar el nombre de la plataforma.";
} elseif (strlen($nombre) < 2) {
    $errores['nombre'] = "El nombre debe tener al menos 2 caracteres.";
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
