<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_faq');
require_once '../../inc/connection.php';

$pregunta = $_POST['pregunta'] ?? '';
$respuesta = $_POST['respuesta'] ?? '';
$id = $_POST['id'] ?? null;
//$id_autor = $_SESSION['id_usuario'];
$id_autor = 1; // en la creacion se deberia guardar el id del moderador de la session, cuando se implemente 

$errores = [];

// validar
if ($pregunta === '') {
    $errores['pregunta'] = "Debe ingresar una pregunta.";
} elseif (!str_starts_with($pregunta, '¿') || !str_ends_with($pregunta, '?')) {
    $errores['pregunta'] = "La pregunta debe comenzar con '¿' y terminar con '?'.";
}

if ($respuesta === '') {
    $errores['respuesta'] = "Debe ingresar una respuesta.";
} elseif (strlen($respuesta) < 5) {
    $errores['respuesta'] = "La respuesta debe tener al menos 5 caracteres.";
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
        // EDITAR
        $sql = "UPDATE faq SET pregunta = :pregunta, respuesta = :respuesta WHERE id_faq = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
    } else {
        // CREAR
        $sql = "INSERT INTO faq (pregunta, respuesta, id_autor) VALUES (:pregunta, :respuesta, :id_autor)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_autor', $id_autor);
    }

    $stmt->bindParam(':pregunta', $pregunta);
    $stmt->bindParam(':respuesta', $respuesta);
    $ok = $stmt->execute();

    echo json_encode([
        'success' => $ok,
        'message' => $id ? "FAQ actualizada correctamente." : "FAQ creada correctamente."
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'errors' => ['general' => 'Error en la base de datos: ' . $e->getMessage()]
    ]);
}
?>