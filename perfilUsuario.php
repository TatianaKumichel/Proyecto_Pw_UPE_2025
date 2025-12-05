<?php
// Proteger pagina  solo usuarios logueados  
require_once './inc/auth.php';
requiereLogin();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php require "inc/head.php"; ?>
    <script src="./js/scriptPerfilUsuario.js" defer></script>
     <link rel="stylesheet" href="./css/stylePerfilUsuario.css" />
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

 
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php
    require "./inc/menu.php";

    // Cargar datos del usuario desde la base de datos
    require "./inc/connection.php";
    $stmt = $conn->prepare("SELECT * FROM USUARIO WHERE id_usuario = ?");
    $stmt->execute([$_SESSION['id_usuario']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['nombre'] = $usuario['username'];
    ?>

   <main class="container my-4 flex-fill">

  <div class="panel-claro shadow-sm">
    <h2 class="text-center mb-4">Perfil de Usuario</h2>

    <!-- Datos del usuario -->
    <div id="usuarioDatos" data-id="<?php echo $_SESSION['id_usuario']; ?>">
      <h4 class="mb-4 text-primary fw-bold">
        <span id="mostrarNombre"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
      </h4>
    </div>

    <!-- Editar nombre -->
    <div class="mb-4">
      <button id="btnEditarNombre" class="btn btn-primary w-100 fw-semibold shadow-sm">
        Editar Nombre de Usuario
      </button>

      <form method="POST" id="formEditarNombre" class="form-box d-none mt-3 p-3 rounded border">
        <div class="mb-3">
          <label for="campoNombre" class="form-label fw-semibold">Nuevo nombre de usuario</label>
                <input
                type="text"
                id="campoNombre"
                class="form-control"
                placeholder="Ingrese su nombre"
                required
                minlength="3"
                maxlength="50"
                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s.,;:!@#$%^&*()_\-+=¿?¡!'\"/\\]+"
            />
       

          <div id="errorNombre" class="text-danger mt-2 d-none"></div>
        </div>

        <button type="submit" class="btn btn-success w-100 fw-semibold shadow-sm">
          Guardar
        </button>
      </form>
    </div>

    <!-- Cambiar contraseña -->
    <div class="mb-4">
      <button id="btnCambiarContrasena" class="btn btn-warning w-100 fw-semibold shadow-sm">
        Cambiar Contraseña
      </button>

      <form id="formCambiarContrasena" class="form-box d-none mt-3 p-3 rounded border">
        <div class="mb-3 " >
          <label for="campoContrasena" class="form-label fw-semibold">Nueva contraseña</label>
                <input
                type="password"
                id="campoContrasena"
                class="form-control"
                placeholder="Ingrese su contraseña"
                required
                minlength="6"
                
            />

            

          <div id="errorContrasena" class="text-danger mt-2 d-none"></div>
        </div>

        <button type="submit" class="btn btn-success w-100 fw-semibold shadow-sm">
          Guardar
        </button>
      </form>
    </div>

    <!-- Favoritos -->
    <div class="text-end">
      <a href="favoritos.php" class="btn btn-info fw-semibold shadow-sm">
        Ver mis favoritos
      </a>
    </div>

  </div>
</main>


    <footer class="bg-dark text-white mt-auto pt-3 pb-2">
        <?php require "./inc/footer.php"; ?>
    </footer>

    
    <div id="modalExito" class="modal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 text-center">
                <h5 id="titulo-exito" class="modal-title"></h5>
            </div>
            <div class="modal-body text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-check-icon lucide-check">
                    <path d="M20 6 9 17l-5-5" />
                </svg>
                <p>La operación se realizó con éxito</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button id="btn-exito" type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>


</body>

</html>
