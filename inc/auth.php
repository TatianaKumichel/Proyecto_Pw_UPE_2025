<?php
/**
<<<<<<< HEAD
 * Incluir para validar que el usuario esté logueado
 */

// Incluir conexión y clases necesarias
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/classes/Permisos.php';
require_once __DIR__ . '/classes/Flash.php';

=======
 * Middleware de autenticación
 * Incluir este archivo en páginas que requieren que el usuario esté logueado
 * Ejemplo: require_once './inc/auth.php';
 */

>>>>>>> 165ef6c (pasaron cosas con git)
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
<<<<<<< HEAD
    Flash::error('Debes iniciar sesión para acceder a esta página.');
=======
>>>>>>> 165ef6c (pasaron cosas con git)
    // Si no está logueado, redirigir al index
    header('Location: ./index.php');
    exit;
}

/**
<<<<<<< HEAD
 * Requiere que el usuario tenga un permiso específico
 * Si no lo tiene, redirige al index
 * @param string $permiso Nombre del permiso requerido
 */
function requierePermiso($permiso)
{
    if (!isset($_SESSION['id_usuario'])) {
        Flash::error('Debes iniciar sesión para acceder a esta página.');
        header('Location: ./index.php');
        exit;
    }

    if (!Permisos::tienePermiso($permiso, $_SESSION['id_usuario'])) {
        Flash::error('No tienes permisos para acceder a esta página.');
=======
 * Verifica si el usuario tiene un rol específico
 * @param string $rol Nombre del rol a verificar
 * @return bool True si el usuario tiene el rol, false en caso contrario
 */
function tieneRol($rol)
{
    return isset($_SESSION['roles']) && in_array($rol, $_SESSION['roles']);
}

/**
 * Verifica si el usuario tiene al menos uno de los roles especificados
 * @param array $roles Array de nombres de roles
 * @return bool True si el usuario tiene al menos uno de los roles
 */
function tieneAlgunRol($roles)
{
    if (!isset($_SESSION['roles'])) {
        return false;
    }

    foreach ($roles as $rol) {
        if (in_array($rol, $_SESSION['roles'])) {
            return true;
        }
    }

    return false;
}

/**
 * Requiere que el usuario tenga un rol específico
 * Si no lo tiene, redirige al index
 * @param string $rol Nombre del rol requerido
 */
function requiereRol($rol)
{
    if (!tieneRol($rol)) {
>>>>>>> 165ef6c (pasaron cosas con git)
        header('Location: ./index.php');
        exit;
    }
}

<<<<<<< HEAD

=======
/**
 * Requiere que el usuario tenga al menos uno de los roles especificados
 * Si no tiene ninguno, redirige al index
 * @param array $roles Array de nombres de roles
 */
function requiereAlgunRol($roles)
{
    if (!tieneAlgunRol($roles)) {
        header('Location: ./index.php');
        exit;
    }
}

/**
 * Verifica si el usuario está restringido
 * @return bool True si el usuario está restringido
 */
function estaRestringido()
{
    return isset($_SESSION['estado']) && $_SESSION['estado'] === 'restringido';
}
?>

// Made with Bob
>>>>>>> 165ef6c (pasaron cosas con git)
