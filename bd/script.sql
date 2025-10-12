/* -------------------------------------------------------------------------------------------
    TABLAS  
---------------------------------------------------------------------------------------------*/
CREATE TABLE ROL (
  id_rol INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE PERMISO (
  id_permiso INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  descripcion VARCHAR(255)
);

CREATE TABLE ROL_PERMISO (
  id_rol INT NOT NULL,
  id_permiso INT NOT NULL,
  PRIMARY KEY (id_rol, id_permiso),
  FOREIGN KEY (id_rol) REFERENCES ROL(id_rol) ON DELETE CASCADE,
  FOREIGN KEY (id_permiso) REFERENCES PERMISO(id_permiso) ON DELETE CASCADE
);

CREATE TABLE USUARIO (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol_id INT NOT NULL,
  avatar VARCHAR(255) NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('activo','restringido') DEFAULT 'activo',
  FOREIGN KEY (rol_id) REFERENCES ROL(id_rol)
);

CREATE TABLE EMPRESA (
  id_empresa INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  sitio_web VARCHAR(150)
);

CREATE TABLE PLATAFORMA (
  id_plataforma INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE GENERO (
  id_genero INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE JUEGO (
  id_juego INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  descripcion TEXT,
  fecha_lanzamiento DATE,
  id_empresa INT NOT NULL,
  imagen_portada VARCHAR(255),
  publicado BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (id_empresa) REFERENCES EMPRESA(id_empresa)
);

CREATE TABLE JUEGO_GENERO (
  id_juego INT NOT NULL,
  id_genero INT NOT NULL,
  PRIMARY KEY (id_juego, id_genero),
  FOREIGN KEY (id_juego) REFERENCES JUEGO(id_juego) ON DELETE CASCADE,
  FOREIGN KEY (id_genero) REFERENCES GENERO(id_genero) ON DELETE CASCADE
);

CREATE TABLE JUEGO_PLATAFORMA (
  id_juego INT NOT NULL,
  id_plataforma INT NOT NULL,
  PRIMARY KEY (id_juego, id_plataforma),
  FOREIGN KEY (id_juego) REFERENCES JUEGO(id_juego) ON DELETE CASCADE,
  FOREIGN KEY (id_plataforma) REFERENCES PLATAFORMA(id_plataforma) ON DELETE CASCADE
);

CREATE TABLE FAVORITO (
  id_usuario INT NOT NULL,
  id_juego INT NOT NULL,
  fecha_agregado DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_usuario, id_juego),
  FOREIGN KEY (id_usuario) REFERENCES USUARIO(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_juego) REFERENCES JUEGO(id_juego) ON DELETE CASCADE
);

CREATE TABLE CALIFICACION (
  id_usuario INT NOT NULL,
  id_juego INT NOT NULL,
  puntuacion TINYINT NOT NULL CHECK (puntuacion BETWEEN 0 AND 5),
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_usuario, id_juego),
  FOREIGN KEY (id_usuario) REFERENCES USUARIO(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_juego) REFERENCES JUEGO(id_juego) ON DELETE CASCADE
);

CREATE TABLE COMENTARIO (
  id_comentario INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_juego INT NOT NULL,
  contenido TEXT NOT NULL,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('activo','reportado','eliminado') DEFAULT 'activo',
  FOREIGN KEY (id_usuario) REFERENCES USUARIO(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_juego) REFERENCES JUEGO(id_juego) ON DELETE CASCADE
);

CREATE TABLE REPORTE_COMENTARIO (
  id_reporte INT AUTO_INCREMENT PRIMARY KEY,
  id_comentario INT NOT NULL,
  id_usuario_reporta INT NOT NULL,
  motivo VARCHAR(255) NOT NULL,
  fecha_reporte DATETIME DEFAULT CURRENT_TIMESTAMP,
  id_moderador_accion INT NULL,
  fecha_accion DATETIME NULL,
  accion ENUM('ignorar','restringir','eliminar') DEFAULT NULL,
  observaciones VARCHAR(255) DEFAULT NULL,
  FOREIGN KEY (id_comentario) REFERENCES COMENTARIO(id_comentario) ON DELETE CASCADE,
  FOREIGN KEY (id_usuario_reporta) REFERENCES USUARIO(id_usuario),
  FOREIGN KEY (id_moderador_accion) REFERENCES USUARIO(id_usuario)
);

CREATE TABLE FAQ (
  id_faq INT AUTO_INCREMENT PRIMARY KEY,
  pregunta VARCHAR(255) NOT NULL,
  respuesta TEXT,
  visible BOOLEAN DEFAULT TRUE,
  id_autor INT NOT NULL,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_autor) REFERENCES USUARIO(id_usuario)
);

