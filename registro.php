<?php
require_once './inc/connection.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = trim($data['username'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    $errores = [];

    // Validaciones
    if (empty($username)) {
        $errores['username'] = 'El nombre de usuario es requerido';
    } elseif (strlen($username) < 3) {
        $errores['username'] = 'El nombre debe tener al menos 3 caracteres';
    } elseif (strlen($username) > 50) {
        $errores['username'] = 'El nombre no puede exceder 50 caracteres';
    }

    if (empty($email)) {
        $errores['email'] = 'El email es requerido';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'El email no es válido';
    }

    if (empty($password)) {
        $errores['password'] = 'La contraseña es requerida';
    } elseif (strlen($password) < 8) {
        $errores['password'] = 'La contraseña debe tener al menos 8 caracteres';
    }

    // Si hay errores de validación, retornar
    if (!empty($errores)) {
        echo json_encode(['success' => false, 'errors' => $errores]);
        exit;
    }

    try {
        // Verificar si el email ya existe
        $stmt = $conn->prepare("SELECT id_usuario FROM USUARIO WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetch()) {
            echo json_encode([
                'success' => false,
                'message' => 'El email ya está registrado'
            ]);
            exit;
        }

        // Verificar si el username ya existe
        $stmt = $conn->prepare("SELECT id_usuario FROM USUARIO WHERE username = :username");
        $stmt->execute([':username' => $username]);

        if ($stmt->fetch()) {
            echo json_encode([
                'success' => false,
                'message' => 'El nombre de usuario ya está en uso'
            ]);
            exit;
        }

        // Hashear la contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario
        $stmt = $conn->prepare("INSERT INTO USUARIO (username, email, password_hash, estado) 
                               VALUES (:username, :email, :password_hash, 'activo')");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password_hash' => $password_hash
        ]);

        $id_usuario = $conn->lastInsertId();

        // Asignar rol de usuario por defecto (id_rol = 1)
        $stmt = $conn->prepare("INSERT INTO USUARIO_ROL (id_usuario, id_rol) 
                               VALUES (:id_usuario, 1)");
        $stmt->execute([':id_usuario' => $id_usuario]);

        echo json_encode([
            'success' => true,
            'message' => 'Usuario registrado exitosamente. Ya puedes iniciar sesión.'
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error en el servidor: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
<<<<<<< HEAD
}
=======
}
?>

// Made with Bob
>>>>>>> 165ef6c (pasaron cosas con git)
