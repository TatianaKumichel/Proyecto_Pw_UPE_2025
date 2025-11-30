<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_moderadores');
require_once '../../inc/connection.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
    exit;
}

// Obtener datos
$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

$errores = [];

// Validaciones
if (empty($username)) {
    $errores['username'] = 'El nombre de usuario es obligatorio';
} elseif (strlen($username) < 3) {
    $errores['username'] = 'El nombre debe tener al menos 3 caracteres';
} elseif (strlen($username) > 50) {
    $errores['username'] = 'El nombre puede tener hasta 50 caracteres';
}

if (empty($email)) {
    $errores['email'] = 'El email es obligatorio';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores['email'] = 'El email no es válido';
}

if (empty($password)) {
    $errores['password'] = 'La contraseña es obligatoria';
} elseif (strlen($password) < 8) {
    $errores['password'] = 'La contraseña debe tener al menos 8 caracteres';
}

if (!empty($errores)) {
    echo json_encode(['success' => false, 'errors' => $errores]);
    exit;
}

try {
    // Verificar email
    $stmt = $conn->prepare("SELECT id_usuario FROM USUARIO WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        echo json_encode([
            'success' => false,
            'errors' => ['email' => 'El email ya está registrado']
        ]);
        exit;
    }

    // Verificar username
    $stmt = $conn->prepare("SELECT id_usuario FROM USUARIO WHERE username = :username");
    $stmt->execute([':username' => $username]);
    if ($stmt->fetch()) {
        echo json_encode([
            'success' => false,
            'errors' => ['username' => 'El nombre de usuario ya está en uso']
        ]);
        exit;
    }

    // Hashear contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO USUARIO (username, email, password_hash, estado)
        VALUES (:username, :email, :password_hash, 'activo')
    ");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password_hash' => $password_hash
    ]);

    $id_usuario = $conn->lastInsertId();

    $stmt = $conn->prepare("
        INSERT INTO USUARIO_ROL (id_usuario, id_rol)
        VALUES (:id_usuario, 2)
    ");
    $stmt->execute([':id_usuario' => $id_usuario]);

    echo json_encode([
        'success' => true,
        'message' => 'Moderador creado exitosamente.'
    ]);

} catch (PDOException $e) {
    error_log("Error al crear moderador: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear moderador: ' . $e->getMessage()
    ]);
}
