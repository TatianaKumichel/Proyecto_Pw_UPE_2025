<?php
// Proteger página - requiere permiso de gestionar moderadores
require_once './inc/auth.php';
requierePermiso('gestionar_moderadores');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  require "./inc/head.php";
  ?>
  <link rel="stylesheet" href="./css/admin-moderadores.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <script src="./js/admin-moderadores.js" defer></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
  <header>
    <?php
    require "./inc/menu.php";
    ?>
  </header>

  <!-- Espaciador -->
  <div class="navbar-spacer"></div>

  <!-- Panel de Moderadores -->
  <main class="container my-4 flex-fill">
    <h1 class="mb-4 text-center">Gestión de Moderadores</h1>

    <div class="alert alert-info">
      <i class="bi bi-info-circle"></i>
      Asignar o quitar el rol de moderador a los usuarios registrados.
    </div>

    <!-- Usuarios -->
    <div class="table-responsive shadow rounded">
      <table class="table table-striped table-hover align-middle text-center mb-0">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaModeradores">
          <tr>
            <td colspan="5" class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
              <p class="mt-2 text-muted">Cargando usuarios...</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </main>

  <!-- Modal de Confirmación -->
  <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalConfirmacionLabel">
            <i class="bi bi-question-circle text-warning"></i> Confirmar Acción
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modalConfirmacionMensaje">
          ¿Estás seguro de realizar esta acción?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cancelar
          </button>
          <button type="button" class="btn btn-primary" id="btnConfirmarAccion">
            <i class="bi bi-check-circle"></i> Confirmar
          </button>
        </div>
      </div>
    </div>
  </div>

  <footer class="bg-dark text-white mt-4 pt-3 pb-2">
    <?php
    require "./inc/footer.php";
    ?>
  </footer>

</body>

</html>