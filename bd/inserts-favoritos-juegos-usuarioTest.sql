USE upegaming;

USE upegaming;

-- =====================================================
-- Usuario de ejemplo
-- =====================================================
INSERT INTO usuario (id_usuario, username, email, password_hash)
VALUES
(1, 'sergio', 'sergio@upe.com', 'hash123'),
(2, 'emma', 'emma@upe.com', 'hash123'),
(3, 'cami', 'cami@example.com', 'hash123'),
(4, 'tati', 'tati@example.com', 'hash123');

-- =====================================================
-- Roles y permisos
-- =====================================================
INSERT INTO rol (id_rol, nombre) VALUES
(1, 'usuario'),
(2, 'moderador'),
(3, 'admin');



INSERT INTO permiso (id_permiso, nombre, descripcion) VALUES
-- Permisos básicos de usuario
(1, 'ver_juegos', 'Puede visualizar juegos'),
(2, 'marcar_favorito', 'Puede agregar juegos a favoritos'),
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

INSERT INTO USUARIO_ROL (id_usuario, id_rol) VALUES 
(1,1),
(2,2),
(3,3),
(4,1);

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




-- =====================================================
-- Empresas (desarrolladores / publishers)
-- =====================================================
INSERT INTO empresa (id_empresa, nombre, sitio_web) VALUES
(1, 'Respawn Entertainment', 'https://www.respawn.com/'),
(2, 'Relic Entertainment', 'https://www.relic.com/'),
(3, 'Ubisoft', 'https://www.ubisoft.com/'),
(4, 'Activision', 'https://www.activision.com/'),
(5, 'Valve', 'https://www.valvesoftware.com/'),
(6, 'Blizzard Entertainment', 'https://blizzard.com/'),
(7, 'Rockstar Games', 'https://www.rockstargames.com/'),
(8, 'Visual Concepts / 2K', 'https://www.2k.com/'),
(9, 'Recreate Games', 'https://recreate.games/'),
(10, 'Capcom', 'https://www.capcom.com/');

-- =====================================================
-- Plataformas
-- =====================================================
INSERT INTO plataforma (id_plataforma, nombre) VALUES
(1, 'PC'),
(2, 'PlayStation 5'),
(3, 'Xbox Series X');

-- =====================================================
-- Géneros
-- =====================================================
INSERT INTO genero (id_genero, nombre) VALUES
(1, 'Acción'),
(2, 'Aventura'),
(3, 'RPG'),
(4, 'Shooter'),
(5, 'Estrategia'),
(6, 'Deportes'),
(7, 'Party');

-- =====================================================
-- Juegos
-- =====================================================
INSERT INTO juego (id_juego, titulo, descripcion, id_empresa, imagen_portada, publicado) VALUES
(1, 'Apex Legends',
 'Apex Legends es el galardonado juego gratuito de acción en primera persona de Respawn Entertainment. Domina un elenco creciente de leyendas con potentes habilidades. Juego estratégico basado en pelotones y jugabilidad innovadora en la nueva evolución del Battle Royale y la acción en primera persona.',
 1, 'img/hapex.jpg', 1),

(2, 'Age of Empires IV',
 'Para celebrar su primer año cautivando a millones de jugadores en todo el mundo, la galardonada y exitosa franquicia de estrategia continúa con Age of Empires IV: Edición Aniversario, sumergiéndote en las épicas batallas históricas que cambiaron el mundo.',
 2, 'img/hage.jpg', 1),

(3, 'Assassin\'s Creed Origins',
 'Explora el antiguo Egipto en este juego de acción y aventura. Enfréntate a enemigos poderosos, desvela conspiraciones y descubre la historia del origen de la Hermandad de Asesinos.',
 3, 'img/hassasin.jpg', 1),

(4, 'Call of Duty: Black Ops 7',
 'La entrega más alucinante de Black Ops hasta la fecha con una innovativa campaña cooperativa, una experiencia multijugador eléctrica y el legendario modo Zombis por rondas.',
 4, 'img/hcall.jpg', 1),

