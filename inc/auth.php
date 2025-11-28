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

// Definir URL base para redirecciones (ajustar según tu entorno)
define('BASE_URL', '/tp/Proyecto_Pw_UPE_2025');

/**
 * Requiere que el usuario esté logueado (para páginas HTML)
 */
function requiereLogin()
{
    if (!isset($_SESSION['id_usuario'])) {
        Flash::error('Debes iniciar sesión para acceder a esta página.');
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

// Cada página debe llamar a requiereLogin() o requierePermiso() explícitamente.

/**
 * Requiere que el usuario tenga un permiso específico
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
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

/**
 * Requiere que el usuario tenga un rol específico
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
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    } catch (PDOException $e) {
        error_log("Error verificando rol: " . $e->getMessage());
        Flash::error('Error al verificar permisos.');
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

/**
 * Requiere que el usuario esté logueado en contexto API
 * Retorna JSON error si no está logueado
 */
function requiereLoginAPI()
{
    // Iniciar sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json');
    
    if (!isset($_SESSION['id_usuario'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Debes iniciar sesión para acceder']);
        exit;
    }
}

/**
 * Requiere permiso específico en contexto API
 * Retorna JSON error si no tiene permiso
 */
function requierePermisoAPI($permiso)
{
    // Iniciar sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json');
    
    if (!isset($_SESSION['id_usuario'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Debes iniciar sesión']);
        exit;
    }
    
    if (!Permisos::tienePermiso($permiso, $_SESSION['id_usuario'])) {
        http_response_code(403);
        echo json_encode(['error' => 'No tienes permisos para esta operación']);
        exit;
    }
}

/**
 * Requiere rol específico en contexto API
 * Retorna JSON error si no tiene el rol
 */
function requiereRolAPI($id_rol)
{
    // Iniciar sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json');
    
    if (!isset($_SESSION['id_usuario'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Debes iniciar sesión']);
        exit;
    }
    
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
            http_response_code(403);
            echo json_encode(['error' => 'No tienes permisos para esta operación']);
            exit;
        }
    } catch (PDOException $e) {
        error_log("Error verificando rol: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Error al verificar permisos']);
        exit;
    }
}