CREATE TABLE JUEGO_IMAGEN (
  id_imagen INT AUTO_INCREMENT PRIMARY KEY,
  id_juego INT NOT NULL,
  url_imagen VARCHAR(255) NOT NULL,
  FOREIGN KEY (id_juego) REFERENCES JUEGO(id_juego) ON DELETE CASCADE
);

-- tabla donde se guarda a quien se debe enviar un correo cuando se publica un juego 
CREATE TABLE ENVIO_NOTIFICACION (
  id_envio INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_juego INT NOT NULL,
  email_destinatario VARCHAR(100) NOT NULL,
  asunto VARCHAR(255) NOT NULL,
  mensaje TEXT NOT NULL,
  fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario) REFERENCES USUARIO(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_juego) REFERENCES JUEGO(id_juego) ON DELETE CASCADE
);

CREATE TABLE RESTRICCION_USUARIO (
  id_restriccion INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
  activo BOOLEAN DEFAULT TRUE,
  FOREIGN KEY (id_usuario) REFERENCES USUARIO(id_usuario) ON DELETE CASCADE
);

/* -------------------------------------------------------------------------------------------
    VISTAS  
---------------------------------------------------------------------------------------------*/

CREATE OR REPLACE VIEW V_GENEROS_TOP2_USUARIO AS
SELECT id_usuario, id_genero, total_favoritos, ranking
FROM (
    SELECT 
        f.id_usuario,
        g.id_genero,
        COUNT(*) AS total_favoritos,
        RANK() OVER (PARTITION BY f.id_usuario ORDER BY COUNT(*) DESC) AS ranking
    FROM FAVORITO f
    JOIN JUEGO_GENERO jg ON f.id_juego = jg.id_juego
    JOIN GENERO g ON jg.id_genero = g.id_genero
    GROUP BY f.id_usuario, g.id_genero
) AS sub
WHERE ranking <= 2;

/* -------------------------------------------------------------------------------------------
    TRIGGERS  
---------------------------------------------------------------------------------------------*/ 
DELIMITER $$

CREATE TRIGGER trg_notificar_publicacion
AFTER UPDATE ON JUEGO
FOR EACH ROW
BEGIN
    DECLARE v_titulo VARCHAR(150);

    -- ejecuta solo si pasa de no publicado a publicado
    IF (NEW.publicado = TRUE AND OLD.publicado = FALSE) THEN
        SET v_titulo = NEW.titulo;

        INSERT INTO ENVIO_NOTIFICACION (id_usuario, id_juego, email_destinatario, asunto, mensaje)
        SELECT DISTINCT 
            u.id_usuario,
            NEW.id_juego,
            u.email,
            CONCAT('Nuevo juego publicado: ', v_titulo),
            CONCAT('Hola ', u.username, ', se ha publicado "', v_titulo, 
                   '", que coincide con tus géneros favoritos.')
        FROM USUARIO u
        JOIN V_GENEROS_TOP2_USUARIO vgt2u ON u.id_usuario = vgt2u.id_usuario
        JOIN JUEGO_GENERO jg ON jg.id_genero = vgt2u.id_genero
        WHERE jg.id_juego = NEW.id_juego;
    END IF;
END $$

DELIMITER ;

/* -------------------------------------------------------------------------------------------
    EVENTOS
---------------------------------------------------------------------------------------------*/

 DELIMITER //

CREATE EVENT IF NOT EXISTS evento_actualizar_restricciones
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    -- Actualiza usuarios que su restriccion ya vencio, mas de 14 días
    UPDATE USUARIO
    SET estado = 'activo'
    WHERE id_usuario IN (
        SELECT id_usuario 
        FROM RESTRICCION_USUARIO 
        WHERE activo = TRUE 
          AND fecha_inicio <= NOW() - INTERVAL 14 DAY
    );
 -- marca las restricciones como inactivas
    UPDATE RESTRICCION_USUARIO
    SET activo = FALSE
    WHERE activo = TRUE 
      AND fecha_inicio <= NOW() - INTERVAL 14 DAY;
