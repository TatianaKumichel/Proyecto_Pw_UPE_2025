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
    // Si no está logueado, redirigir al index
    header('Location: ./index.php');
    exit;
}

/**
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
        header('Location: ./index.php');
        exit;
    }
}