(5, 'Counter Strike 2',
 'Durante las dos últimas décadas, Counter-Strike ha proporcionado una experiencia competitiva de primer nivel para los millones de jugadores de todo el mundo que contribuyeron a darle forma. Ahora el próximo capítulo en la historia de CS está a punto de comenzar.',
 5, 'img/hcs.jpg', 1),

(6, 'Diablo IV',
 'Únete a la lucha por Santuario en Diablo IV, la aventura de rol y acción definitiva. Vive la campaña alabada por la crítica y nuevo contenido de temporada.',
 6, 'img/hdiablo.jpg', 1),

(7, 'Grand Theft Auto V',
 'Disfruta de los superventas del entretenimiento Grand Theft Auto V y Grand Theft Auto Online, ahora mejorados para una nueva generación, con impresionantes gráficos, carga más rápida, audio 3D y mucho más.',
 7, 'img/hgta.jpg', 1),

(8, 'NBA 2K26',
 'Exhibe tu colección de movimientos con hiperrealismo, gracias a la tecnología ProPLAY y desafía a tus amigos, o rivales, en los modos competitivos de NBA 2K26, y leave no doubt: tú eres el rey.',
 8, 'img/hnba.jpg', 1),

(9, 'Party Animals',
 'Pelea contra tus amigos como perritos, gatitos y otras criaturas peludas en PARTY ANIMALS! Patea a tus amigos tanto online como offline. Interactúa con el mundo bajo nuestro motor de físicas realistas.¿Ya mencioné PERRITOS?',
 9, 'img/hparty.jpg', 1),

(10, 'Resident Evil 4',
 'Sobrevivir es solo el principio. Con una mecánica de juego modernizada, una historia reimaginada y unos gráficos espectacularmente detallados, Resident Evil 4 supone el renacimiento de un gigante del mundo de los videojuegos.',
 10, 'img/hresident.jpg', 1),

(11, 'Street Fighter 6',
 'Aquí llega el peso pesado de Capcom! Street Fighter 6 trae consigo una nueva evolución de la saga Street Fighter! Incluye tres modos de juego: World Tour, Fighting Ground y Battle Hub',
 10, 'img/hsf.jpg', 1);

-- =====================================================
-- Favoritos del usuario
-- =====================================================
INSERT INTO favorito (id_usuario, id_juego, fecha_agregado) VALUES
(1, 1, NOW()),
(1, 2, NOW()),
(1, 3, NOW()),
(1, 4, NOW()),
(1, 5, NOW()),
(1, 6, NOW()),
(1, 7, NOW()),
(1, 8, NOW()),
(1, 9, NOW()),
(1, 10, NOW()),
(1, 11, NOW());

USE upegaming;

-- =====================================================
-- Juego - Genero
-- =====================================================
INSERT INTO juego_genero (id_juego, id_genero) VALUES
-- Apex Legends -> Shooter, Acción
(1, 4), (1, 1),
-- Age of Empires IV -> Estrategia
(2, 5),
-- Assassin's Creed Origins -> Acción, Aventura
(3, 1), (3, 2),
-- Call of Duty: Black Ops 7 -> Acción, Shooter
(4, 1), (4, 4),
-- Counter Strike 2 -> Shooter
(5, 4),
-- Diablo IV -> RPG, Acción
(6, 3), (6, 1),
-- Grand Theft Auto V -> Acción, Aventura
(7, 1), (7, 2),
-- NBA 2K26 -> Deportes
(8, 6),
-- Party Animals -> Party, Acción
(9, 7), (9, 1),
-- Resident Evil 4 -> Acción, Aventura
(10, 1), (10, 2),
-- Street Fighter 6 -> Acción
(11, 1);