END //

DELIMITER ;

/* -------------------------------------------------------------------------------------------
    STORE PROCEDURES 
---------------------------------------------------------------------------------------------*/

DELIMITER $$

-- USUARIO

DROP PROCEDURE IF EXISTS SP_USUARIO_REGISTER$$
CREATE PROCEDURE SP_USUARIO_REGISTER(
    IN p_username VARCHAR(50),
    IN p_email VARCHAR(100),
    IN p_password_hash VARCHAR(255),
    IN p_rol_id INT
)
BEGIN
    INSERT INTO USUARIO(username, email, password_hash, rol_id)
    VALUES(p_username, p_email, p_password_hash, p_rol_id);
END $$

DROP PROCEDURE IF EXISTS SP_USUARIO_LOGIN$$
CREATE PROCEDURE SP_USUARIO_LOGIN(
    IN p_email VARCHAR(100),
    IN p_password_hash VARCHAR(255)
)
BEGIN
    SELECT id_usuario, username, email, rol_id, estado
    FROM USUARIO
    WHERE email = p_email AND password_hash = p_password_hash;
END $$

DROP PROCEDURE IF EXISTS SP_USUARIO_GET_BY_ID$$
CREATE PROCEDURE SP_USUARIO_GET_BY_ID(IN p_id_usuario INT)
BEGIN
    SELECT * FROM USUARIO WHERE id_usuario = p_id_usuario;
END $$

DROP PROCEDURE IF EXISTS SP_USUARIO_UPDATE$$
CREATE PROCEDURE SP_USUARIO_UPDATE(
    IN p_id_usuario INT,
    IN p_username VARCHAR(50),
    IN p_email VARCHAR(100),
    IN p_password_hash VARCHAR(255),
    IN p_avatar VARCHAR(255),
    IN p_estado ENUM('activo','restringido')
)
BEGIN
    UPDATE USUARIO
    SET username = p_username,
        email = p_email,
        password_hash = p_password_hash,
        avatar = p_avatar,
        estado = p_estado
    WHERE id_usuario = p_id_usuario;
END $$

DROP PROCEDURE IF EXISTS SP_USUARIO_DELETE$$
CREATE PROCEDURE SP_USUARIO_DELETE(IN p_id_usuario INT)
BEGIN
    DELETE FROM USUARIO WHERE id_usuario = p_id_usuario;
END $$

-- ROL y PERMISO

DROP PROCEDURE IF EXISTS SP_ROL_GET$$
CREATE PROCEDURE SP_ROL_GET()
BEGIN
    SELECT * FROM ROL;
END $$

DROP PROCEDURE IF EXISTS SP_PERMISO_GET$$
CREATE PROCEDURE SP_PERMISO_GET()
BEGIN
    SELECT * FROM PERMISO;
END $$

DROP PROCEDURE IF EXISTS SP_ROL_PERMISO_GET_BY_ROL$$
CREATE PROCEDURE SP_ROL_PERMISO_GET_BY_ROL(IN p_id_rol INT)
BEGIN
    SELECT rp.id_rol, rp.id_permiso, p.nombre AS permiso_nombre
    FROM ROL_PERMISO rp
    JOIN PERMISO p ON rp.id_permiso = p.id_permiso
    WHERE rp.id_rol = p_id_rol;
END $$

-- EMPRESA

DROP PROCEDURE IF EXISTS SP_EMPRESA_GET$$
CREATE PROCEDURE SP_EMPRESA_GET()
BEGIN
    SELECT * FROM EMPRESA;
END $$

DROP PROCEDURE IF EXISTS SP_EMPRESA_PUT$$
CREATE PROCEDURE SP_EMPRESA_PUT(
    IN p_nombre VARCHAR(100),
    IN p_sitio_web VARCHAR(150)
)
BEGIN
    INSERT INTO EMPRESA(nombre, sitio_web) VALUES(p_nombre, p_sitio_web);
END $$

