<?php
require_once './inc/connection.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
<<<<<<< HEAD
    $username = trim($data['username'] ?? '');
    $password = $data['password'] ?? '';

    // Validaciones
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Usuario y contraseña son requeridos']);
=======
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    // Validaciones básicas
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email y contraseña son requeridos']);
>>>>>>> 165ef6c (pasaron cosas con git)
        exit;
    }

    try {
<<<<<<< HEAD
        // Obtiene usuario por username
        $stmt = $conn->prepare("SELECT u.id_usuario, u.username, u.email, u.password_hash, u.estado 
                                FROM USUARIO u 
                                WHERE u.username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user && password_verify($password, $user['password_hash'])) {

            // Verificar si el usuario está restringido
            if ($user['estado'] === 'restringido') {
=======
        // Obtener usuario por email
        $stmt = $conn->prepare("SELECT u.id_usuario, u.username, u.email, u.password_hash, u.estado 
                                FROM USUARIO u 
                                WHERE u.email = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el usuario existe y la contraseña es correcta
        if ($usuario && password_verify($password, $usuario['password_hash'])) {

            // Verificar si el usuario está restringido
            if ($usuario['estado'] === 'restringido') {
>>>>>>> 165ef6c (pasaron cosas con git)
                echo json_encode([
                    'success' => false,
                    'message' => 'Tu cuenta está restringida temporalmente. Contacta al administrador.'
                ]);
                exit;
            }

            // Obtener roles del usuario
            $stmt = $conn->prepare("SELECT r.nombre 
                                   FROM ROL r 
                                   INNER JOIN USUARIO_ROL ur ON r.id_rol = ur.id_rol 
                                   WHERE ur.id_usuario = :id_usuario");
<<<<<<< HEAD
            $stmt->execute([':id_usuario' => $user['id_usuario']]);
=======
            $stmt->execute([':id_usuario' => $usuario['id_usuario']]);
>>>>>>> 165ef6c (pasaron cosas con git)
            $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Si no tiene roles asignados, asignar rol de usuario por defecto
            if (empty($roles)) {
                $stmt = $conn->prepare("INSERT INTO USUARIO_ROL (id_usuario, id_rol) VALUES (:id_usuario, 1)");
<<<<<<< HEAD
                $stmt->execute([':id_usuario' => $user['id_usuario']]);
                $roles = ['usuario'];
            }

            // Obtener permisos del usuario
            $stmt = $conn->prepare("SELECT DISTINCT p.nombre
                                   FROM PERMISO p
                                   INNER JOIN ROL_PERMISO rp ON p.id_permiso = rp.id_permiso
                                   INNER JOIN USUARIO_ROL ur ON rp.id_rol = ur.id_rol
                                   WHERE ur.id_usuario = :id_usuario
                                   ORDER BY p.nombre");
            $stmt->execute([':id_usuario' => $user['id_usuario']]);
            $permisos = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Crear sesión
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['estado'] = $user['estado'];
            $_SESSION['roles'] = $roles;
            $_SESSION['permisos'] = $permisos; // Guardar permisos en sesión
            $_SESSION['nombre'] = $user['username']; // Para compatibilidad con código existente
=======
                $stmt->execute([':id_usuario' => $usuario['id_usuario']]);
                $roles = ['usuario'];
            }

            // Crear sesión
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['roles'] = $roles;
            $_SESSION['nombre'] = $usuario['username']; // Para compatibilidad con código existente
>>>>>>> 165ef6c (pasaron cosas con git)

            // Determinar rol principal para compatibilidad
            if (in_array('admin', $roles)) {
                $_SESSION['rol'] = 'admin';
            } elseif (in_array('moderador', $roles)) {
                $_SESSION['rol'] = 'moderador';
            } else {
                $_SESSION['rol'] = 'usuario';
            }

<<<<<<< HEAD
            // Registrar último acceso
            $stmt = $conn->prepare("UPDATE USUARIO SET fecha_registro = NOW() WHERE id_usuario = :id_usuario");
            $stmt->execute([':id_usuario' => $user['id_usuario']]);

            echo json_encode([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'redirect' => './index.php',
                'user' => [
                    'username' => $user['username'],
                    'roles' => $roles,
                    'permisos' => $permisos
                ]
=======
            echo json_encode([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'redirect' => './index.php'
>>>>>>> 165ef6c (pasaron cosas con git)
            ]);

        } else {
            echo json_encode([
                'success' => false,
<<<<<<< HEAD
                'message' => 'Usuario o contraseña incorrectos'
=======
                'message' => 'Email o contraseña incorrectos'
>>>>>>> 165ef6c (pasaron cosas con git)
            ]);
        }

    } catch (PDOException $e) {
<<<<<<< HEAD
        error_log("Error en login: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error en el servidor. Por favor, intenta nuevamente.'
=======
        echo json_encode([
            'success' => false,
            'message' => 'Error en el servidor: ' . $e->getMessage()
>>>>>>> 165ef6c (pasaron cosas con git)
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
<<<<<<< HEAD


=======
?>

// Made with Bob
>>>>>>> 165ef6c (pasaron cosas con git)
