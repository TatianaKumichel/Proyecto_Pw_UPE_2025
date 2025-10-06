<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  require "./inc/head.php";
  ?>
</head>

<body class="bg-light">
  <header>
    <?php
    require "./inc/menu.php";
    ?>
  </header>

  <!--  contenedor -->
  <main class="container my-4">
    <div class="row">
      <!-- Aqui se ponen los cards de juegos en columnas -->
      <div class="col-lg-9">
        <h3 class="mb-4">Catálogo</h3>
        <div class="row g-4">
          <!-- cards. Luego podrian ponerse dinamicamente tras conectar con una base de datos... -->
          <div class="col-12 col-sm-6 col-md-4" data-categoria="aventura">
            <div class="card h-100">
              <img src="https://i.3djuegos.com/juegos/17986/palworld/fotos/ficha/palworld-5861112.jpg"
                class="card-img-top" alt="Juego 1" />
              <div class="card-body">
                <h5 class="card-title">Juego 1</h5>
                <p class="card-text">Descripción.</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4" data-categoria="estrategia">
            <div class="card h-100">
              <img
                src="https://i0.wp.com/www.pcmrace.com/wp-content/uploads/2025/05/Warhammer-40.000-Dawn-of-War-%E2%80%93-Definitive-Edition-key-art-4k.jpg"
                class="card-img-top" alt="Juego 2" />
              <div class="card-body">
                <h5 class="card-title">Juego 2</h5>
                <p class="card-text">Descripción.</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4" data-categoria="supervivencia">
            <div class="card h-100">
              <img
                src="https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/3008130/b5944c43d563d780614d961ee859f7ce1248c9fa/capsule_616x353.jpg?t=1758717740"
                class="card-img-top" alt="Juego 3" />
              <div class="card-body">
                <h5 class="card-title">Juego 3</h5>
                <p class="card-text">Descripción.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carousel de imagenes en un costado o debajo acorde al tamaño de imagen -->
      <aside class="col-lg-3 mt-4 mt-lg-0">
        <h4>Tendencias</h4>
        <div id="tendenciasCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="https://upload.wikimedia.org/wikipedia/en/3/32/Kingdom_Come_Deliverance_II.jpg"
                class="d-block w-100" alt="Tendencia 1" />
            </div>
            <div class="carousel-item">
              <img
                src="https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/2208810/3667d481acc2a8693153cc978ff8cf6f744e8d63/capsule_616x353.jpg?t=1755181567"
                class="d-block w-100" alt="Tendencia 2" />
            </div>
            <div class="carousel-item">
              <img src="https://upload.wikimedia.org/wikipedia/en/7/7b/Silent_Hill_f_cover_art.png"
                class="d-block w-100" alt="Tendencia 3" />
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#tendenciasCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#tendenciasCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      </aside>
    </div>
  </main>

  <footer class="bg-dark text-white mt-4 pt-3 pb-2">
<?php
  require "./inc/footer.php";
?>
  </footer>

  <!-- modal de inicio de sesion -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formLogin">
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
                <a href="#" data-bs-toggle="modal" data-bs-target="#recuperarModal" data-bs-dismiss="modal">
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