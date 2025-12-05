<?php
// Proteger página - requiere permiso de gestionar géneros
require_once './inc/auth.php';
requierePermiso('gestionar_generos');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php require "./inc/head.php"; ?>
  <link rel="stylesheet" href="./css/admin.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <script src="./js/admin-generos.js" defer></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
  <header>
    <?php require "./inc/menu.php"; ?>
  </header>



  <main class="container my-4 flex-fill">
    <div class="d-flex justify-content-center align-items-center gap-2 mb-4">

      <h1 class="m-0 fw-bold">Gestión de Géneros</h1>
    </div>




    <!-- Botón agregar -->
    <div class="d-flex justify-content-end mb-3">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalGenero"
        id="btnNuevoGenero">
        <i class="bi bi-plus-lg me-2"></i> Nuevo Género
      </button>
    </div>

    <!-- Tabla -->
    <div class="table-responsive shadow rounded">
      <table class="table table-striped table-hover align-middle text-center mb-0">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaGeneros">
          <tr>
            <td colspan="3" class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
              <p class="mt-2 text-muted">Cargando géneros...</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>

  <footer class="bg-dark text-white mt-4 pt-3 pb-2">
    <?php require "./inc/footer.php"; ?>
  </footer>

  <!-- Modal de Alta/Edición -->
  <div class="modal fade" id="modalGenero" tabindex="-1" aria-labelledby="modalGeneroLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="formGenero" novalidate>
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalGeneroLabel">
              <i class="bi bi-plus-circle"></i> Nuevo Género
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
              aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="idGenero" name="id_genero">
            <div class="mb-3">
              <label for="nombre" class="form-label">
                <i class="bi bi-tag-fill"></i> Nombre del género
              </label>
              <input type="text" class="form-control" id="nombre" name="nombre" required minlength="3"
                placeholder="Ej: Acción, Aventura, RPG...">
              <div class="invalid-feedback">El nombre debe tener al menos 3 caracteres.</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-primary" id="btnGuardarGenero">
              <i class="bi bi-check-circle"></i> Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Confirmar Eliminación -->
  <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">
            <i class="bi bi-trash-fill"></i> Eliminar Género
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-3">
            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3rem;"></i>
          </div>
          <p class="text-center mb-2">¿Estás seguro de eliminar este género?</p>
          <p class="text-center text-muted small" id="modalEliminarNombre"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cancelar
          </button>
          <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">
            <i class="bi bi-trash-fill"></i> Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>