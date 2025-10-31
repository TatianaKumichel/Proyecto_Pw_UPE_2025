<!DOCTYPE html>
<html lang="es">

<head>
  <?php require "./inc/head.php"; ?>
  <script src="./js/admin-generos.js" defer></script>
</head>

<body>
  <header>
    <?php require "./inc/menu.php"; ?>
  </header>

  <main class="container my-4">
    <h2 class="mb-4">Administrar Géneros</h2>

    <!-- Mensaje de error general -->
    <div id="divErroresGenerales" class="alert alert-danger d-none" role="alert"></div>

    <!-- Botón agregar -->
    <div class="mb-3">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalGenero"
        id="btnNuevoGenero">
        <i class="bi bi-plus-circle"></i> Nuevo Género
      </button>
    </div>

    <!-- Tabla -->
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaGeneros"></tbody>
      </table>
    </div>
  </main>

  <footer class="bg-dark text-white mt-4 pt-3 pb-2">
    <?php require "./inc/footer.php"; ?>
  </footer>

  <!-- Modal de Alta/Edición -->
  <div class="modal fade" id="modalGenero" tabindex="-1" aria-labelledby="modalGeneroLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="formGenero" novalidate>
          <div class="modal-header">
            <h5 class="modal-title" id="modalGeneroLabel">Nuevo Género</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="idGenero" name="id_genero">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre del género</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required minlength="3">
              <div class="invalid-feedback">El nombre debe tener al menos 3 caracteres.</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="btnGuardarGenero">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Confirmar Eliminación -->
  <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger"><i class="bi bi-trash-fill"></i> Eliminar Género</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">¿Estás seguro de eliminar este género?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>