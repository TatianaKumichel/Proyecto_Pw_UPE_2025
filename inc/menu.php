<?php
// consultar sobre uso de session_start y $_SESSION
session_start();

/* 
 El rol se define en el login (usuario, moderador, admin, visitante (default))
 se usa para mostrar/ocultar opciones del menu
 */
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'visitante';

// nombre del usuario logueado para mostrar en el menu. Falta desarrollo.
$nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Mi perfil';


/* 
    hardcode para probar:
    --------------------
    visitante: solo ve catalogo, registrarse e iniciar sesion
    usuario: Ya esta logueado, oculto registarse e iniciar sesion. Ve catalogo, perfil y favoritos. 
    moderador: Ya esta logueado, oculto registarse e iniciar sesion. Ve catalogo?, perfil?, gestion comentarios y FAQ.
    admin: Ya esta logueado, oculto registarse e iniciar sesion. Ve catalogo?, perfil?, gestiones admin.
*/
$rol = 'visitante';
$nombreUsuario = 'Pepe';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="./index.php">
            <div class="bg-dark text-white rounded p-2 me-2 d-flex align-items-center justify-content-center">
                <i class="bi bi-controller"></i>
            </div>
            <div>
                <span class="fw-bold">UPEGaming</span><br />
                <small class="text-muted d-none d-sm-inline">Tu catálogo de videojuegos</small>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="./index.php">
                        <i class="bi bi-house"></i><span class="d-lg-none"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./filtros.php">
                        <i class="bi bi-collection"></i> <span>Catálogo</span>
                    </a>
                </li>

                <?php if ($rol === 'visitante'): ?>
                    <!-- Visitante no logueado -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#registroModal">
                            <i class="bi bi-person-plus"></i> Registrarse
                        </a>
                    </li>

                <?php else: ?>
                    <!-- Usuario logueado -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="perfilDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 me-1"></i>
                            <span id="nombrePerfil" class="d-lg-inline">
                                <?php echo $nombreUsuario ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="perfilDropdown">
                            <?php if ($rol === 'usuario' || $rol === 'moderador' || $rol === 'admin'): ?>
                                <li><a class="dropdown-item" href="./perfilUsuario.php"><i class="bi bi-person"></i> Ver
                                        Perfil</a></li>

                                <?php if ($rol === 'usuario'): ?>
                                    <li><a class="dropdown-item" href="./favoritos.php"><i class="bi bi-heart"></i> Mis
                                            Favoritos</a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            <?php endif; ?>

                            <?php if ($rol === 'moderador'): ?>
                                <li><a class="dropdown-item" href="./moderador-comentarios-reportados.php">
                                        <i class="bi bi-chat-dots"></i> Comentarios Reportados</a></li>
                                <li><a class="dropdown-item" href="./moderador-gestion-faq.php">
                                        <i class="bi bi-question-circle"></i> Gestión FAQ</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            <?php endif; ?>

                            <?php if ($rol === 'admin'): ?>
                                <li><a class="dropdown-item" href="./admin-plataformas.php"><i class="bi bi-display"></i>
                                        Plataformas</a></li>
                                <li><a class="dropdown-item" href="./admin-generos.php"><i class="bi bi-tags"></i> Géneros</a>
                                </li>
                                <li><a class="dropdown-item" href="./admin-empresas.php"><i class="bi bi-building"></i>
                                        Empresas</a></li>
                                <li><a class="dropdown-item" href="./admin-moderadores.php"><i class="bi bi-shield"></i> Gestión
                                        Moderadores</a></li>
                                <li><a class="dropdown-item" href="./admin-juegos.php"><i class="bi bi-joystick"></i> Gestión
                                        Juegos</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            <?php endif; ?>
                            <?php if ($rol !== 'visitante'): ?>
                                <li><a id="cerrarSesion" class="dropdown-item text-danger" href="./logout.php">Cerrar Sesión</a>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>