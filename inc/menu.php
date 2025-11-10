<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir clases necesarias
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/classes/Permisos.php';
require_once __DIR__ . '/classes/Flash.php';

// Determinar si el usuario está logueado
$usuarioLogueado = isset($_SESSION['id_usuario']);
$nombreUsuario = 'Mi perfil';
$idUsuario = null;

if ($usuarioLogueado) {
    $nombreUsuario = $_SESSION['username'] ?? $_SESSION['nombre'] ?? 'Usuario';
    $idUsuario = $_SESSION['id_usuario'];
}
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
                        <i class="bi bi-house"></i><span class="d-lg-none"> Inicio</span>
                    </a>
                </li>

                <?php if ($usuarioLogueado && Permisos::tienePermiso('ver_juegos', $idUsuario)): ?>
                    <!-- Catálogo solo para usuarios con permiso -->
                    <li class="nav-item">
                        <a class="nav-link" href="./filtros.php">
                            <i class="bi bi-collection"></i> <span>Catálogo</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (!$usuarioLogueado): ?>
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
                                <?php echo htmlspecialchars($nombreUsuario); ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="perfilDropdown">
                            <!-- Perfil - Todos los usuarios logueados -->
                            <li><a class="dropdown-item" href="./perfilUsuario.php">
                                    <i class="bi bi-person"></i> Ver Perfil</a>
                            </li>

                            <?php if (Permisos::tienePermiso('marcar_favorito', $idUsuario)): ?>
                                <!-- Favoritos - Solo usuarios con permiso -->
                                <li><a class="dropdown-item" href="./favoritos.php">
                                        <i class="bi bi-heart"></i> Mis Favoritos</a>
                                </li>
                            <?php endif; ?>

                            <?php
                            // Verificar si tiene permisos de moderador o admin
                            $tienePermisosModeradorOAdmin = Permisos::tieneAlgunPermiso([
                                'moderar_comentarios',
                                'gestionar_faq',
                                'gestionar_juegos',
                                'gestionar_empresas',
                                'gestionar_plataformas',
                                'gestionar_generos',
                                'gestionar_moderadores'
                            ], $idUsuario);

                            if ($tienePermisosModeradorOAdmin):
                                ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="dropdown-header">Gestión</li>
                            <?php endif; ?>

                            <?php if (Permisos::tienePermiso('moderar_comentarios', $idUsuario)): ?>
                                <!-- Moderación de comentarios -->
                                <li><a class="dropdown-item" href="./moderador-comentarios-reportados.php">
                                        <i class="bi bi-chat-dots"></i> Comentarios Reportados</a>
                                </li>
                            <?php endif; ?>

                            <?php if (Permisos::tienePermiso('gestionar_faq', $idUsuario)): ?>
                                <!-- Gestión de FAQ -->
                                <li><a class="dropdown-item" href="./moderador-gestion-faq.php">
                                        <i class="bi bi-question-circle"></i> Gestión FAQ</a>
                                </li>
                            <?php endif; ?>

                            <?php if (Permisos::tienePermiso('gestionar_juegos', $idUsuario)): ?>
                                <!-- Gestión de Juegos -->
                                <li><a class="dropdown-item" href="./admin-juegos.php">
                                        <i class="bi bi-joystick"></i> Gestión Juegos</a>
                                </li>
                            <?php endif; ?>

                            <?php if (Permisos::tienePermiso('gestionar_empresas', $idUsuario)): ?>
                                <!-- Gestión de Empresas -->
                                <li><a class="dropdown-item" href="./admin-empresas.php">
                                        <i class="bi bi-building"></i> Empresas</a>
                                </li>
                            <?php endif; ?>

                            <?php if (Permisos::tienePermiso('gestionar_plataformas', $idUsuario)): ?>
                                <!-- Gestión de Plataformas -->
                                <li><a class="dropdown-item" href="./admin-plataformas.php">
                                        <i class="bi bi-display"></i> Plataformas</a>
                                </li>
                            <?php endif; ?>

                            <?php if (Permisos::tienePermiso('gestionar_generos', $idUsuario)): ?>
                                <!-- Gestión de Géneros -->
                                <li><a class="dropdown-item" href="./admin-generos.php">
                                        <i class="bi bi-tags"></i> Géneros</a>
                                </li>
                            <?php endif; ?>

                            <?php if (Permisos::tienePermiso('gestionar_moderadores', $idUsuario)): ?>
                                <!-- Gestión de Moderadores -->
                                <li><a class="dropdown-item" href="./admin-moderadores.php">
                                        <i class="bi bi-shield"></i> Gestión Moderadores</a>
                                </li>
                            <?php endif; ?>

                            <!-- Cerrar Sesión -->
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a id="cerrarSesion" class="dropdown-item text-danger" href="./logout.php">
                                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Mostrar mensajes flash si existen -->
<div class="container mt-3">
    <?php echo Flash::render(); ?>
</div>

<?php require_once './inc/modales-auth.php'; ?>