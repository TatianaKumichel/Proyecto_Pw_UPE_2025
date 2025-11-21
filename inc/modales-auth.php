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
