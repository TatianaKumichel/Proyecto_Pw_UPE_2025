<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  require "./inc/head.php";
  ?>
  <link rel="stylesheet" href="./css/moderador-comentarios-reportados.css" />
  <script src="./js/moderador-comentarios-reportados.js" defer></script>
</head>

<body>
  <header>
    <?php
    require "./inc/menu.php";
    ?>
  </header>
  <main class="container my-4">
    <div class="container py-5">
      <h2 class="fw-bold">Comentarios Reportados</h2>
      <p class="text-muted">
        Gestiona los comentarios reportados por la comunidad
      </p>

      <div class="mb-4">
        <select id="filtroEstado" class="form-select custom-select w-auto">
          <option value="pendiente">Pendientes</option>
          <option value="resuelto">Resueltos</option>
          <option value="todos">Todos los estados</option>
        </select>
      </div>
      <div id="reportesContainer"></div>

    </div>

    </div>
  </main>

  <footer class="bg-dark text-white mt-4 pt-3 pb-2">
    <?php
    require "./inc/footer.php";
    ?>
  </footer>

  <!--modal de confirmacion al seleccionar eliminar comentario-->
  <div class="modal fade" id="modalEliminar">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmar eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          ¿Seguro que deseas eliminar este comentario?
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button class="btn btn-danger" id="confirmaEliminar" data-bs-dismiss="modal">
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- modal para confirmar restringir -->
  <div class="modal fade" id="modalRestringir">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmar restricción</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          ¿Seguro que deseas restringir a este usuario?
          Al confirmar el usuario sera restringido y el comentario Eliminado
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button class="btn btn-dark" id="confirmaRestringir" data-bs-dismiss="modal">
            Restringir
          </button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>