<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">
                    <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formLogin">
                    <div class="mb-3">
                        <label for="loginUsername" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="loginUsername" name="loginUsername" required
                            placeholder="Tu nombre de usuario">
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="loginPassword" name="loginPassword" required
                            placeholder="">
                    </div>
                    <div id="loginError" class="alert alert-danger d-none" role="alert"></div>
                    <div id="loginSuccess" class="alert alert-success d-none" role="alert"></div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Ingresar
                        </button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <small>¿No tienes cuenta?
                        <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#registroModal">
                            Regístrate aquí
                        </a>
                    </small>
                    <div class="mt-2">
                        <small>
                            <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#recuperarModal" class="text-secondary">
                                Olvidé mi contraseña
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Registro -->
<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registroModalLabel">
                    <i class="bi bi-person-plus"></i> Registrarse
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistro">
                    <div class="mb-3">
                        <label for="regUsername" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control" id="regUsername" name="username" required
                            placeholder="Mínimo 3 caracteres" minlength="3" maxlength="50">
                        <small class="form-text text-muted">Este será tu nombre visible en el sitio</small>
                    </div>
                    <div class="mb-3">
                        <label for="regEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="regEmail" name="email" required
                            placeholder="tu@email.com">
                    </div>
                    <div class="mb-3">
                        <label for="regPassword" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="regPassword" name="password" required
                            placeholder="Mínimo 8 caracteres" minlength="8">
                        <small class="form-text text-muted">Usa una contraseña segura (al menos 8 caracteres)</small>
                    </div>
                    <div id="registroError" class="alert alert-danger d-none" role="alert"></div>
                    <div id="registroSuccess" class="alert alert-success d-none" role="alert"></div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Crear Cuenta
                        </button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <small>¿Ya tienes cuenta?
                        <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">
                            Inicia sesión aquí
                        </a>
                    </small>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Modal Recuperar Contraseña -->
<div class="modal fade" id="recuperarModal" tabindex="-1" aria-labelledby="recuperarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recuperarModalLabel">
                    <i class="bi bi-key"></i> Recuperar Contraseña
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <!-- PASO 1: Ingresar Email -->
                <form id="formRecuperarPaso1">
                    <div class="mb-3">
                        <label for="recuperarEmail" class="form-label">Ingresa tu email registrado</label>
                        <input type="email" class="form-control" id="recuperarEmail" required placeholder="tu@email.com">
                    </div>
                    <div id="recuperarError1" class="alert alert-danger d-none" role="alert"></div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Verificar Email
                        </button>
                    </div>
                </form>

                <!-- PASO 2: Nueva Contraseña (Oculto inicialmente) -->
                <form id="formRecuperarPaso2" class="d-none">
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> Email verificado. Ingresa tu nueva contraseña.
                    </div>
                    <input type="hidden" id="recuperarEmailConfirmado">
                    
                    <div class="mb-3">
                        <label for="recuperarPassword" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="recuperarPassword" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label for="recuperarPasswordConfirm" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="recuperarPasswordConfirm" required minlength="6">
                    </div>
                    
                    <div id="recuperarError2" class="alert alert-danger d-none" role="alert"></div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            Actualizar Contraseña
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <small>
                        <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">
                            Volver al inicio de sesión
                        </a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
