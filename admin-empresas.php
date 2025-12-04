<?php
// Proteger página - requiere permiso de gestionar empresas
require_once './inc/auth.php';
requierePermiso('gestionar_empresas');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  require "./inc/head.php";
  ?>
  <link rel="stylesheet" href="./css/admin-moderadores.css" />
  <script defer src="./js/admin-empresas.js"></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
  <header>
    <?php
    require "./inc/menu.php";
    ?>
  </header>

  <main class="container my-4 flex-fill">


    <div class="d-flex justify-content-center">
      <div class="d-flex align-items-center gap-2 mb-4">

        <h1 class="fw-bold m-0">Gestión de Empresas</h1>
      </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
      <button id="btnAgregarEmpresa" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Agregar Empresa
      </button>
    </div>


    <div class="table-responsive shadow rounded">
      <table class="table table-striped align-middle text-center  mb-0">
        <thead class="table-dark">
          <th>Empresa</th>
          <th>Sitio web</th>
          <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaEmpresas"></tbody>
      </table>

    </div>
  </main>

  <div class="modal fade" id="modalNuevaEmpresa">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nueva Empresa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <form id="formEmpresa">
          <div class="modal-body">
            <p class="text-muted" id="descripcionForm">Ingresa una empresa desarrolladora</p>

            <div class="mb-3">
              <label for="nombreEmpresa" class="form-label">Nombre de la Empresa</label>
              <input type="text" id="nombreEmpresa" class="form-control" placeholder="Ej: Ubisoft, Nintendo..." />
              <div class="valid-feedback"></div>
              <div id="ErrornombreEmpresa" class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
              <label for="sitioWeb" class="form-label">Sitio Web</label>
              <input type="text" id="sitioWeb" class="form-control" placeholder="https://www.ubisoft.com/" />
              <div class="valid-feedback"></div>
              <div id="ErrorsitioWeb" class="invalid-feedback"></div>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-dark" id="btnGuardarEmpresa">Guardar</button>
          </div>

        </form>
      </div>
    </div>
  </div>


  <div class="modal fade" id="modalEliminarEmpresa">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Eliminar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          ¿Estás seguro de que deseas eliminar esta Empresa? Esta acción no se
          puede deshacer.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">
            Eliminar
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
  <?php
  include './componentes/modal_exito.php';
  include './componentes/modal_error.php';
  ?>
</body>

</html>