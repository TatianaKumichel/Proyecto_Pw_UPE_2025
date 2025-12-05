<?php
require_once "./auth.php";
requiereLoginAPI();
require_once "./connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
     //Obtengo el cuerpo de la solicitud que envió JS
    $json_data = file_get_contents('php://input');

    //Decodifico esa cadena JSON a un array de PHP
    $data = json_decode($json_data, true);

    // accedo a la variable contrasena
    $nuevaContrasena = isset($data['contrasena']) ? trim($data['contrasena']) : '';
    
     
    $id = $_SESSION['id_usuario'];

    $errores = [];

    if (empty($nuevaContrasena)) {
        $errores['contrasena'] = "La contraseña no puede estar vacía.";
    } elseif (strlen($nuevaContrasena) < 6) {
        $errores['contrasena'] = "La contraseña debe tener al menos 6 caracteres.";
    }

    if (!empty($errores)) {
        echo json_encode(["success" => false, "errors" => $errores]);
        exit;
    }

    // Hasheamos la contraseña antes de guardarla
    $hash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("UPDATE USUARIO SET password_hash = :hash WHERE id_usuario = :id");
        $stmt->execute([':hash' => $hash, ':id' => $id]);

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error en la actualización: " . $e->getMessage()]);
    }
}
?>
