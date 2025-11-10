/* -------------------------------------------------------------------------------------------
    MODIFICACIONES PARA SISTEMA DE ROLES MÚLTIPLES
    - Crear tabla USUARIO_ROL (relación muchos a muchos)
    - Eliminar campo rol_id de tabla USUARIO
---------------------------------------------------------------------------------------------*/

USE upegaming;

-- 1. Crear tabla USUARIO_ROL
CREATE TABLE IF NOT EXISTS USUARIO_ROL (
  id_usuario INT NOT NULL,
  id_rol INT NOT NULL,
  fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_usuario, id_rol),
  FOREIGN KEY (id_usuario) REFERENCES USUARIO(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_rol) REFERENCES ROL(id_rol) ON DELETE CASCADE
);

-- 2. Migrar datos existentes de USUARIO a USUARIO_ROL (si existen usuarios)
INSERT INTO USUARIO_ROL (id_usuario, id_rol)
SELECT id_usuario, rol_id 
FROM USUARIO 
WHERE rol_id IS NOT NULL
ON DUPLICATE KEY UPDATE id_usuario = id_usuario;

-- 3. Eliminar la foreign key de rol_id en USUARIO
-- Nota: El nombre de la constraint puede variar, ajustar si es necesario
ALTER TABLE USUARIO DROP FOREIGN KEY USUARIO_ibfk_1;

-- 4. Eliminar la columna rol_id de USUARIO
ALTER TABLE USUARIO DROP COLUMN rol_id;

-- 5. Insertar roles adicionales si no existen
INSERT IGNORE INTO ROL (id_rol, nombre) VALUES
(2, 'moderador'),
(3, 'admin');

-- 6. Insertar todos los permisos del sistema
INSERT IGNORE INTO PERMISO (id_permiso, nombre, descripcion) VALUES
-- Permisos básicos de usuario
(3, 'calificar_juego', 'Puede calificar juegos'),
(4, 'comentar', 'Puede comentar en juegos'),
(5, 'gestionar_comentarios_propios', 'Puede editar/eliminar sus propios comentarios'),
(6, 'reportar_comentarios', 'Puede reportar comentarios inapropiados'),
-- Permisos de moderador
(7, 'moderar_comentarios', 'Puede moderar comentarios reportados'),
(8, 'gestionar_faq', 'Puede crear y editar FAQs'),
-- Permisos de administrador
(9, 'gestionar_juegos', 'Puede crear, editar y eliminar juegos'),
(10, 'gestionar_empresas', 'Puede gestionar empresas'),
(11, 'gestionar_plataformas', 'Puede gestionar plataformas'),
(12, 'gestionar_generos', 'Puede gestionar géneros'),
(13, 'gestionar_moderadores', 'Puede asignar y remover moderadores');

-- 7. Asignar permisos al rol USUARIO (id_rol = 1)
INSERT IGNORE INTO ROL_PERMISO (id_rol, id_permiso) VALUES
(1, 1),  -- ver_juegos
(1, 2),  -- marcar_favorito
(1, 3),  -- calificar_juego
(1, 4),  -- comentar
(1, 5),  -- gestionar_comentarios_propios
(1, 6);  -- reportar_comentarios

-- 8. Asignar permisos al rol MODERADOR (id_rol = 2)
INSERT IGNORE INTO ROL_PERMISO (id_rol, id_permiso) VALUES
(2, 1),  -- ver_juegos
(2, 2),  -- marcar_favorito
(2, 3),  -- calificar_juego
(2, 4),  -- comentar
(2, 5),  -- gestionar_comentarios_propios
(2, 6),  -- reportar_comentarios
(2, 7),  -- moderar_comentarios
(2, 8);  -- gestionar_faq

-- 9. Asignar permisos al rol ADMIN (id_rol = 3) - Todos los permisos
INSERT IGNORE INTO ROL_PERMISO (id_rol, id_permiso) VALUES
(3, 1), (3, 2), (3, 3), (3, 4), (3, 5), (3, 6), (3, 7), (3, 8),
(3, 9), (3, 10), (3, 11), (3, 12), (3, 13);

-- 10. Verificar cambios
SELECT 'Tabla USUARIO_ROL creada y datos migrados' AS status;
SELECT * FROM USUARIO_ROL;

-- 11. Mostrar resumen de permisos por rol
SELECT
    r.nombre AS rol,
    GROUP_CONCAT(p.nombre SEPARATOR ', ') AS permisos
FROM ROL r
LEFT JOIN ROL_PERMISO rp ON r.id_rol = rp.id_rol
LEFT JOIN PERMISO p ON rp.id_permiso = p.id_permiso
GROUP BY r.id_rol, r.nombre
ORDER BY r.id_rol;
