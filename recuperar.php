<?php
session_start();
$errores = $_SESSION["errores"] ?? [];
$mensajeExito = $_SESSION["exito"] ?? null;
$mostrarFormPass = $_SESSION["mostrar_pass"] ?? false;

unset($_SESSION["errores"], $_SESSION["exito"], $_SESSION["mostrar_pass"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2 class="text-center mb-4">Recuperar contraseña</h2>

    <!-- FORMULARIO 1: INGRESAR EMAIL -->
    <?php if (!$mostrarFormPass): ?>
        <form action="procesar_recuperar.php" method="POST" class="card p-4 shadow-sm">

            <div class="mb-3">
                <label class="form-label">Ingresá tu email</label>
                <input type="email" name="email" class="form-control" required>

                <!-- Mostrar error -->
                <?php if (!empty($errores["email"])): ?>
                    <p class="text-danger mt-1"><?= $errores["email"] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" name="verificar_email" class="btn btn-primary w-100">
                Verificar email
            </button>

        </form>
    <?php endif; ?>


    <!-- FORMULARIO 2: CAMBIO DE CONTRASEÑA -->
    <?php if ($mostrarFormPass): ?>
        <form action="procesar_recuperar.php" method="POST" class="card p-4 shadow-sm mt-4">

            <div class="mb-3">
                <label class="form-label">Nueva contraseña</label>
                <input type="password" name="password" class="form-control" required>

                <?php if (!empty($errores["password"])): ?>
                    <p class="text-danger mt-1"><?= $errores["password"] ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Repetir contraseña</label>
                <input type="password" name="password2" class="form-control" required>

                <?php if (!empty($errores["password2"])): ?>
                    <p class="text-danger mt-1"><?= $errores["password2"] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" name="actualizar_pass" class="btn btn-success w-100">
                Actualizar contraseña
            </button>

        </form>
    <?php endif; ?>

</div>


<!-- MODAL DE ÉXITO -->
<?php if ($mensajeExito): ?>
<div class="modal fade show" style="display:block; background:rgba(0,0,0,0.5);" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Contraseña actualizada</h5>
      </div>

      <div class="modal-body">
        <p><?= $mensajeExito ?></p>
      </div>

      <div class="modal-footer">
        <a href="login.php" class="btn btn-primary">Ir a iniciar sesión</a>
      </div>

    </div>
  </div>
</div>
<?php endif; ?>

</body>
</html>
