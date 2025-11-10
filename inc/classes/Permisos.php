<?php

require_once __DIR__ . '/../connection.php';

/**
 * Control de permisos
 *
 */
class Permisos
{
    /**
     * Verifica si un usuario tiene un permiso
     *
     */
    public static function tienePermiso($permiso, $idUsuario)
    {
        global $conn;

        try {
            $query = "SELECT COUNT(*) as tiene
                      FROM PERMISO p
                      INNER JOIN ROL_PERMISO rp ON p.id_permiso = rp.id_permiso
                      INNER JOIN USUARIO_ROL ur ON rp.id_rol = ur.id_rol
                      WHERE ur.id_usuario = :id_usuario
                      AND p.nombre = :permiso";

            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':id_usuario' => $idUsuario,
                ':permiso' => $permiso
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['tiene'] > 0;

        } catch (PDOException $e) {
            error_log("Error verificando permiso: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un usuario tiene al menos uno de los permisos especificados
     * 
     */
    public static function tieneAlgunPermiso($permisos, $idUsuario)
    {
        if (!is_array($permisos)) {
            $permisos = [$permisos];
        }

        foreach ($permisos as $permiso) {
            if (self::tienePermiso($permiso, $idUsuario)) {
                return true;
            }
        }

        return false;
    }

}


