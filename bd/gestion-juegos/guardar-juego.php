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

if ($empresa === '')
    $errores['empresa'] = "Debe indicar la empresa.";

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

if (!empty($errores)) {
    echo json_encode(['success' => false, 'errors' => $errores]);
    exit;
}

try {


    // Verificar que exista la empresa
    $stmt = $conn->prepare("SELECT id_empresa FROM EMPRESA WHERE id_empresa = ?");
    $stmt->execute([$empresa]);
    $dataEmpresa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dataEmpresa) {
        echo json_encode(['success' => false, 'error' => 'Empresa inválida']);
        exit;
    }

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

    // UPDATE EXISTENTE

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
            SET titulo = ?, descripcion = ?, fecha_lanzamiento = ?, id_empresa = ?, imagen_portada = ?, publicado = 1
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
        // NOTA: las imágenes extra NO se borran aca, solo se agregan nuevas

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

        //  IMÁGENES EXTRA (UPDATE)
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

    // INSERTAR NUEVO JUEGO
    $stmt = $conn->prepare("
        INSERT INTO JUEGO (titulo, descripcion, fecha_lanzamiento, id_empresa, imagen_portada, publicado)
        VALUES (?, ?, ?, ?, ?, 0)
    ");

    $stmt->execute([$titulo, $descripcion, $fecha, $id_empresa, $imagen_path]);

    $id_juego = $conn->lastInsertId();

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

    //  IMÁGENES EXTRA (CREATE)
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
