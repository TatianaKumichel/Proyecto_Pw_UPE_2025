-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-11-2025 a las 19:11:47
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `upegaming`
--
CREATE DATABASE IF NOT EXISTS `upegaming` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `upegaming`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificacion`
--

CREATE TABLE `calificacion` (
  `id_usuario` int(11) NOT NULL,
  `id_juego` int(11) NOT NULL,
  `puntuacion` tinyint(4) NOT NULL CHECK (`puntuacion` between 0 and 5),
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `calificacion`
--

INSERT INTO `calificacion` (`id_usuario`, `id_juego`, `puntuacion`, `fecha`) VALUES
(7, 1, 1, '2025-11-06 00:55:49'),
(7, 2, 2, '2025-11-05 23:39:48'),
(7, 3, 2, '2025-11-05 23:09:23'),
(7, 4, 3, '2025-11-07 15:37:29'),
(8, 1, 2, '2025-11-21 14:55:55'),
(8, 2, 4, '2025-11-13 11:38:06'),
(8, 10, 4, '2025-11-13 12:07:11'),
(9, 1, 5, '2025-11-11 23:42:10'),
(10, 1, 3, '2025-11-11 23:42:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE `comentario` (
  `id_comentario` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_juego` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `estado` enum('activo','reportado','eliminado') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `comentario`
--