DROP PROCEDURE IF EXISTS SP_EMPRESA_UPDATE$$
CREATE PROCEDURE SP_EMPRESA_UPDATE(
    IN p_id_empresa INT,
    IN p_nombre VARCHAR(100),
    IN p_sitio_web VARCHAR(150)
)
BEGIN
    UPDATE EMPRESA
    SET nombre = p_nombre,
        sitio_web = p_sitio_web
    WHERE id_empresa = p_id_empresa;
END $$

DROP PROCEDURE IF EXISTS SP_EMPRESA_DELETE$$
CREATE PROCEDURE SP_EMPRESA_DELETE(IN p_id_empresa INT)
BEGIN
    DELETE FROM EMPRESA WHERE id_empresa = p_id_empresa;
END $$

-- PLATAFORMA

DROP PROCEDURE IF EXISTS SP_PLATAFORMA_GET$$
CREATE PROCEDURE SP_PLATAFORMA_GET()
BEGIN
    SELECT * FROM PLATAFORMA;
END $$

DROP PROCEDURE IF EXISTS SP_PLATAFORMA_PUT$$
CREATE PROCEDURE SP_PLATAFORMA_PUT(IN p_nombre VARCHAR(100))
BEGIN
    INSERT INTO PLATAFORMA(nombre) VALUES(p_nombre);
END $$

DROP PROCEDURE IF EXISTS SP_PLATAFORMA_UPDATE$$
CREATE PROCEDURE SP_PLATAFORMA_UPDATE(IN p_id_plataforma INT, IN p_nombre VARCHAR(100))
BEGIN
    UPDATE PLATAFORMA SET nombre = p_nombre WHERE id_plataforma = p_id_plataforma;
END $$

DROP PROCEDURE IF EXISTS SP_PLATAFORMA_DELETE$$
CREATE PROCEDURE SP_PLATAFORMA_DELETE(IN p_id_plataforma INT)
BEGIN
    DELETE FROM PLATAFORMA WHERE id_plataforma = p_id_plataforma;
END $$

-- GENERO

DROP PROCEDURE IF EXISTS SP_GENERO_GET$$
CREATE PROCEDURE SP_GENERO_GET()
BEGIN
    SELECT * FROM GENERO;
END $$

DROP PROCEDURE IF EXISTS SP_GENERO_PUT$$
CREATE PROCEDURE SP_GENERO_PUT(IN p_nombre VARCHAR(100))
BEGIN
    INSERT INTO GENERO(nombre) VALUES(p_nombre);
END $$

DROP PROCEDURE IF EXISTS SP_GENERO_UPDATE$$
CREATE PROCEDURE SP_GENERO_UPDATE(IN p_id_genero INT, IN p_nombre VARCHAR(100))
BEGIN
    UPDATE GENERO SET nombre = p_nombre WHERE id_genero = p_id_genero;
END $$

DROP PROCEDURE IF EXISTS SP_GENERO_DELETE$$
CREATE PROCEDURE SP_GENERO_DELETE(IN p_id_genero INT)
BEGIN
    DELETE FROM GENERO WHERE id_genero = p_id_genero;
END $$

-- JUEGO

DROP PROCEDURE IF EXISTS SP_JUEGO_GET$$
CREATE PROCEDURE SP_JUEGO_GET (
    IN p_titulo VARCHAR(150),
    IN p_id_genero INT,
    IN p_id_plataforma INT,
    IN p_publicado BOOLEAN
)
BEGIN
    SELECT 
        J.id_juego,
        J.titulo,
        J.descripcion,
        J.fecha_lanzamiento,
        J.publicado,
        E.nombre AS empresa,
        GROUP_CONCAT(DISTINCT G.nombre SEPARATOR ', ') AS generos,
        GROUP_CONCAT(DISTINCT P.nombre SEPARATOR ', ') AS plataformas
    FROM JUEGO J
    LEFT JOIN EMPRESA E ON J.id_empresa = E.id_empresa
    LEFT JOIN JUEGO_GENERO JG ON J.id_juego = JG.id_juego
    LEFT JOIN GENERO G ON JG.id_genero = G.id_genero
    LEFT JOIN JUEGO_PLATAFORMA JP ON J.id_juego = JP.id_juego
    LEFT JOIN PLATAFORMA P ON JP.id_plataforma = P.id_plataforma
    WHERE 
        (p_titulo IS NULL OR J.titulo LIKE CONCAT('%', p_titulo, '%'))
        AND (p_id_genero IS NULL OR G.id_genero = p_id_genero)
        AND (p_id_plataforma IS NULL OR P.id_plataforma = p_id_plataforma)
        AND (p_publicado IS NULL OR J.publicado = p_publicado)
    GROUP BY J.id_juego, J.titulo, J.descripcion, J.fecha_lanzamiento, J.publicado, E.nombre
    ORDER BY J.fecha_lanzamiento DESC;
