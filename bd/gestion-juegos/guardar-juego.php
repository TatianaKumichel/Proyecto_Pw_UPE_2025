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
// Datos del formulario
// ================================

$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$fecha = $_POST['fecha'] ?? null;
$empresa = $_POST['empresa'] ?? '';
$id = $_POST['id'] ?? null;

$generos = isset($_POST['generos']) ? json_decode($_POST['generos'], true) : [];
$plataformas = isset($_POST['plataformas']) ? json_decode($_POST['plataformas'], true) : [];

$errores = [];

if ($titulo === '')  $errores['titulo'] = "Debe ingresar un título.";
if ($descripcion === '') $errores['descripcion'] = "Debe ingresar una descripción.";
if ($empresa === '') $errores['empresa'] = "Debe indicar la empresa.";
if (empty($generos)) $errores['genero'] = "Debe seleccionar al menos un género.";
if (empty($plataformas)) $errores['plataforma'] = "Debe seleccionar al menos una plataforma.";

if (!empty($errores)) {
    echo json_encode(['success' => false, 'errors' => $errores]);
    exit;
}

try {

    // ====================================
    // EMPRESA
    // ====================================
    $stmt = $conn->prepare("SELECT id_empresa FROM EMPRESA WHERE id_empresa = ?");
    $stmt->execute([$empresa]);
    $dataEmpresa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dataEmpresa) {
        echo json_encode(['success' => false, 'error' => 'Empresa inválida']);
        exit;
    }

    $id_empresa = $empresa;

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
    // UPDATE EXISTENTE
    // ====================================
    if ($id) {

        // Obtener imagen existente si no se subió nueva
        if ($imagen_path === null) {
            $stmt = $conn->prepare("SELECT imagen_portada FROM JUEGO WHERE id_juego = ?");
            $stmt->execute([$id]);
            $imagen_path = $stmt->fetchColumn();
        }

        $stmt = $conn->prepare("
            UPDATE JUEGO 
            SET titulo = ?, descripcion = ?, fecha_lanzamiento = ?, id_empresa = ?, imagen_portada = ?, publicado = 1
            WHERE id_juego = ?
        ");

        $stmt->execute([
            $titulo, $descripcion, $fecha, $id_empresa, $imagen_path, $id
        ]);

        // Limpiar relaciones previas
        $conn->prepare("DELETE FROM JUEGO_GENERO WHERE id_juego = ?")->execute([$id]);
        $conn->prepare("DELETE FROM JUEGO_PLATAFORMA WHERE id_juego = ?")->execute([$id]);

        // Insertar géneros
        foreach ($generos as $g) {
            $stmt = $conn->prepare("INSERT INTO JUEGO_GENERO (id_juego, id_genero) VALUES (?, ?)");
            $stmt->execute([$id, $g]);
        }

        // Insertar plataformas
        foreach ($plataformas as $p) {
            $stmt = $conn->prepare("INSERT INTO JUEGO_PLATAFORMA (id_juego, id_plataforma) VALUES (?, ?)");
            $stmt->execute([$id, $p]);
        }

        echo json_encode(['success' => true, 'message' => 'Juego actualizado correctamente.']);
        exit;
    }

    // ====================================
    // INSERTAR NUEVO JUEGO (sin SP)
    // ====================================

    $stmt = $conn->prepare("
        INSERT INTO JUEGO (titulo, descripcion, fecha_lanzamiento, id_empresa, imagen_portada, publicado)
        VALUES (?, ?, ?, ?, ?, 1)
    ");

    $stmt->execute([$titulo, $descripcion, $fecha, $id_empresa, $imagen_path]);

    $id_juego = $conn->lastInsertId(); // ← AHORA SIEMPRE FUNCIONA

    // Insertar géneros
    foreach ($generos as $g) {
        $stmt = $conn->prepare("INSERT INTO JUEGO_GENERO (id_juego, id_genero) VALUES (?, ?)");
        $stmt->execute([$id_juego, $g]);
    }

    // Insertar plataformas
    foreach ($plataformas as $p) {
        $stmt = $conn->prepare("INSERT INTO JUEGO_PLATAFORMA (id_juego, id_plataforma) VALUES (?, ?)");
        $stmt->execute([$id_juego, $p]);
    }

    echo json_encode(['success' => true, 'message' => 'Juego creado correctamente.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