INSERT INTO `comentario` (`id_comentario`, `id_usuario`, `id_juego`, `contenido`, `fecha`, `estado`) VALUES
(1, 7, 1, 'el primer comentario.', '2025-11-06 00:36:52', 'reportado'),
(2, 7, 1, 'otro', '2025-11-06 00:37:21', 'eliminado'),
(3, 7, 3, 'fua', '2025-11-06 00:52:27', 'activo'),
(4, 8, 2, 'aa', '2025-11-10 16:02:25', 'eliminado'),
(5, 8, 2, 'aa', '2025-11-10 18:15:11', 'eliminado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id_empresa` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `sitio_web` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id_empresa`, `nombre`, `sitio_web`) VALUES
(1, 'Respawn Entertainments', 'https://www.respawn.com/'),
(2, 'Relic Entertainment', 'https://www.relic.com/'),
(3, 'Ubisoft', 'https://www.ubisoft.com/'),
(4, 'Activision', 'https://www.activision.com/'),
(5, 'Valve', 'https://www.valvesoftware.com/'),
(6, 'Blizzard Entertainment', 'https://blizzard.com/'),
(7, 'Rockstar Games', 'https://www.rockstargames.com/'),
(8, 'Visual Concepts / 2K', 'https://www.2k.com/'),
(9, 'Recreate Games', 'https://recreate.games/'),
(10, 'Capcom', 'https://www.capcom.com/');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envio_notificacion`
--

CREATE TABLE `envio_notificacion` (
  `id_envio` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_juego` int(11) NOT NULL,
  `email_destinatario` varchar(100) NOT NULL,
  `asunto` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faq`
--

CREATE TABLE `faq` (
  `id_faq` int(11) NOT NULL,
  `pregunta` varchar(255) NOT NULL,
  `respuesta` text DEFAULT NULL,
  `visible` tinyint(1) DEFAULT 1,
  `id_autor` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `faq`
--

INSERT INTO `faq` (`pregunta`, `respuesta`, `visible`, `id_autor`, `fecha_creacion`) VALUES
('¿Cómo puedo crear una cuenta en la plataforma?', 'Para crear una cuenta, hacé clic en “Registrarse”, y completá tus datos.', 1, 1, NOW()),
('¿Por qué no puedo iniciar sesión?', 'Revisá que tu correo y contraseña sean correctos.', 1, 1, NOW()),
('¿Cómo puedo reportar un comentario ?', 'Desde la página del juego, hacé clic en “Reportar” y completá el motivo y envia el reporte, un moderador se encargara de revisarlo.', 1, 1, NOW()),
('¿Qué significan los juegos “destacados”?', 'Los juegos destacados  son los juegos con mayor popularidad y mejor calificados por los usuarios', 1, 1, NOW()),
('¿Cómo agrego un juego a mis favoritos?', 'Para agregar un juego a tus favoritos, hacé clic en marcar como favorito en la página del juego.', 1, 1, NOW());
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favorito`
--

CREATE TABLE `favorito` (
  `id_usuario` int(11) NOT NULL,
  `id_juego` int(11) NOT NULL,
  `fecha_agregado` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `favorito`
--

INSERT INTO `favorito` (`id_usuario`, `id_juego`, `fecha_agregado`) VALUES
(1, 1, '2025-11-03 23:44:38'),
(1, 2, '2025-11-03 23:44:38'),
(1, 3, '2025-11-03 23:44:38'),
(1, 4, '2025-11-03 23:44:38'),
(1, 6, '2025-11-03 23:44:38'),
(1, 7, '2025-11-03 23:44:38'),
(1, 8, '2025-11-03 23:44:38'),
(1, 9, '2025-11-03 23:44:38'),
(1, 10, '2025-11-03 23:44:38'),
(1, 11, '2025-11-03 23:44:38'),
(7, 1, '2025-11-06 00:55:51'),
(7, 3, '2025-11-05 23:17:56'),
(7, 4, '2025-11-05 23:20:11'),
(7, 6, '2025-11-05 23:47:23'),
(7, 8, '2025-11-05 23:47:28'),
(7, 9, '2025-11-05 23:47:32'),
(7, 10, '2025-11-05 23:20:25'),
(8, 1, '2025-11-13 00:11:13'),
(8, 2, '2025-11-10 18:15:09'),
(10, 1, '2025-11-07 18:45:42'),
(10, 2, '2025-11-07 18:45:13'),
(10, 3, '2025-11-07 18:45:45'),
(10, 6, '2025-11-07 18:45:38'),
(10, 10, '2025-11-07 18:45:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id_genero` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id_genero`, `nombre`) VALUES
(1, 'Acción'),
(2, 'Aventura'),
(6, 'Deportes'),
(5, 'Estrategia'),
(7, 'Party'),
(9, 'Puzzle'),
(3, 'RPG'),
(4, 'Shooter');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juego`
--

CREATE TABLE `juego` (
  `id_juego` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_lanzamiento` date DEFAULT NULL,
  `id_empresa` int(11) NOT NULL,
  `imagen_portada` varchar(255) DEFAULT NULL,
  `publicado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `juego`
--

INSERT INTO `juego` (`id_juego`, `titulo`, `descripcion`, `fecha_lanzamiento`, `id_empresa`, `imagen_portada`, `publicado`) VALUES
(1, 'Apex Legends', 'Apex Legends es el galardonado juego gratuito de acción en primera persona...','2019-02-04', 1, 'img/hapex.jpg', 1),
(2, 'Age of Empires IV', 'La galardonada franquicia de estrategia continúa...', '2021-10-28', 2, 'img/hage.jpg', 1),
(3, 'Assassin\s Creed Origins', 'Explora el antiguo Egipto en este juego de acción...',  '2017-10-27', 3, 'img/hassasin.jpg', 1),
(4, 'Call of Duty: Black Ops 7', 'La entrega más alucinante de Black Ops hasta la fecha...', '2010-11-09' , 4, 'img/hcall.jpg', 1),
(5, 'Counter Strike 2', 'El próximo capítulo en la historia de CS está a punto de comenzar.', '2012-08-21', 5, 'img/hcs.jpg', 1),
(6, 'Diablo IV', 'Únete a la lucha por Santuario en Diablo IV...',  '2012-05-15' , 6, 'img/hdiablo.jpg', 1),
(7, 'Grand Theft Auto V', 'Disfruta de los superventas del entretenimiento GTA V...', '2013-09-17', 7, 'img/hgta.jpg', 1),
(8, 'NBA 2K26', 'Exhibe tu colección de movimientos con hiperrealismo...', '2026-09-05' , 8, 'img/hnba.jpg', 1),
(9, 'Party Animals', 'Pelea contra tus amigos como perritos y gatitos...', '2026-09-20', 9, 'img/hparty.jpg', 1),
(10, 'Resident Evil 4', 'Sobrevivir es solo el principio...', '2026-03-24', 10, 'img/hresident.jpg', 1),
(11, 'Street Fighter 6', 'Street Fighter 6 trae una nueva evolución...', '2026-02-17', 10, 'img/hsf.jpg', 1);

--
-- Disparadores `juego`
--
DELIMITER $$
CREATE TRIGGER `trg_notificar_publicacion` AFTER UPDATE ON `juego` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juego_genero`
--

CREATE TABLE `juego_genero` (
  `id_juego` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `juego_genero`
--

INSERT INTO `juego_genero` (`id_juego`, `id_genero`) VALUES
(1, 1),
(1, 4),
(2, 5),
(3, 1),
(3, 2),
(4, 1),
(4, 4),
(5, 4),
(6, 1),
(6, 3),
(7, 1),
(7, 2),
(8, 6),
(9, 1),
(9, 7),
(10, 1),
(10, 2),
(11, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juego_imagen`
--

CREATE TABLE `juego_imagen` (
  `id_imagen` int(11) NOT NULL,
  `id_juego` int(11) NOT NULL,
  `url_imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `juego_imagen`
--

INSERT INTO `juego_imagen` (`id_imagen`, `id_juego`, `url_imagen`) VALUES
(1, 1, 'img/hapex.jpg'),
(2, 1, 'img/hapex2.jpg'),
(3, 2, 'img/hage.jpg'),
(4, 2, 'img/hage2.jpg'),
(5, 3, 'img/hassasin.jpg'),
(6, 3, 'img/hassasin2.jpg'),
(7, 4, 'img/hcall.jpg'),
(8, 4, 'img/hcall2.jpg'),
(9, 5, 'img/hcs.jpg'),
(10, 5, 'img/hcs2.jpg'),
(11, 6, 'img/hdiablo.jpg'),
(12, 6, 'img/hdiablo2.jpg'),
(13, 7, 'img/hgta.jpg'),
(14, 7, 'img/hgta2.jpg'),
(15, 8, 'img/hnba.jpg'),
(16, 8, 'img/hnba2.jpg'),
(17, 9, 'img/hparty.jpg'),
(18, 9, 'img/hparty2.jpg'),
(19, 10, 'img/hresident.jpg'),
(20, 10, 'img/hresident2.jpg'),
(21, 11, 'img/hsf.jpg'),
(22, 11, 'img/hsf2.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juego_plataforma`
--

CREATE TABLE `juego_plataforma` (
  `id_juego` int(11) NOT NULL,
  `id_plataforma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `juego_plataforma`
--

INSERT INTO `juego_plataforma` (`id_juego`, `id_plataforma`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(3, 1),
(3, 2),
(3, 3),
(4, 1),
(4, 2),
(4, 3),
(5, 1),
(6, 1),
(6, 2),
(6, 3),
(7, 1),
(7, 2),
(7, 3),
(8, 1),
(8, 2),
(8, 3),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(10, 3),
(11, 1),
(11, 2),
(11, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id_permiso`, `nombre`, `descripcion`) VALUES
(1, 'ver_juegos', 'Puede visualizar juegos'),
(2, 'marcar_favorito', 'Puede agregar juegos a favoritos'),
(3, 'calificar_juego', 'Puede calificar juegos'),
(4, 'comentar', 'Puede comentar en juegos'),
(5, 'gestionar_comentarios_propios', 'Puede editar/eliminar sus propios comentarios'),
(6, 'reportar_comentarios', 'Puede reportar comentarios inapropiados'),
(7, 'moderar_comentarios', 'Puede moderar comentarios reportados'),
(8, 'gestionar_faq', 'Puede crear y editar FAQs'),
(9, 'gestionar_juegos', 'Puede crear, editar y eliminar juegos'),
(10, 'gestionar_empresas', 'Puede gestionar empresas'),
(11, 'gestionar_plataformas', 'Puede gestionar plataformas'),
(12, 'gestionar_generos', 'Puede gestionar géneros'),
(13, 'gestionar_moderadores', 'Puede asignar y remover moderadores');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plataforma`
--

CREATE TABLE `plataforma` (
  `id_plataforma` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `plataforma`
--

INSERT INTO `plataforma` (`id_plataforma`, `nombre`) VALUES
(1, 'PC'),
(2, 'PlayStation 5'),
(3, 'Xbox Series X');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_comentario`
--

CREATE TABLE `reporte_comentario` (
  `id_reporte` int(11) NOT NULL,
  `id_comentario` int(11) NOT NULL,
  `id_usuario_reporta` int(11) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `fecha_reporte` datetime DEFAULT current_timestamp(),
  `id_moderador_accion` int(11) DEFAULT NULL,
  `fecha_accion` datetime DEFAULT NULL,
  `accion` enum('ignorar','restringir','eliminar') DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reporte_comentario`
--

INSERT INTO `reporte_comentario` (`id_reporte`, `id_comentario`, `id_usuario_reporta`, `motivo`, `fecha_reporte`, `id_moderador_accion`, `fecha_accion`, `accion`, `observaciones`) VALUES
(1, 1, 10, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabcd', '2025-11-07 19:13:19', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restriccion_usuario`
--

CREATE TABLE `restriccion_usuario` (
  `id_restriccion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_inicio` datetime DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`) VALUES
(3, 'admin'),
(2, 'moderador'),
(1, 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol_permiso`
--

INSERT INTO `rol_permiso` (`id_rol`, `id_permiso`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 7),
(3, 8),
(3, 9),
(3, 10),
(3, 11),
(3, 12),
(3, 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `estado` enum('activo','restringido') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `username`, `email`, `password_hash`, `avatar`, `fecha_registro`, `estado`) VALUES
(1, 'sergio', 'sergio@upe.com', '$2y$10$vYYc0e9wQv6Egdt7ywlnSu092t9AVI516MaBtSVwR4RjR4mwKH9mq', NULL, '2025-11-03 23:44:38', 'activo'),
(2, 'emma', 'emma@upe.com', '$2y$10$R8p34EPPgI.35gOGTyvNre8NyoPSqaOWw9LC5./40cYaUBlI6lXwm', NULL, '2025-11-03 23:44:38', 'activo'),
(3, 'cami', 'cami@example.com', '$2y$10$hexbiMc3cDdmgfxcxrmv0O/RiNXQRPJbG1hgjhxWQ8Rtmgg4tRIbq', NULL, '2025-11-03 23:44:38', 'activo'),
(4, 'tati', 'tati@example.com', '$2y$10$jY5DXSR394MVvC1T2aYExO0sRvoZ27W9lz3o1HoNJuIckXuGbK1jm', NULL, '2025-11-03 23:44:38', 'activo'),
(5, 'test', 'test@upe.com', '$2y$10$9.ldRFwMMmEb5/3g4RMCoeVbnJwu6/n2BzP4IWOV1NnK9x9PSzRY.', NULL, '2025-11-03 23:57:18', 'activo'),
(6, 'test2', 'test2@upe.com', '$2y$10$8FzEGe0TFrgaNGGhj.47y.b82k.8iK833sThKNSykM3VUCKRcuT4e', NULL, '2025-11-04 00:23:41', 'activo'),
(7, 'user1', 'user1@upe.com', '$2y$10$/Y0J3MJ.3wN/G3EX2Emwse2d/P48UPbFgUBkyrl7KY62gRDDcLXoa', NULL, '2025-11-07 17:19:04', 'activo'),
(8, 'admin_test', 'admin@upegaming.com', '$2y$10$LnYEYNff14XomMaFtyrare/k1Bzjl4fEIA35yYnd/gMMlS0xwOKbS', NULL, '2025-11-21 14:54:03', 'activo'),
(9, 'moderador_test', 'moderador@upegaming.com', '$2y$10$LnYEYNff14XomMaFtyrare/k1Bzjl4fEIA35yYnd/gMMlS0xwOKbS', NULL, '2025-11-11 23:42:03', 'activo'),
(10, 'usuario_test', 'usuario@upegaming.com', '$2y$10$LnYEYNff14XomMaFtyrare/k1Bzjl4fEIA35yYnd/gMMlS0xwOKbS', NULL, '2025-11-11 23:42:44', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `fecha_asignacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_rol`
--

INSERT INTO `usuario_rol` (`id_usuario`, `id_rol`, `fecha_asignacion`) VALUES
(1, 1, '2025-11-03 23:44:38'),
(2, 2, '2025-11-03 23:44:38'),
(3, 3, '2025-11-03 23:44:38'),
(4, 1, '2025-11-03 23:44:38'),
(5, 1, '2025-11-03 23:57:18'),
(6, 1, '2025-11-04 00:23:41'),
(7, 1, '2025-11-05 16:41:48'),
(8, 3, '2025-11-07 17:13:22'),
(9, 2, '2025-11-07 17:13:22'),
(10, 1, '2025-11-07 17:13:22');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_generos_top2_usuario`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_generos_top2_usuario` (
`id_usuario` int(11)
,`id_genero` int(11)
,`total_favoritos` bigint(21)
,`ranking` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `v_generos_top2_usuario`
--
DROP TABLE IF EXISTS `v_generos_top2_usuario`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_generos_top2_usuario`  AS SELECT `sub`.`id_usuario` AS `id_usuario`, `sub`.`id_genero` AS `id_genero`, `sub`.`total_favoritos` AS `total_favoritos`, `sub`.`ranking` AS `ranking` FROM (select `f`.`id_usuario` AS `id_usuario`,`g`.`id_genero` AS `id_genero`,count(0) AS `total_favoritos`,rank() over ( partition by `f`.`id_usuario` order by count(0) desc) AS `ranking` from ((`favorito` `f` join `juego_genero` `jg` on(`f`.`id_juego` = `jg`.`id_juego`)) join `genero` `g` on(`jg`.`id_genero` = `g`.`id_genero`)) group by `f`.`id_usuario`,`g`.`id_genero`) AS `sub` WHERE `sub`.`ranking` <= 2 ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calificacion`
--
ALTER TABLE `calificacion`
  ADD PRIMARY KEY (`id_usuario`,`id_juego`),
  ADD KEY `id_juego` (`id_juego`);

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_juego` (`id_juego`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id_empresa`);

--
-- Indices de la tabla `envio_notificacion`
--
ALTER TABLE `envio_notificacion`
  ADD PRIMARY KEY (`id_envio`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_juego` (`id_juego`);

--
-- Indices de la tabla `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id_faq`),
  ADD KEY `id_autor` (`id_autor`);

--
-- Indices de la tabla `favorito`
--
ALTER TABLE `favorito`
  ADD PRIMARY KEY (`id_usuario`,`id_juego`),
  ADD KEY `id_juego` (`id_juego`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id_genero`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `juego`
--
ALTER TABLE `juego`
  ADD PRIMARY KEY (`id_juego`),
  ADD KEY `id_empresa` (`id_empresa`);

--
-- Indices de la tabla `juego_genero`
--
ALTER TABLE `juego_genero`
  ADD PRIMARY KEY (`id_juego`,`id_genero`),
  ADD KEY `id_genero` (`id_genero`);

--
-- Indices de la tabla `juego_imagen`
--
ALTER TABLE `juego_imagen`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_juego` (`id_juego`);

--
-- Indices de la tabla `juego_plataforma`
--
ALTER TABLE `juego_plataforma`
  ADD PRIMARY KEY (`id_juego`,`id_plataforma`),
  ADD KEY `id_plataforma` (`id_plataforma`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id_permiso`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `plataforma`
--
ALTER TABLE `plataforma`
  ADD PRIMARY KEY (`id_plataforma`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `reporte_comentario`
--
ALTER TABLE `reporte_comentario`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `id_comentario` (`id_comentario`),
  ADD KEY `id_usuario_reporta` (`id_usuario_reporta`),
  ADD KEY `id_moderador_accion` (`id_moderador_accion`);

--
-- Indices de la tabla `restriccion_usuario`
--
ALTER TABLE `restriccion_usuario`
  ADD PRIMARY KEY (`id_restriccion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD PRIMARY KEY (`id_rol`,`id_permiso`),
  ADD KEY `id_permiso` (`id_permiso`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD PRIMARY KEY (`id_usuario`,`id_rol`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `envio_notificacion`
--
ALTER TABLE `envio_notificacion`
  MODIFY `id_envio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `faq`
--
ALTER TABLE `faq`
  MODIFY `id_faq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `juego`
--
ALTER TABLE `juego`
  MODIFY `id_juego` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `juego_imagen`
--
ALTER TABLE `juego_imagen`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `plataforma`
--
ALTER TABLE `plataforma`
  MODIFY `id_plataforma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reporte_comentario`
--
ALTER TABLE `reporte_comentario`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `restriccion_usuario`
--
ALTER TABLE `restriccion_usuario`
  MODIFY `id_restriccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calificacion`
--
ALTER TABLE `calificacion`
  ADD CONSTRAINT `calificacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `calificacion_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE CASCADE;

--
-- Filtros para la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE CASCADE;

--
-- Filtros para la tabla `envio_notificacion`
--
ALTER TABLE `envio_notificacion`
  ADD CONSTRAINT `envio_notificacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `envio_notificacion_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE CASCADE;

--
-- Filtros para la tabla `faq`
--
ALTER TABLE `faq`
  ADD CONSTRAINT `faq_ibfk_1` FOREIGN KEY (`id_autor`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `favorito`
--
ALTER TABLE `favorito`
  ADD CONSTRAINT `favorito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorito_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE CASCADE;

--
-- Filtros para la tabla `juego`
--
ALTER TABLE `juego`
  ADD CONSTRAINT `juego_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`);

--
-- Filtros para la tabla `juego_genero`
--
ALTER TABLE `juego_genero`
  ADD CONSTRAINT `juego_genero_ibfk_1` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE CASCADE,
  ADD CONSTRAINT `juego_genero_ibfk_2` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id_genero`) ON DELETE CASCADE;

--
-- Filtros para la tabla `juego_imagen`
--
ALTER TABLE `juego_imagen`
  ADD CONSTRAINT `juego_imagen_ibfk_1` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE CASCADE;

--
-- Filtros para la tabla `juego_plataforma`
--
ALTER TABLE `juego_plataforma`
  ADD CONSTRAINT `juego_plataforma_ibfk_1` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE CASCADE,
  ADD CONSTRAINT `juego_plataforma_ibfk_2` FOREIGN KEY (`id_plataforma`) REFERENCES `plataforma` (`id_plataforma`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reporte_comentario`
--
ALTER TABLE `reporte_comentario`
  ADD CONSTRAINT `reporte_comentario_ibfk_1` FOREIGN KEY (`id_comentario`) REFERENCES `comentario` (`id_comentario`) ON DELETE CASCADE,
  ADD CONSTRAINT `reporte_comentario_ibfk_2` FOREIGN KEY (`id_usuario_reporta`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `reporte_comentario_ibfk_3` FOREIGN KEY (`id_moderador_accion`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `restriccion_usuario`
--
ALTER TABLE `restriccion_usuario`
  ADD CONSTRAINT `restriccion_usuario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `rol_permiso_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE,
  ADD CONSTRAINT `rol_permiso_ibfk_2` FOREIGN KEY (`id_permiso`) REFERENCES `permiso` (`id_permiso`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD CONSTRAINT `usuario_rol_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_rol_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `evento_actualizar_restricciones` ON SCHEDULE EVERY 1 DAY STARTS '2025-10-12 20:22:17' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
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
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
