<?php
// Proteger página - requiere permiso de gestionar géneros
require_once './inc/auth.php';
requierePermiso('gestionar_plataformas');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  require "./inc/head.php";
  ?>
  <link rel="stylesheet" href="./css/admin-moderadores.css" />
  <script src="./js/admin-plataforma.js" defer></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
  <header>
    <?php
    require "./inc/menu.php";
    ?>
  </header>


  <main class="container my-4 flex-fill">
    <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
      <h1 class="m-0 fw-bold">Gestión de Plataformas</h1>
    </div>

    <div class="d-flex justify-content-end mb-3">

      <button id="btnAgregarPlataforma" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i> Agregar Plataforma
      </button>
    </div>

    <div class="table-responsive shadow rounded">
      <table class="table table-striped align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaPlataformas">

        </tbody>
      </table>
    </div>
  </main>




  <div class="modal fade" id="plataformaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form id="formPlataformaModal">
          <div class="modal-body">
            <label class="form-label">Nombre de la Plataforma</label>
            <input type="text" id="modalNombrePlataforma" class="form-control">
            <div class="valid-feedback"></div>
            <div id="ErrorNombrePlataforma" class="invalid-feedback"></div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" id="btnGuardarPlataforma" class="btn btn-success">Guardar</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <!-- Modal Confirmación -->
  <div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Confirmar eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          ¿Seguro que deseas eliminar esta plataforma?
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="button" id="confirmYes" class="btn btn-danger">Eliminar</button>
        </div>
      </div>
    </div>
  </div>





  <footer class="bg-dark text-white mt-4 pt-3 pb-2">
    <?php
    require "./inc/footer.php";
    ?>
  </footer>
  <?php
  include './componentes/modal_exito.php';
  include './componentes/modal_error.php';
  ?>
</body>

</html>