END $$

--

DROP PROCEDURE IF EXISTS SP_JUEGO_GET_BY_ID$$
CREATE PROCEDURE SP_JUEGO_GET_BY_ID(IN p_id_juego INT)
BEGIN
    SELECT * FROM JUEGO WHERE id_juego = p_id_juego;
END $$

--

DROP PROCEDURE IF EXISTS SP_JUEGO_PUT$$
CREATE PROCEDURE SP_JUEGO_PUT(
    IN p_titulo VARCHAR(150),
    IN p_descripcion TEXT,
    IN p_fecha_lanzamiento DATE,
    IN p_id_empresa INT,
    IN p_imagen_portada VARCHAR(255),
    IN p_publicado BOOLEAN
)
BEGIN
    INSERT INTO JUEGO(titulo, descripcion, fecha_lanzamiento, id_empresa, imagen_portada, publicado)
    VALUES(p_titulo, p_descripcion, p_fecha_lanzamiento, p_id_empresa, p_imagen_portada, p_publicado);
END $$

--

DROP PROCEDURE IF EXISTS SP_JUEGO_UPDATE$$
CREATE PROCEDURE SP_JUEGO_UPDATE(
    IN p_id_juego INT,
    IN p_titulo VARCHAR(150),
    IN p_descripcion TEXT,
    IN p_fecha_lanzamiento DATE,
    IN p_id_empresa INT,
    IN p_imagen_portada VARCHAR(255),
    IN p_publicado BOOLEAN
)
BEGIN
    UPDATE JUEGO
    SET titulo = p_titulo,
        descripcion = p_descripcion,
        fecha_lanzamiento = p_fecha_lanzamiento,
        id_empresa = p_id_empresa,
        imagen_portada = p_imagen_portada,
        publicado = p_publicado
    WHERE id_juego = p_id_juego;
END $$

--

DROP PROCEDURE IF EXISTS SP_JUEGO_DELETE$$
CREATE PROCEDURE SP_JUEGO_DELETE(IN p_id_juego INT)
BEGIN
    DELETE FROM JUEGO WHERE id_juego = p_id_juego;
END $$

-- PUBLICAR JUEGO 

DROP PROCEDURE IF EXISTS SP_JUEGO_PUBLICAR$$
CREATE PROCEDURE SP_JUEGO_PUBLICAR(IN p_id_juego INT)
BEGIN
    UPDATE JUEGO
    SET publicado = TRUE
    WHERE id_juego = p_id_juego;
END $$


-- JUEGO_GENERO 

DROP PROCEDURE IF EXISTS SP_JUEGO_GENERO_PUT$$
CREATE PROCEDURE SP_JUEGO_GENERO_PUT(IN p_id_juego INT, IN p_id_genero INT)
BEGIN
    INSERT INTO JUEGO_GENERO(id_juego, id_genero) VALUES(p_id_juego, p_id_genero);
END $$

-- JUEGO_PLATAFORMA

DROP PROCEDURE IF EXISTS SP_JUEGO_PLATAFORMA_PUT$$
CREATE PROCEDURE SP_JUEGO_PLATAFORMA_PUT(IN p_id_juego INT, IN p_id_plataforma INT)
BEGIN
    INSERT INTO JUEGO_PLATAFORMA(id_juego, id_plataforma) VALUES(p_id_juego, p_id_plataforma);
END $$

-- FAVORITO

DROP PROCEDURE IF EXISTS SP_FAVORITO_PUT$$
CREATE PROCEDURE SP_FAVORITO_PUT(IN p_id_usuario INT, IN p_id_juego INT)
BEGIN
    INSERT INTO FAVORITO(id_usuario, id_juego) VALUES(p_id_usuario, p_id_juego);
END $$

