<?php
/**
 * Obtiene todos los usuarios con sus roles
 * Retorna lista de usuarios indicando si tienen rol de moderador
 */
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_moderadores');
require_once '../../inc/connection.php';
try {
    // Obtener todos los usuarios con información de sus roles
    $stmt = $conn->prepare("
        SELECT 
            u.id_usuario,
            u.username,
            u.email,
            u.estado,
            u.fecha_registro,
            GROUP_CONCAT(r.nombre SEPARATOR ', ') as roles,
            MAX(CASE WHEN r.id_rol = 2 THEN 1 ELSE 0 END) as es_moderador,
            MAX(CASE WHEN r.id_rol = 3 THEN 1 ELSE 0 END) as es_admin
        FROM USUARIO u
        LEFT JOIN USUARIO_ROL ur ON u.id_usuario = ur.id_usuario
        LEFT JOIN ROL r ON ur.id_rol = r.id_rol
        GROUP BY u.id_usuario, u.username, u.email, u.estado, u.fecha_registro
        ORDER BY u.fecha_registro DESC
    ");

    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convertir valores booleanos
    foreach ($usuarios as &$usuario) {
        $usuario['es_moderador'] = (bool) $usuario['es_moderador'];
        $usuario['es_admin'] = (bool) $usuario['es_admin'];
        $usuario['roles'] = $usuario['roles'] ?? 'Sin rol';
    }

    echo json_encode([
        'success' => true,
        'data' => $usuarios,
        'total' => count($usuarios)
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener usuarios: ' . $e->getMessage()
    ]);
}
?>