-- =====================================================
-- Juego - Plataforma
-- =====================================================
INSERT INTO juego_plataforma (id_juego, id_plataforma) VALUES
-- Apex Legends -> PC, PS5, Xbox
(1, 1), (1, 2), (1, 3),
-- Age of Empires IV -> PC
(2, 1),
-- Assassin's Creed Origins -> PC, PS5, Xbox
(3, 1), (3, 2), (3, 3),
-- Call of Duty: Black Ops 7 -> PC, PS5, Xbox
(4, 1), (4, 2), (4, 3),
-- Counter Strike 2 -> PC
(5, 1),
-- Diablo IV -> PC, PS5, Xbox
(6, 1), (6, 2), (6, 3),
-- Grand Theft Auto V -> PC, PS5, Xbox
(7, 1), (7, 2), (7, 3),
-- NBA 2K26 -> PC, PS5, Xbox
(8, 1), (8, 2), (8, 3),
-- Party Animals -> PC, PS5
(9, 1), (9, 2),
-- Resident Evil 4 -> PC, PS5, Xbox
(10, 1), (10, 2), (10, 3),
-- Street Fighter 6 -> PC, PS5, Xbox
(11, 1), (11, 2), (11, 3);

-- =====================================================
-- Juego - Imagen (extras para la galería)
-- =====================================================
INSERT INTO juego_imagen (id_imagen, id_juego, url_imagen) VALUES
-- Apex Legends
(1, 1, 'img/hapex.jpg'),
(2, 1, 'img/hapex2.jpg'),
-- Age of Empires IV
(3, 2, 'img/hage.jpg'),
(4, 2, 'img/hage2.jpg'),
-- Assassin's Creed Origins
(5, 3, 'img/hassasin.jpg'),
(6, 3, 'img/hassasin2.jpg'),
-- Call of Duty: Black Ops 7
(7, 4, 'img/hcall.jpg'),
(8, 4, 'img/hcall2.jpg'),
-- Counter Strike 2
(9, 5, 'img/hcs.jpg'),
(10, 5, 'img/hcs2.jpg'),
-- Diablo IV
(11, 6, 'img/hdiablo.jpg'),
(12, 6, 'img/hdiablo2.jpg'),
-- Grand Theft Auto V
(13, 7, 'img/hgta.jpg'),
(14, 7, 'img/hgta2.jpg'),
-- NBA 2K26
(15, 8, 'img/hnba.jpg'),
(16, 8, 'img/hnba2.jpg'),
-- Party Animals
(17, 9, 'img/hparty.jpg'),
(18, 9, 'img/hparty2.jpg'),
-- Resident Evil 4
(19, 10, 'img/hresident.jpg'),
(20, 10, 'img/hresident2.jpg'),
-- Street Fighter 6
(21, 11, 'img/hsf.jpg'),
(22, 11, 'img/hsf2.jpg');




-- =====================================================
-- Roles y permisos
-- =====================================================
INSERT INTO rol (id_rol, nombre) VALUES
(1, 'usuario');

INSERT INTO permiso (id_permiso, nombre, descripcion) VALUES
(1, 'ver_juegos', 'Puede visualizar juegos'),
(2, 'marcar_favorito', 'Puede agregar juegos a favoritos');

INSERT INTO rol_permiso (id_rol, id_permiso) VALUES
(1, 1), (1, 2);

-- =====================================================
-- Usuario de ejemplo
-- =====================================================
INSERT INTO usuario (id_usuario, username, email, password_hash, rol_id)
VALUES
(1, 'sergio', 'sergio@example.com', 'hash123', 1);

-- =====================================================
-- Empresas (desarrolladores / publishers)
-- =====================================================
INSERT INTO empresa (id_empresa, nombre, sitio_web) VALUES
(1, 'Respawn Entertainment', 'https://www.respawn.com/'),
(2, 'Relic Entertainment', 'https://www.relic.com/'),
(3, 'Ubisoft', 'https://www.ubisoft.com/'),
(4, 'Activision', 'https://www.activision.com/'),
(5, 'Valve', 'https://www.valvesoftware.com/'),
(6, 'Blizzard Entertainment', 'https://blizzard.com/'),
(7, 'Rockstar Games', 'https://www.rockstargames.com/'),
(8, 'Visual Concepts / 2K', 'https://www.2k.com/'),
(9, 'Recreate Games', 'https://recreate.games/'),
(10, 'Capcom', 'https://www.capcom.com/');

-- =====================================================
-- Plataformas
-- =====================================================
INSERT INTO plataforma (id_plataforma, nombre) VALUES
(1, 'PC'),
(2, 'PlayStation 5'),
(3, 'Xbox Series X');

