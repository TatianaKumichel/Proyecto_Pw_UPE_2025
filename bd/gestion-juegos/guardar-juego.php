<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_juegos');

require_once '../../inc/connection.php';
// Datos del formulario
$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$fecha = $_POST['fecha'] ?? null;
$empresa = $_POST['empresa'] ?? '';
$id = $_POST['id'] ?? null;
// generos y plataformas vienen como lista
$generos = isset($_POST['generos']) ? json_decode($_POST['generos'], true) : [];
$plataformas = isset($_POST['plataformas']) ? json_decode($_POST['plataformas'], true) : [];

$errores = [];

if ($titulo === '')
    $errores['titulo'] = "Debe ingresar un título.";
elseif (strlen($titulo) > 150)
    $errores['titulo'] = "El título no puede exceder los 150 caracteres.";

if ($descripcion === '')
    $errores['descripcion'] = "Debe ingresar una descripción.";
// validacion de fecha
if ($fecha === null || $fecha === '') {
    $errores['fecha'] = "Debe ingresar una fecha de lanzamiento.";
} else {
    $d = DateTime::createFromFormat('Y-m-d', $fecha);
    if (!($d && $d->format('Y-m-d') === $fecha)) {
        $errores['fecha'] = "Formato de fecha inválido.";
    }
}

if ($empresa === '') {
    $errores['empresa'] = "Debe seleccionar una empresa.";
} else {
    // Verificar que exista la empresa
    $stmt = $conn->prepare("SELECT COUNT(*) FROM EMPRESA WHERE id_empresa = ?");
    $stmt->execute([$empresa]);
    if ($stmt->fetchColumn() == 0) {
        $errores['empresa'] = "Debe seleccionar una empresa.";
    }
}

if (empty($generos)) {
    $errores['genero'] = "Debe seleccionar al menos un género.";
} else {
    // Verificar que existan los géneros
    $valores = implode(',', array_fill(0, count($generos), '?'));
    $stmt = $conn->prepare("SELECT COUNT(*) FROM genero WHERE id_genero IN ($valores)");
    $stmt->execute($generos);
    if ($stmt->fetchColumn() != count($generos)) {
        $errores['genero'] = "Uno o más géneros seleccionados no son válidos.";
    }
}

if (empty($plataformas)) {
    $errores['plataforma'] = "Debe seleccionar al menos una plataforma.";
} else {
    // Verificar que existan las plataformas
    $valores = implode(',', array_fill(0, count($plataformas), '?'));
    $stmt = $conn->prepare("SELECT COUNT(*) FROM plataforma WHERE id_plataforma IN ($valores)");
    $stmt->execute($plataformas);
    if ($stmt->fetchColumn() != count($plataformas)) {
        $errores['plataforma'] = "Una o más plataformas seleccionadas no son válidas.";
    }
}

// =============================================================================
// VALIDACIÓN DE IMÁGENES (Portada y Adicionales)
// =============================================================================
// valores aceptados
$maxSize = 140 * 1024; // 140 KB
$maxWidth = 600;
$maxHeight = 338;

// 1. Validar Portada
if (!empty($_FILES['imagen']['name'])) {
    if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Validar Peso
        if ($_FILES['imagen']['size'] > $maxSize) {
            $errores['imagen'] = "La imagen de portada excede el peso máximo de 140KB.";
        } else {
            // Validar Dimensiones
            $dims = getimagesize($_FILES['imagen']['tmp_name']);
            if ($dims) {
                if ($dims[0] > $maxWidth || $dims[1] > $maxHeight) {
                    $errores['imagen'] = "La portada excede las dimensiones permitidas ({$maxWidth}x{$maxHeight}px).";
                }
            } else {
                $errores['imagen'] = "El archivo de portada no es una imagen válida.";
            }
        }
        // Validar Tipo (MIME)
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['imagen']['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowedMimes)) {
            $errores['imagen'] = "Formato de imagen no válido.";
        }
    } else {
        $errores['imagen'] = "Error al subir la imagen.";
    }
} elseif (!$id) {
    // Si no hay imagen
    $errores['imagen'] = "Debe subir una imagen.";
}

// 2. Validar Imágenes adicionales
if (!empty($_FILES['imagenesExtra']['name'][0])) {
    foreach ($_FILES['imagenesExtra']['name'] as $key => $name) {
        if ($_FILES['imagenesExtra']['error'][$key] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['imagenesExtra']['tmp_name'][$key];
            
            // Peso
            if ($_FILES['imagenesExtra']['size'][$key] > $maxSize) {
                $errores['imagenesExtra'] = "Una o más imágenes adicionales exceden los 140KB.";
            }
            // Dimensiones
            $dims = getimagesize($tmpName);
            if ($dims) {
                if ($dims[0] > $maxWidth || $dims[1] > $maxHeight) {
                    $errores['imagenesExtra'] = "Una o más imágenes adicionales exceden {$maxWidth}x{$maxHeight}px.";
                }
            }
             // MIME
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmpName);
            finfo_close($finfo);
            if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
                $errores['imagenesExtra'] = "Una o más imágenes adicionales tienen formato inválido.";
            }
        }
    }
}

if (!empty($errores)) {
    echo json_encode(['success' => false, 'errors' => $errores]);
    exit;
}

