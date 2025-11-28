<?php
/**
 * Verifica si el usuario está logueado
 * Uso publico
 */
session_start();
header('Content-Type: application/json');

$logueado = isset($_SESSION['id_usuario']);

echo json_encode([
    'logueado' => $logueado,
    'id_usuario' => $logueado ? $_SESSION['id_usuario'] : null,
    'username' => $logueado ? $_SESSION['username'] : null
]);