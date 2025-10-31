<!DOCTYPE html>
<html lang="es">

<head>
  <?php require "./inc/head.php"; ?>
  <link rel="stylesheet" href="css/admin-generos.css" />
  <script src="./js/admin-generos.js" defer></script>
</head>

<body>
  <header>
    <?php require "./inc/menu.php"; ?>
  </header>

  <main class="container my-4">
    <h2 class="mb-4">Gestión de Géneros</h2>

    <div id="divErroresGenerales" class="alert alert-danger d-none" role="alert"></div>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="h5">Panel de géneros</h3>
      <button id="btnAgregarGenero" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Género
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
        <tbody id="tablaGeneros">
          <!-- Se cargan los géneros vía JS -->
        </tbody>
      </table>
    </div>
  </main>

  <footer class="bg-dark text-white mt-4 pt-3 pb-2">
    <?php require "./inc/footer.php"; ?>
  </footer>

  <!-- Modal Agregar/Editar Género -->
  <div class="modal fade" id="modalGenero" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalGeneroTitle">Nuevo Género</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form id="formGenero">
          <div class="modal-body">
            <div class="mb-3">
              <label for="nombreGeneroModal" class="form-label">Nombre del género</label>
              <input type="text" class="form-control" id="nombreGeneroModal" placeholder="Ej: Acción, Aventura..."
                required>
              <div class="invalid-feedback" id="errorNombreModal"></div>
            </div>
            <input type="hidden" id="idGeneroModal" value="">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="btnGuardarGenero">💾 Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Confirmar Eliminación -->
  <div class="modal fade" id="modalConfirmarEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bi bi-exclamation-triangle-fill text-warning"></i> Confirmar eliminación
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          ¿Estás seguro de eliminar este género? Esta acción no se puede deshacer.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" id="btnConfirmarEliminarGenero">Eliminar</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>