-- =====================================================
-- Géneros
-- =====================================================
INSERT INTO genero (id_genero, nombre) VALUES
(1, 'Acción'),
(2, 'Aventura'),
(3, 'RPG'),
(4, 'Shooter'),
(5, 'Estrategia'),
(6, 'Deportes'),
(7, 'Party');

-- =====================================================
-- Juegos
-- =====================================================
INSERT INTO juego (id_juego, titulo, descripcion, id_empresa, imagen_portada, publicado) VALUES
(1, 'Apex Legends',
 'Apex Legends es el galardonado juego gratuito de acción en primera persona de Respawn Entertainment. Domina un elenco creciente de leyendas con potentes habilidades. Juego estratégico basado en pelotones y jugabilidad innovadora en la nueva evolución del Battle Royale y la acción en primera persona.',
 1, 'img/hapex.jpg', 1),

(2, 'Age of Empires IV',
 'Para celebrar su primer año cautivando a millones de jugadores en todo el mundo, la galardonada y exitosa franquicia de estrategia continúa con Age of Empires IV: Edición Aniversario, sumergiéndote en las épicas batallas históricas que cambiaron el mundo.',
 2, 'img/hage.jpg', 1),

(3, 'Assassin\'s Creed Origins',
 'Explora el antiguo Egipto en este juego de acción y aventura. Enfréntate a enemigos poderosos, desvela conspiraciones y descubre la historia del origen de la Hermandad de Asesinos.',
 3, 'img/hassasin.jpg', 1),

(4, 'Call of Duty: Black Ops 7',
 'La entrega más alucinante de Black Ops hasta la fecha con una innovativa campaña cooperativa, una experiencia multijugador eléctrica y el legendario modo Zombis por rondas.',
 4, 'img/hcall.jpg', 1),

(5, 'Counter Strike 2',
 'Durante las dos últimas décadas, Counter-Strike ha proporcionado una experiencia competitiva de primer nivel para los millones de jugadores de todo el mundo que contribuyeron a darle forma. Ahora el próximo capítulo en la historia de CS está a punto de comenzar.',
 5, 'img/hcs.jpg', 1),

(6, 'Diablo IV',
 'Únete a la lucha por Santuario en Diablo IV, la aventura de rol y acción definitiva. Vive la campaña alabada por la crítica y nuevo contenido de temporada.',
 6, 'img/hdiablo.jpg', 1),

(7, 'Grand Theft Auto V',
 'Disfruta de los superventas del entretenimiento Grand Theft Auto V y Grand Theft Auto Online, ahora mejorados para una nueva generación, con impresionantes gráficos, carga más rápida, audio 3D y mucho más.',
 7, 'img/hgta.jpg', 1),

(8, 'NBA 2K26',
 'Exhibe tu colección de movimientos con hiperrealismo, gracias a la tecnología ProPLAY y desafía a tus amigos, o rivales, en los modos competitivos de NBA 2K26, y leave no doubt: tú eres el rey.',
 8, 'img/hnba.jpg', 1),

(9, 'Party Animals',
 'Pelea contra tus amigos como perritos, gatitos y otras criaturas peludas en PARTY ANIMALS! Patea a tus amigos tanto online como offline. Interactúa con el mundo bajo nuestro motor de físicas realistas.¿Ya mencioné PERRITOS?',
 9, 'img/hparty.jpg', 1),

(10, 'Resident Evil 4',
 'Sobrevivir es solo el principio. Con una mecánica de juego modernizada, una historia reimaginada y unos gráficos espectacularmente detallados, Resident Evil 4 supone el renacimiento de un gigante del mundo de los videojuegos.',
 10, 'img/hresident.jpg', 1),

(11, 'Street Fighter 6',
 'Aquí llega el peso pesado de Capcom! Street Fighter 6 trae consigo una nueva evolución de la saga Street Fighter! Incluye tres modos de juego: World Tour, Fighting Ground y Battle Hub',
 10, 'img/hsf.jpg', 1);

