<?php
require_once './connection.php';
header('Content-Type: application/json');

// Leer input JSON
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

try {
    if ($action === 'verificar_email') {
        $email = trim($data['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Formato de email inválido.']);
            exit;
        }

        $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'El email no está registrado.']);
        }
        exit;

    } elseif ($action === 'actualizar_pass') {
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $passwordConfirm = $data['passwordConfirm'] ?? '';

        if (strlen($password) < 6) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres.']);
            exit;
        }

        if ($password !== $passwordConfirm) {
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden.']);
            exit;
        }

        // Verificar que el email siga existiendo (seguridad básica)
        $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Email inválido.']);
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuario SET password_hash = ? WHERE email = ?");
        $stmt->execute([$hash, $email]);

        echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
        exit;

    } else {
        echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
    }

} catch (PDOException $e) {
    error_log("Error en recuperar_ajax.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error en el servidor.']);
}
