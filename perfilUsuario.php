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
 
</head>

<body>

    <?php
    require "./inc/menu.php";

    // Cargar datos del usuario desde la base de datos
    require "./inc/connection.php";
    $stmt = $conn->prepare("SELECT * FROM USUARIO WHERE id_usuario = ?");
    $stmt->execute([$_SESSION['id_usuario']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['nombre'] = $usuario['username'];
    ?>

    <main class="container my-4">

        <div class="panel shadow">

            <h2 class="text-center  mb-4">Tu perfil</h2>

            <!-- Datos del usuario -->
            <div id="usuarioDatos" data-id="<?php echo $_SESSION['id_usuario']; ?>" class="text-center mb-4">
                <h4>
                    Bienvenid@,
                    <span id="mostrarNombre" class="text-info fw-bold">
                        <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                    </span>
                </h4>
            </div>

            <!-- Editar nombre -->
            <div class="mb-4">
                <button id="btnEditarNombre" class="btn-moderno">
                    Editar Nombre de Usuario
                </button>

                <form method="POST" id="formEditarNombre"
                    class="d-none mt-3 p-3 rounded bg-dark border border-secondary">

                    <label for="campoNombre" class="form-label text-light">Nuevo nombre de usuario</label>
                    <input
                        type="text"
                        id="campoNombre"
                        class="form-control bg-secondary text-light border-0"
                        placeholder="Ingrese su nuevo nombre" />

                    <div id="errorNombre" class="text-danger mt-2 d-none"></div>

                    <button type="submit" class="btn btn-success w-100 mt-3">Guardar</button>
                </form>
            </div>

            <!-- Cambiar contraseña -->
            <div class="mb-4">
                <button id="btnCambiarContrasena" class="btn-moderno">
                    Cambiar Contraseña
                </button>

                <form id="formCambiarContrasena"
                    class="d-none mt-3 p-3 rounded bg-dark border border-secondary">

                    <label for="campoContrasena" class="form-label text-light">Nueva contraseña</label>
                    <input
                        type="password"
                        id="campoContrasena"
                        class="form-control bg-secondary text-light border-0"
                        placeholder="Ingrese su nueva contraseña" />

                    <div id="errorContrasena" class="text-danger mt-2 d-none"></div>

                    <button type="submit" class="btn btn-success w-100 mt-3">Guardar</button>
                </form>
            </div>

            <!-- Ver favoritos -->
            <div class="ms-auto">
                <a href="favoritos.php" class="btn-moderno">Ver mis favoritos</a>
            </div>

        </div>

    </main>

    <footer class="bg-dark text-white mt-4 pt-3 pb-2">
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