-- =====================================================
-- Favoritos del usuario
-- =====================================================
INSERT INTO favorito (id_usuario, id_juego, fecha_agregado) VALUES
(1, 1, NOW()),
(1, 2, NOW()),
(1, 3, NOW()),
(1, 4, NOW()),
(1, 5, NOW()),
(1, 6, NOW()),
(1, 7, NOW()),
(1, 8, NOW()),
(1, 9, NOW()),
(1, 10, NOW()),
(1, 11, NOW());

USE upegaming;

-- =====================================================
-- Juego - Genero
-- =====================================================
INSERT INTO juego_genero (id_juego, id_genero) VALUES
-- Apex Legends -> Shooter, Acción
(1, 4), (1, 1),
-- Age of Empires IV -> Estrategia
(2, 5),
-- Assassin's Creed Origins -> Acción, Aventura
(3, 1), (3, 2),
-- Call of Duty: Black Ops 7 -> Acción, Shooter
(4, 1), (4, 4),
-- Counter Strike 2 -> Shooter
(5, 4),
-- Diablo IV -> RPG, Acción
(6, 3), (6, 1),
-- Grand Theft Auto V -> Acción, Aventura
(7, 1), (7, 2),
-- NBA 2K26 -> Deportes
(8, 6),
-- Party Animals -> Party, Acción
(9, 7), (9, 1),
-- Resident Evil 4 -> Acción, Aventura
(10, 1), (10, 2),
-- Street Fighter 6 -> Acción
(11, 1);

-- =====================================================
-- Juego - Plataforma
-- =====================================================
INSERT INTO juego_plataforma (id_juego, id_plataforma) VALUES
-- Apex Legends -> PC, PS5, Xbox
(1, 1), (1, 2), (1, 3),
-- Age of Empires IV -> PC
(2, 1),
-- Assassin's Creed Origins -> PC, PS5, Xbox
(3, 1), (3, 2), (3, 3),
-- Call of Duty: Black Ops 7 -> PC, PS5, Xbox
(4, 1), (4, 2), (4, 3),
-- Counter Strike 2 -> PC
(5, 1),
-- Diablo IV -> PC, PS5, Xbox
(6, 1), (6, 2), (6, 3),
-- Grand Theft Auto V -> PC, PS5, Xbox
(7, 1), (7, 2), (7, 3),
-- NBA 2K26 -> PC, PS5, Xbox
(8, 1), (8, 2), (8, 3),
-- Party Animals -> PC, PS5
(9, 1), (9, 2),
-- Resident Evil 4 -> PC, PS5, Xbox
(10, 1), (10, 2), (10, 3),
-- Street Fighter 6 -> PC, PS5, Xbox
(11, 1), (11, 2), (11, 3);

-- =====================================================
-- Juego - Imagen (extras para la galería)
-- =====================================================
INSERT INTO juego_imagen (id_imagen, id_juego, url_imagen) VALUES
-- Apex Legends
(1, 1, 'img/hapex.jpg'),
(2, 1, 'img/hapex2.jpg'),
-- Age of Empires IV
(3, 2, 'img/hage.jpg'),
(4, 2, 'img/hage2.jpg'),
-- Assassin's Creed Origins
(5, 3, 'img/hassasin.jpg'),
(6, 3, 'img/hassasin2.jpg'),
-- Call of Duty: Black Ops 7
(7, 4, 'img/hcall.jpg'),
(8, 4, 'img/hcall2.jpg'),
-- Counter Strike 2
(9, 5, 'img/hcs.jpg'),
(10, 5, 'img/hcs2.jpg'),
-- Diablo IV
(11, 6, 'img/hdiablo.jpg'),
(12, 6, 'img/hdiablo2.jpg'),
-- Grand Theft Auto V
(13, 7, 'img/hgta.jpg'),
(14, 7, 'img/hgta2.jpg'),
-- NBA 2K26
(15, 8, 'img/hnba.jpg'),
(16, 8, 'img/hnba2.jpg'),
-- Party Animals
(17, 9, 'img/hparty.jpg'),
(18, 9, 'img/hparty2.jpg'),
-- Resident Evil 4
(19, 10, 'img/hresident.jpg'),
(20, 10, 'img/hresident2.jpg'),
-- Street Fighter 6
(21, 11, 'img/hsf.jpg'),
(22, 11, 'img/hsf2.jpg');

