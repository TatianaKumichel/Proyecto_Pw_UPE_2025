<?php
require_once "./auth.php";
requiereLoginAPI();
require_once "./connection.php";
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    $nuevoNombre = isset($data['nombre']) ? trim($data['nombre']) : '';
    $id = $_SESSION['id_usuario'];

    $errores = [];

    // Validaciones 
    if (empty($nuevoNombre)) {
        $errores['nombre'] = "El nombre no puede estar vacío.";
    } elseif (strlen($nuevoNombre) < 3) {
        $errores['nombre'] = "El nombre debe tener al menos 3 caracteres.";
    } elseif (strlen($nuevoNombre) > 50) {
        $errores['nombre'] = "El nombre no puede superar los 50 caracteres.";
    }
    // Regex: acepta letras, números, símbolos, puntuación
    elseif (!preg_match('/^[\p{L}\p{N}\p{P}\p{S}\s]+$/u', $nuevoNombre)) {
        $errores['nombre'] = "El nombre contiene caracteres no permitidos.";
    }

    // Si hay errores
    if (!empty($errores)) {
        echo json_encode(["success" => false, "errors" => $errores]);
        exit;
    }

    // VALIDAR SI EL NOMBRE YA ESTA EN USO 
    try {
        $stmt = $conn->prepare(
            "SELECT COUNT(*) AS total 
             FROM USUARIO 
             WHERE username = :nombre 
             AND id_usuario != :id"
        );

        $stmt->execute([
            ":nombre" => $nuevoNombre,
            ":id" => $id
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        
        if ($resultado['total'] > 0) {
            echo json_encode([
                "success" => false,
                "errors" => [
                    "nombre" => "El nombre de usuario ya está en uso, elija otro."
                ]
            ]);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Error al verificar duplicados: " . $e->getMessage()
        ]);
        exit;
    }

    // todo ok actualiza
    try {
        $stmt = $conn->prepare(
            "UPDATE USUARIO 
             SET username = :nombre 
             WHERE id_usuario = :id"
        );

        $stmt->execute([
            ':nombre' => $nuevoNombre,
            ':id' => $id
        ]);

        // Actualiza sesion
        $_SESSION['nombre'] = $nuevoNombre;

        echo json_encode(["success" => true]);

    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Error en la actualización: " . $e->getMessage()
        ]);
    }
}
?>
