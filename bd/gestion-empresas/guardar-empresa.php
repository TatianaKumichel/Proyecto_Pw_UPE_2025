<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_empresas');

require_once '../../inc/connection.php';

$nombre = trim($_POST['nombre_empresa'] ?? '');
$sitio_web = trim($_POST['sitio_web'] ?? '');
$id = $_POST['id'] ?? null;

$errores = [];

if ($nombre === '') {
    $errores['nombre_empresa'] = "Debe ingresar el nombre de la empresa.";
} elseif (strlen($nombre) < 2) {
    $errores['nombre_empresa'] = "El nombre debe tener al menos 2 caracteres.";
}

if ($sitio_web === '') {
    $errores['sitio_web'] = "Debe ingresar el sitio web.";
} elseif (!filter_var($sitio_web, FILTER_VALIDATE_URL)) {
    $errores['sitio_web'] = "Debe ingresar una URL válida (ejemplo: https://www.ejemplo.com).";
}

if (!empty($errores)) {
    echo json_encode([
        'success' => false,
        'errors' => $errores
    ]);
    exit;
}

try {

    if ($id) {

        $sqlCheck = "SELECT COUNT(*) FROM empresa WHERE nombre = :nombre AND id_empresa <> :id";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':nombre', $nombre);
        $stmtCheck->bindParam(':id', $id);
    } else {

        $sqlCheck = "SELECT COUNT(*) FROM empresa WHERE nombre = :nombre";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':nombre', $nombre);
    }

    $stmtCheck->execute();
    $existe = $stmtCheck->fetchColumn();

    if ($existe > 0) {
        echo json_encode([
            'success' => false,
            'errors' => ['nombre_empresa' => 'Ya existe una empresa con ese nombre.']
        ]);
        exit;
    }


    if ($id) {
        // EDITAR
        $sql = "UPDATE empresa 
                SET nombre = :nombre, sitio_web = :sitio_web 
                WHERE id_empresa = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
    } else {
        // CREAR
        $sql = "INSERT INTO empresa (nombre, sitio_web) 
                VALUES (:nombre, :sitio_web)";
        $stmt = $conn->prepare($sql);
    }

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':sitio_web', $sitio_web);
    $ok = $stmt->execute();

    echo json_encode([
        'success' => $ok,
        'message' => $id
            ? "Empresa actualizada correctamente."
            : "Empresa creada correctamente."
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'errors' => ['general' => 'Ocurrio un Error']
    ]);
}
?>