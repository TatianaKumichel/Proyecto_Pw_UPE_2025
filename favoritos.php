<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    require "./inc/head.php";
    ?>
    <link rel="stylesheet" href="css/favoritos.css" />
    <script src="./js/favoritos.js" defer></script>
</head>

<body>
    <header>
        <?php
        require "./inc/menu.php";
        ?>
    </header>

    <main class="container my-4">
        <h2 class="mb-4">Favoritos</h2>

        <!-- Errores generales -->
        <div id="divErroresGenerales" class="alert alert-danger d-none" role="alert"></div>

        <!-- Listado de favoritos -->
        <div id="listaFavoritos" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"></div>
    </main>

    <footer class="bg-dark text-white mt-4 pt-3 pb-2">
        <?php
        require "./inc/footer.php";
        ?>
    </footer>

    <!-- Modal de confirmación -->
    <!-- Modal -->
    <div class="modal fade" id="modalConfirmar" tabindex="-1" aria-labelledby="modalConfirmarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmarLabel">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i> Confirmar acción
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que querés quitar este juego de tus favoritos?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">
                        <i class="bi bi-trash-fill"></i> Quitar
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!-- modal de inicio de sesion -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formLoginFake">
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="correo" required />
                        </div>
                        <div class="mb-3">
                            <label for="clave" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="clave" required />
                        </div>
                        <div id="loginError" class="text-danger mb-3" style="display: none"></div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Ingresar</button>
                            <div class="text-center mt-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#recuperarModal"
                                    data-bs-dismiss="modal">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- modal de registro -->
    <div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registroModalLabel">Crear cuenta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formRegistro" method="POST" class="needs-validation" action="">
                        <div class="mb-3">
                            <label for="registroNombre" class="form-label">Nombre de usuario</label>
                            <input type="text" class="form-control" id="registroNombre" required />
                            <div class="invalid-feedback">
                                Por favor, el nombre solo debe contener letras.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="registroCorreo" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="registroCorreo" required />
                            <div class="invalid-feedback">
                                Por favor, el email debe ser correcto.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="registroClave" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="registroClave" required />
                            <div class="invalid-feedback">
                                Por favor, la contraseña debe ser una válida.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="registroConfirmacion" class="form-label">Confirmar contraseña</label>
                            <input type="password" class="form-control" id="registroConfirmacion" required />
                            <div class="invalid-feedback">
                                Por favor, las contraseñas deben coincidir.
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                Registrarse
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- modal de recuperacion de contraseña -->
    <div class="modal fade" id="recuperarModal" tabindex="-1" aria-labelledby="recuperarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="recuperarModalLabel">
                        Recuperar Contraseña
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formRecuperar">
                        <div class="mb-3">
                            <label for="recuperarCorreo" class="form-label">Ingresa tu correo registrado</label>
                            <input type="email" class="form-control" id="recuperarCorreo" required />
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                Enviar enlace
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- modal de confirmacion de recuperacion de contraseña (para no usar un alert, pueden haber alternativas) -->
    <div class="modal fade" id="confirmacionModal" tabindex="-1" aria-labelledby="confirmacionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmacionModalLabel">
                        Recuperación de Contraseña
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="confirmacionMensaje">
                    <!-- con js aqui se coloca un mensaje de confirmacion -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>