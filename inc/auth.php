<?php
/**
 * Incluir para validar que el usuario esté logueado
 */

// Incluir conexión y clases necesarias
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/classes/Permisos.php';
require_once __DIR__ . '/classes/Flash.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    Flash::error('Debes iniciar sesión para acceder a esta página.');
    header('Location: ./index.php');
    exit;
}

/**
 * Requiere que el usuario tenga un permiso específico
 * @param string $permiso Nombre del permiso requerido
 */
function requierePermiso($permiso)
{
    if (!isset($_SESSION['id_usuario'])) {
        Flash::error('Debes iniciar sesión.');
        header('Location: ./index.php');
        exit;
    }

    // Nota: Permisos::tienePermiso espera ($permiso, $idUsuario)
    if (!Permisos::tienePermiso($permiso, $_SESSION['id_usuario'])) {
        Flash::error('No tienes permisos para acceder a esta página.');
        header('Location: ./index.php');
        exit;
    }
}

/**
 * Requiere que el usuario tenga un rol específico
 * @param int $id_rol ID del rol requerido
 */
function requiereRol($id_rol)
{
    if (!isset($_SESSION['id_usuario'])) {
        Flash::error('Debes iniciar sesión.');
        header('Location: ./index.php');
        exit;
    }

    // Verificar si el usuario tiene el rol en USUARIO_ROL
    global $conn;
    try {
        $query = "SELECT COUNT(*) as tiene
                  FROM USUARIO_ROL
                  WHERE id_usuario = :id_usuario
                  AND id_rol = :id_rol";

        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':id_usuario' => $_SESSION['id_usuario'],
            ':id_rol' => $id_rol
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['tiene'] == 0) {
            Flash::error('No tienes permisos para acceder a esta página.');
            header('Location: ./index.php');
            exit;
        }
    } catch (PDOException $e) {
        error_log("Error verificando rol: " . $e->getMessage());
        Flash::error('Error al verificar permisos.');
        header('Location: ./index.php');
        exit;
    }
}