DROP PROCEDURE IF EXISTS SP_FAVORITO_DELETE$$
CREATE PROCEDURE SP_FAVORITO_DELETE(IN p_id_usuario INT, IN p_id_juego INT)
BEGIN
    DELETE FROM FAVORITO WHERE id_usuario = p_id_usuario AND id_juego = p_id_juego;
END $$

-- CALIFICACION

DROP PROCEDURE IF EXISTS SP_CALIFICACION_PUT$$
CREATE PROCEDURE SP_CALIFICACION_PUT(
    IN p_id_usuario INT,
    IN p_id_juego INT,
    IN p_puntuacion TINYINT
)
BEGIN
    INSERT INTO CALIFICACION(id_usuario, id_juego, puntuacion)
    VALUES(p_id_usuario, p_id_juego, p_puntuacion);
END $$

DROP PROCEDURE IF EXISTS SP_CALIFICACION_UPDATE$$
CREATE PROCEDURE SP_CALIFICACION_UPDATE(
    IN p_id_usuario INT,
    IN p_id_juego INT,
    IN p_puntuacion TINYINT
)
BEGIN
    UPDATE CALIFICACION
    SET puntuacion = p_puntuacion
    WHERE id_usuario = p_id_usuario AND id_juego = p_id_juego;
END $$

-- COMENTARIO

DROP PROCEDURE IF EXISTS SP_COMENTARIO_PUT$$
CREATE PROCEDURE SP_COMENTARIO_PUT(
    IN p_id_usuario INT,
    IN p_id_juego INT,
    IN p_contenido TEXT
)
BEGIN
    INSERT INTO COMENTARIO(id_usuario, id_juego, contenido)
    VALUES(p_id_usuario, p_id_juego, p_contenido);
END $$

DROP PROCEDURE IF EXISTS SP_COMENTARIO_UPDATE$$
CREATE PROCEDURE SP_COMENTARIO_UPDATE(
    IN p_id_comentario INT,
    IN p_contenido TEXT,
    IN p_estado ENUM('activo','reportado','eliminado')
)
BEGIN
    UPDATE COMENTARIO
    SET contenido = p_contenido,
        estado = p_estado
    WHERE id_comentario = p_id_comentario;
END $$

DROP PROCEDURE IF EXISTS SP_COMENTARIO_DELETE$$
CREATE PROCEDURE SP_COMENTARIO_DELETE(IN p_id_comentario INT)
BEGIN
    DELETE FROM COMENTARIO WHERE id_comentario = p_id_comentario;
END $$

-- REPORTE_COMENTARIO

DROP PROCEDURE IF EXISTS SP_REPORTE_COMENTARIO_PUT$$
CREATE PROCEDURE SP_REPORTE_COMENTARIO_PUT(
    IN p_id_comentario INT,
    IN p_id_usuario_reporta INT,
    IN p_motivo VARCHAR(255)
)
BEGIN
    INSERT INTO REPORTE_COMENTARIO(id_comentario, id_usuario_reporta, motivo)
    VALUES(p_id_comentario, p_id_usuario_reporta, p_motivo);
END $$

DROP PROCEDURE IF EXISTS SP_REPORTE_COMENTARIO_UPDATE$$
CREATE PROCEDURE SP_REPORTE_COMENTARIO_UPDATE(
    IN p_id_reporte INT,
    IN p_id_moderador_accion INT,
    IN p_accion ENUM('ignorar','restringir','eliminar'),
    IN p_observaciones VARCHAR(255)
)
BEGIN
    UPDATE REPORTE_COMENTARIO
    SET id_moderador_accion = p_id_moderador_accion,
        accion = p_accion,
        observaciones = p_observaciones,
        fecha_accion = NOW()
    WHERE id_reporte = p_id_reporte;
END $$

DROP PROCEDURE IF EXISTS SP_REPORTE_COMENTARIO_DELETE$$
CREATE PROCEDURE SP_REPORTE_COMENTARIO_DELETE(IN p_id_reporte INT)
BEGIN
    DELETE FROM REPORTE_COMENTARIO WHERE id_reporte = p_id_reporte;
END $$

-- FAQ

DROP PROCEDURE IF EXISTS SP_FAQ_GET$$
CREATE PROCEDURE SP_FAQ_GET(
    IN p_visible BOOLEAN
)
BEGIN
    SELECT 
        id_faq,
        pregunta,
        respuesta,
        visible,
        id_autor,
        fecha_creacion
    FROM FAQ
    WHERE p_visible IS NULL 
          OR visible = p_visible
    ORDER BY fecha_creacion DESC;
