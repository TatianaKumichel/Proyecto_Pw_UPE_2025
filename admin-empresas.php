<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  require "./inc/head.php";
  ?>
  <link rel="stylesheet" href="./css/admin-empresas.css" />
  <script defer src="./js/admin-empresas.js"></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
  <header>
    <?php
    require "./inc/menu.php";
    ?>
  </header>

  <div class=" container py-4 ">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">

      <div class="d-flex align-items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-building2-icon lucide-building-2">
          <path d="M10 12h4" />
          <path d="M10 8h4" />
          <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
          <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
          <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
        </svg>

        <div>
          <h2 class="fw-bold mb-0  titulo">Empresas</h2>
          <span class="text-secondary small">Gestiona las empresas del sitio</span>
        </div>
      </div>

      <div class="d-flex align-items-start align-items-md-center mt-3 mt-md-0">
        <button id="btnAgregarEmpresa" class="btn btn-primary py-2 px-4">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-plus">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
          </svg>
          <span class="ms-2 small h6">Nueva Empresa</span>
        </button>
      </div>

    </div>


    <!-- tabla de empresas -->
    <div id="cardEmpresas" class="card p-3">
      <table class="table align-middle">
        <thead>
          <tr>
            <th class="text-dark">Empresa</th>
            <th class="text-dark">Sitio web</th>
            <th> </th>
          </tr>
        </thead>
        <tbody id="tablaEmpresas">

        </tbody>
      </table>

    </div>
  </div>

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