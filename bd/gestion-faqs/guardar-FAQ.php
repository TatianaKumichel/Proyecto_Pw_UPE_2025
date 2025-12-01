<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_faq');
require_once '../../inc/connection.php';

$pregunta = $_POST['pregunta'] ?? '';
$respuesta = $_POST['respuesta'] ?? '';
$id = $_POST['id'] ?? null;
//$id_autor = $_SESSION['id_usuario'];
$id_autor = 1; // en la creacion se deberia guardar el id del moderador de la session, cuando se implemente 

// La pregunta empieza con '¿', termina con '?', se aceptan letras, numeros y simbolos, entre 5 y 255 caracteres.
$regexPregunta = '/^¿[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 !"#$%&\'()*+,.\-\/:;<=>@\[\]\^_`{|}~]{5,255}\?$/u';
// La respuesta no tiene la restriccion de empezar con '¿' ni de terminar con '?'
$regexRespuesta = '/^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 !"#$%&\'()*+,\-\.\/:;<=>@\[\]\^_`{|}~]{5,255}$/u';

$errores = [];

// validaciones
if ($pregunta === '') {
    $errores['pregunta'] = "Debe ingresar una pregunta.";
} elseif (!preg_match($regexPregunta, $pregunta)) {
    $errores['pregunta'] = "La pregunta debe comenzar con '¿' y terminar con '?', debe tener entre 5 y 255 caracteres y se aceptan letras, simbolos y numeros. No puede contener '¿' o '?' en medio de la pregunta.";
}

if ($respuesta === '') {
    $errores['respuesta'] = "Debe ingresar una respuesta.";
} elseif (!preg_match($regexRespuesta, $respuesta)) {
    $errores['respuesta'] = "La respuesta debe tener entre 5 y 255 caracteres. Se permiten letras, numeros y simbolos.";
}

// if ($pregunta === '') {
//     $errores['pregunta'] = "Debe ingresar una pregunta.";
// } elseif (!preg_match($regexPregunta, $pregunta)) {
//     $errores['pregunta'] = "La pregunta no tiene el formato correspondiente.";
// }

// if ($respuesta === '') {
//     $errores['respuesta'] = "Debe ingresar una respuesta.";
// } elseif (strlen($respuesta) < 5) {
//     $errores['respuesta'] = "La respuesta debe tener al menos 5 caracteres.";
// } elseif (strlen($respuesta) > 255) {
//     $errores['respuesta'] = "La respuesta no puede superar los 255 caracteres.";
// }


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