END $$

DROP PROCEDURE IF EXISTS SP_FAQ_PUT$$
CREATE PROCEDURE SP_FAQ_PUT(
    IN p_pregunta VARCHAR(255),
    IN p_respuesta TEXT,
    IN p_id_autor INT,
    IN p_visible BOOLEAN
)
BEGIN
    INSERT INTO FAQ(pregunta, respuesta, id_autor, visible)
    VALUES(p_pregunta, p_respuesta, p_id_autor, p_visible);
END $$

DROP PROCEDURE IF EXISTS SP_FAQ_UPDATE$$
CREATE PROCEDURE SP_FAQ_UPDATE(
    IN p_id_faq INT,
    IN p_pregunta VARCHAR(255),
    IN p_respuesta TEXT,
    IN p_visible BOOLEAN
)
BEGIN
    UPDATE FAQ
    SET pregunta = p_pregunta,
        respuesta = p_respuesta,
        visible = p_visible
    WHERE id_faq = p_id_faq;
END $$

DROP PROCEDURE IF EXISTS SP_FAQ_DELETE$$
CREATE PROCEDURE SP_FAQ_DELETE(IN p_id_faq INT)
BEGIN
    DELETE FROM FAQ WHERE id_faq = p_id_faq;
END $$


-- JUEGO_IMAGEN

DROP PROCEDURE IF EXISTS SP_JUEGO_IMAGEN_PUT$$
CREATE PROCEDURE SP_JUEGO_IMAGEN_PUT(IN p_id_juego INT, IN p_url_imagen VARCHAR(255))
BEGIN
    INSERT INTO JUEGO_IMAGEN(id_juego, url_imagen)
    VALUES(p_id_juego, p_url_imagen);
END $$

DROP PROCEDURE IF EXISTS SP_JUEGO_IMAGEN_DELETE$$
CREATE PROCEDURE SP_JUEGO_IMAGEN_DELETE(IN p_id_imagen INT)
BEGIN
    DELETE FROM JUEGO_IMAGEN WHERE id_imagen = p_id_imagen;
END $$


-- ENVIO_NOTIFICACION

DROP PROCEDURE IF EXISTS SP_OBTENER_NOTIFICACIONES_PENDIENTES$$
CREATE PROCEDURE SP_OBTENER_NOTIFICACIONES_PENDIENTES()
BEGIN
    SELECT * FROM ENVIO_NOTIFICACION
    WHERE fecha_envio IS NULL OR fecha_envio = '0000-00-00 00:00:00';
END $$

DROP PROCEDURE IF EXISTS SP_MARCAR_NOTIFICACION_ENVIADA$$
CREATE PROCEDURE SP_MARCAR_NOTIFICACION_ENVIADA(IN p_id_envio INT)
BEGIN
    UPDATE ENVIO_NOTIFICACION
    SET fecha_envio = NOW()
    WHERE id_envio = p_id_envio;
END $$


-- RESTRICCION_USUARIO

DROP PROCEDURE IF EXISTS SP_RESTRICCION_USUARIO_PUT$$
CREATE PROCEDURE SP_RESTRICCION_USUARIO_PUT(IN p_id_usuario INT)
BEGIN
    INSERT INTO RESTRICCION_USUARIO(id_usuario) VALUES(p_id_usuario);
END $$

DROP PROCEDURE IF EXISTS SP_RESTRICCION_USUARIO_UPDATE$$
CREATE PROCEDURE SP_RESTRICCION_USUARIO_UPDATE(
    IN p_id_restriccion INT,
    IN p_activo BOOLEAN
)
BEGIN
    UPDATE RESTRICCION_USUARIO
    SET activo = p_activo
    WHERE id_restriccion = p_id_restriccion;
END $$

DROP PROCEDURE IF EXISTS SP_RESTRICCION_USUARIO_DELETE$$
CREATE PROCEDURE SP_RESTRICCION_USUARIO_DELETE(IN p_id_restriccion INT)
BEGIN
    DELETE FROM RESTRICCION_USUARIO WHERE id_restriccion = p_id_restriccion;
END $$


DELIMITER ;

