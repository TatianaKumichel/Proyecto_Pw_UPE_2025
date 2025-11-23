<?php
include '../../inc/connection.php';
header('Content-Type: application/json');
session_start();

// Solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit;
}

// ================================
// Datos principales del formulario
// ================================

$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$fecha = $_POST['fecha'] ?? null;
$empresa = $_POST['empresa'] ?? '';
$id = $_POST['id'] ?? null;

$generos = isset($_POST['generos']) ? json_decode($_POST['generos'], true) : [];
$plataformas = isset($_POST['plataformas']) ? json_decode($_POST['plataformas'], true) : [];

$errores = [];

if ($titulo === '')
    $errores['titulo'] = "Debe ingresar un título.";
if ($descripcion === '')
    $errores['descripcion'] = "Debe ingresar una descripción.";
if ($empresa === '')
    $errores['empresa'] = "Debe indicar la empresa.";
if (empty($generos))
    $errores['genero'] = "Debe seleccionar al menos un género.";
if (empty($plataformas))
    $errores['plataforma'] = "Debe seleccionar al menos una plataforma.";

if (!empty($errores)) {
    echo json_encode(['success' => false, 'errors' => $errores]);
    exit;
}

try {

    // ====================================
    // EMPRESA
    // ====================================
    $stmt = $conn->prepare("SELECT id_empresa FROM EMPRESA WHERE nombre = :nombre");
    $stmt->execute([':nombre' => $empresa]);
    $dataEmpresa = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dataEmpresa) {
        $id_empresa = $dataEmpresa['id_empresa'];
    } else {
        $stmt = $conn->prepare("INSERT INTO EMPRESA (nombre) VALUES (:nombre)");
        $stmt->execute([':nombre' => $empresa]);
        $id_empresa = $conn->lastInsertId();
    }

    // ====================================
    // IMAGEN
    // ====================================
    $imagen_path = null;

    if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

        $uploadDir = __DIR__ . '/../../img/uploads/';
        if (!is_dir($uploadDir))
            mkdir($uploadDir, 0755, true);

        $tmp = $_FILES['imagen']['tmp_name'];
        $name = time() . "_" . basename($_FILES['imagen']['name']);
        $target = $uploadDir . $name;

        if (move_uploaded_file($tmp, $target)) {
            $imagen_path = "img/uploads/" . $name;
        }
    }

    // ====================================
    // UPDATE
    // ====================================
    if ($id) {

        // si no se subió imagen nueva → recuperar la existente
        if ($imagen_path === null) {
            $row = $conn->prepare("SELECT imagen_portada FROM JUEGO WHERE id_juego = ?");
            $row->execute([$id]);
            $imagen_path = $row->fetchColumn();
        }

        $stmt = $conn->prepare("CALL SP_JUEGO_UPDATE(
            :id, :titulo, :descripcion, :fecha, :id_empresa, :imagen, :publicado
        )");

        $stmt->execute([
            ':id' => $id,
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fecha' => $fecha,
            ':id_empresa' => $id_empresa,
            ':imagen' => $imagen_path,
            ':publicado' => 1
        ]);

        $stmt->closeCursor();

        // limpiar relaciones viejas
        $conn->prepare("DELETE FROM JUEGO_GENERO WHERE id_juego = ?")->execute([$id]);
        $conn->prepare("DELETE FROM JUEGO_PLATAFORMA WHERE id_juego = ?")->execute([$id]);

        // insertar géneros
        foreach ($generos as $g) {
            $stmt = $conn->prepare("CALL SP_JUEGO_GENERO_PUT(:id_juego, :id_genero)");
            $stmt->execute([':id_juego' => $id, ':id_genero' => $g]);
            $stmt->closeCursor();
        }

        // insertar plataformas
        foreach ($plataformas as $p) {
            $stmt = $conn->prepare("CALL SP_JUEGO_PLATAFORMA_PUT(:id_juego, :id_plataforma)");
            $stmt->execute([':id_juego' => $id, ':id_plataforma' => $p]);
            $stmt->closeCursor();
        }

        echo json_encode(['success' => true, 'message' => 'Juego actualizado correctamente.']);
        exit;
    }

    // ====================================
    // INSERT NUEVO → SP_JUEGO_PUT
    // ====================================

    $stmt = $conn->prepare("CALL SP_JUEGO_PUT(
        :titulo, :descripcion, :fecha, :id_empresa, :imagen, :publicado
    )");

    $stmt->execute([
        ':titulo' => $titulo,
        ':descripcion' => $descripcion,
        ':fecha' => $fecha,
        ':id_empresa' => $id_empresa,
        ':imagen' => $imagen_path,
        ':publicado' => 1
    ]);

    $stmt->closeCursor();

    // obtener ID nuevo
    $stmt2 = $conn->query("SELECT LAST_INSERT_ID()");
    $id_juego = $stmt2->fetchColumn();

    // insertar géneros
    foreach ($generos as $g) {
        $stmt = $conn->prepare("CALL SP_JUEGO_GENERO_PUT(:id_juego, :id_genero)");
        $stmt->execute([':id_juego' => $id_juego, ':id_genero' => $g]);
        $stmt->closeCursor();
    }

    // insertar plataformas
    foreach ($plataformas as $p) {
        $stmt = $conn->prepare("CALL SP_JUEGO_PLATAFORMA_PUT(:id_juego, :id_plataforma)");
        $stmt->execute([':id_juego' => $id_juego, ':id_plataforma' => $p]);
        $stmt->closeCursor();
    }

    echo json_encode(['success' => true, 'message' => 'Juego creado correctamente.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>