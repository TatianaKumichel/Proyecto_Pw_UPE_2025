<?php
require_once "./inc/connection.php";
header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   

    // Obtenengo el cuerpo de la solicitud que envió JS
    $json_data = file_get_contents('php://input');

    //Decodifico esa cadena JSON a un array de PHP
    $data = json_decode($json_data, true);

    // accedo a la variable nombre
    $nuevoNombre = isset($data['nombre']) ? trim($data['nombre']) : '';
    
   //id tomo de la sesion
    $id = $_SESSION['id_usuario'];

    $errores = [];

    if (empty($nuevoNombre)) {
        $errores['nombre'] = "El nombre de usuario no puede estar vacío.";
    }

    if (strlen($nuevoNombre) < 3 || strlen($nuevoNombre) > 50) {
        $errores['nombre'] = "El nombre debe tener entre 3 y 50 caracteres.";
    }

    if (!empty($errores)) {
        echo json_encode(["success" => false, "errors" => $errores]);
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE USUARIO SET username = :nombre WHERE id_usuario = :id");
        $stmt->execute([':nombre' => $nuevoNombre, ':id' => $id]);

        $_SESSION['nombre'] = $nuevoNombre;

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error en la actualización: " . $e->getMessage()]);
    }
}
?>