try {

    $id_empresa = $empresa;

    // DIRECTORIO DE IMAGENES

    $uploadDir = __DIR__ . '/../../img/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // IMAGEN PORTADA

    $imagen_path = null;

    if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

        $tmp = $_FILES['imagen']['tmp_name'];
        $name = time() . "_" . basename($_FILES['imagen']['name']);
        $target = $uploadDir . $name;

        if (move_uploaded_file($tmp, $target)) {
            $imagen_path = "img/uploads/" . $name;
        }
    }

    // JUEGO EXISTENTE

    if ($id) {

        // Obtener imagen existente si no se subió nueva
        if ($imagen_path === null) {
            $stmt = $conn->prepare("SELECT imagen_portada FROM JUEGO WHERE id_juego = ?");
            $stmt->execute([$id]);
            $imagen_path = $stmt->fetchColumn();
        }

        if (!$id && empty($_FILES['imagen']['name'])) {
            echo json_encode(['success' => false, 'error' => 'Debes subir una imagen de portada.']);
            exit;
        }

        if (!empty($_POST['imagenesAEliminar'])) {
            $lista = json_decode($_POST['imagenesAEliminar'], true);
            foreach ($lista as $idImg) {
                $stmt = $conn->prepare("DELETE FROM JUEGO_IMAGEN WHERE id_imagen = ?");
                $stmt->execute([$idImg]);
            }
        }

        $stmt = $conn->prepare("
            UPDATE JUEGO 
            SET titulo = ?, descripcion = ?, fecha_lanzamiento = ?, id_empresa = ?, imagen_portada = ?
            WHERE id_juego = ?
        ");

        $stmt->execute([
            $titulo,
            $descripcion,
            $fecha,
            $id_empresa,
            $imagen_path,
            $id
        ]);

        // Limpiar relaciones previas
        $conn->prepare("DELETE FROM JUEGO_GENERO WHERE id_juego = ?")->execute([$id]);
        $conn->prepare("DELETE FROM JUEGO_PLATAFORMA WHERE id_juego = ?")->execute([$id]);

        // géneros
        foreach ($generos as $g) {
            $stmt = $conn->prepare("INSERT INTO JUEGO_GENERO (id_juego, id_genero) VALUES (?, ?)");
            $stmt->execute([$id, $g]);
        }

        // plataformas
        foreach ($plataformas as $p) {
            $stmt = $conn->prepare("INSERT INTO JUEGO_PLATAFORMA (id_juego, id_plataforma) VALUES (?, ?)");
            $stmt->execute([$id, $p]);
        }

        //  Imagenes adicionales
        if (!empty($_FILES['imagenesExtra']) && is_array($_FILES['imagenesExtra']['name'])) {
            $names = $_FILES['imagenesExtra']['name'];
            $tmpNames = $_FILES['imagenesExtra']['tmp_name'];
            $errors = $_FILES['imagenesExtra']['error'];

            foreach ($names as $idx => $nombreImg) {
                if ($errors[$idx] === UPLOAD_ERR_OK && $tmpNames[$idx] !== '') {
                    $safeName = time() . "_" . $idx . "_" . basename($nombreImg);
                    $target = $uploadDir . $safeName;

                    if (move_uploaded_file($tmpNames[$idx], $target)) {
                        $url = "img/uploads/" . $safeName;

                        $stmt = $conn->prepare("INSERT INTO JUEGO_IMAGEN (id_juego, url_imagen) VALUES (?, ?)");
                        $stmt->execute([$id, $url]);
                    }
                }
            }
        }

        echo json_encode(['success' => true, 'message' => 'Juego actualizado correctamente.']);
        exit;
    }

    // nuevo juego
    $stmt = $conn->prepare("
        INSERT INTO JUEGO (titulo, descripcion, fecha_lanzamiento, id_empresa, imagen_portada, publicado)
        VALUES (?, ?, ?, ?, ?, 0)
    ");

    $stmt->execute([$titulo, $descripcion, $fecha, $id_empresa, $imagen_path]);

    $id_juego = $conn->lastInsertId();

    // géneros
    foreach ($generos as $g) {
        $stmt = $conn->prepare("INSERT INTO JUEGO_GENERO (id_juego, id_genero) VALUES (?, ?)");
        $stmt->execute([$id_juego, $g]);
    }

    // plataformas
    foreach ($plataformas as $p) {
        $stmt = $conn->prepare("INSERT INTO JUEGO_PLATAFORMA (id_juego, id_plataforma) VALUES (?, ?)");
        $stmt->execute([$id_juego, $p]);
    }

    //  Imagenes adicionales
    if (!empty($_FILES['imagenesExtra']) && is_array($_FILES['imagenesExtra']['name'])) {
        $names = $_FILES['imagenesExtra']['name'];
        $tmpNames = $_FILES['imagenesExtra']['tmp_name'];
        $errors = $_FILES['imagenesExtra']['error'];

        foreach ($names as $idx => $nombreImg) {
            if ($errors[$idx] === UPLOAD_ERR_OK && $tmpNames[$idx] !== '') {
                $safeName = time() . "_" . $idx . "_" . basename($nombreImg);
                $target = $uploadDir . $safeName;

                if (move_uploaded_file($tmpNames[$idx], $target)) {
                    $url = "img/uploads/" . $safeName;

                    $stmt = $conn->prepare("INSERT INTO JUEGO_IMAGEN (id_juego, url_imagen) VALUES (?, ?)");
                    $stmt->execute([$id_juego, $url]);
                }
            }
        }
    }

    echo json_encode(['success' => true, 'message' => 'Juego creado correctamente.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
