<!DOCTYPE html>
<html lang="es">

<head>
<?php require "inc/head.php"; ?>
<link rel="stylesheet" href="./css/pefilUsuario.css" />
<script src="js/scriptPerfilUsuario.js" defer></script>
 
</head>

<body class="bg-light">
  <?php
  require "./inc/menu.php";

  //Simulacion de sesion de usuario
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  // Si todavía no tiene login, simulamos un usuario
  if (!isset($_SESSION['id_usuario'])) {
      $_SESSION['id_usuario'] = 4;
      $_SESSION['nombre'] = 'tati';
      $_SESSION['email'] = 'tati@mail.com';
  }

  // carga desde la base de datos 

  require "./inc/connection.php";
  $stmt = $conn->prepare("SELECT * FROM USUARIO WHERE id_usuario = ?");
  $stmt->execute([$_SESSION['id_usuario']]);
  $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
  $_SESSION['nombre'] = $usuario['username'];
  
  ?>


  <main class="container my-4">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8">
          <div class="card shadow rounded-3">
            <div class="card-body">
              <h2 class="card-title mb-4 text-center">Perfil de Usuario</h2>

              <!-- Datos del usuario -->
              <div id="usuarioDatos" data-id="<?php echo $_SESSION['id_usuario']; ?>">
                <h4>
                  <span id="mostrarNombre"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                </h4>
              </div>

              <!-- Botón para editar nombre -->
              <div class="mb-3">
                <button id="btnEditarNombre" class="btn btn-primary w-100">
                  Editar Nombre de Usuario
                </button>

                <form method="POST" id="formEditarNombre" class="d-none mt-3">
                  <div class="mb-3">
                    <label for="campoNombre" class="form-label">Nuevo nombre de usuario</label>
                    <input
                      type="text"
                      id="campoNombre"
                      class="form-control"
                      placeholder="Ingrese su nombre"
                    />
                    <div id="errorNombre" class="text-danger mt-1 d-none"></div>
                  </div>
                  <button type="submit" class="btn btn-success w-100">Guardar</button>
                </form>
              </div>

              <!-- Botón para cambiar contraseña -->
              <div class="mb-3">
                <button id="btnCambiarContrasena" class="btn btn-warning w-100">
                  Cambiar Contraseña
                </button>

                <form id="formCambiarContrasena" class="d-none mt-3">
                  <div class="mb-3">
                    <label for="campoContrasena" class="form-label">Nueva contraseña</label>
                    <input
                      type="password"
                      id="campoContrasena"
                      class="form-control"
                      placeholder="Ingrese su contraseña"
                    />
                    <div id="errorContrasena" class="text-danger mt-1 d-none"></div>
                  </div>
                  <button type="submit" class="btn btn-success w-100">Guardar</button>
                </form>
              </div>

              <!-- Botón para ver favoritos -->
              <div class="mb-3">
                <a href="favoritos.php" class="btn btn-info w-100">Ver mis favoritos</a>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-dark text-white mt-4 pt-3 pb-2 fixed-bottom">
    <?php require "./inc/footer.php"; ?>
  </footer>
</body>

</html>