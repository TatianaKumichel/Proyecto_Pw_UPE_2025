<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perfil de Usuario</title>
    <?php
      require "./inc/head.php";
    ?>
    <script src="./js/scriptPerfilUsuario.js" defer></script>
  </head>

  <body class="bg-light">
    <header>
      <?php
      require "./inc/menu.php";
      ?>
    </header>
    <main class="container my-4">
      <div class="container py-5">
        <div class="row justify-content-center">
          <div class="col-12 col-md-8">
            <div class="card shadow rounded-3">
              <div class="card-body">
                <h2 class="card-title mb-4 text-center">Perfil de Usuario</h2>

                <!-- Datos visibles -->
                <div class="mb-3">
                  <p>
                    <strong>Nombre de usuario:</strong>
                    <span id="mostrarNombre">Juan Pérez</span>
                  </p>
                  <p>
                    <strong>Email:</strong>
                    <span id="mostrarEmail">juanperez@mail.com</span>
                  </p>
                </div>

                <!-- Botón para editar nombre -->
                <div class="mb-3">
                  <button id="btnEditarNombre" class="btn btn-primary w-100">
                    Editar Nombre de Usuario
                  </button>
                  <form id="formEditarNombre" class="d-none mt-3">
                    <div class="mb-3">
                      <label for="campoNombre" class="form-label"
                        >Nuevo nombre de usuario</label
                      >
                      <input
                        type="text"
                        id="campoNombre"
                        class="form-control"
                        placeholder="Ingrese su nombre"
                      />
                      <div
                        id="errorNombre"
                        class="text-danger mt-1 d-none"
                      ></div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                      Guardar
                    </button>
                  </form>
                </div>

                <!-- Botón para cambiar contraseña -->
                <div class="mb-3">
                  <button
                    id="btnCambiarContrasena"
                    class="btn btn-warning w-100"
                  >
                    Cambiar Contraseña
                  </button>
                  <form id="formCambiarContrasena" class="d-none mt-3">
                    <div class="mb-3">
                      <label for="campoContrasena" class="form-label"
                        >Nueva contraseña</label
                      >
                      <input
                        type="password"
                        id="campoContrasena"
                        class="form-control"
                        placeholder="Ingrese su contraseña"
                      />
                      <div
                        id="errorContrasena"
                        class="text-danger mt-1 d-none"
                      ></div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                      Guardar
                    </button>
                  </form>
                </div>

                <!-- Botón para ver favoritos -->
                <div class="mb-3">
                  <a href="favoritos.html" class="btn btn-info w-100"
                    >Ver mis favoritos</a
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <footer class="bg-dark text-white mt-4 pt-3 pb-2 fixed-bottom">
    <?php
      require "./inc/footer.php";
    ?>
    </footer>
    
  </body>
</html>
