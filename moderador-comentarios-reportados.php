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
          <option value="todos">Todos los estados</option>
          <option value="pendiente">Pendientes</option>
          <option value="resuelto">Resueltos</option>
        </select>
      </div>

      <div class="card mb-4 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
              <h6 class="mb-0">Reporte #1</h6>
              <span class="badge badge-pendiente ms-2">pendiente</span>
            </div>
          </div>

          <p class="mt-2 mb-1 text-muted">
            Reportado por: <strong>user123</strong> · Motivo:
            <strong>Contenido ofensivo</strong>
          </p>

          <div class="comment-bloque mt-3 mb-2">
            <strong>gamer456</strong>
            <p class="mb-1">
              Este juego es terrible, no lo recomiendo para nada.
            </p>
            <small class="text-muted">en Cyberpunk 2077</small>
          </div>

          <div class="d-flex gap-2 mt-3">
            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar">
              <i class="bi bi-trash me-1"></i> Eliminar Comentario
            </button>
            <button class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#modalRestringir">
              <i class="bi bi-person-x-fill"></i> Restringir Usuario
            </button>
            <button class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-x-circle me-1"></i> Descartar Reporte
            </button>
          </div>
        </div>
      </div>

      <div class="card mb-4 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
              <h6 class="mb-0">Reporte #2</h6>
              <span class="badge badge-pendiente ms-2">pendiente</span>
            </div>
          </div>

          <p class="mt-2 mb-1 text-muted">
            Reportado por: <strong>akira1</strong> · Motivo:
            <strong>Spam</strong>
          </p>

          <div class="comment-bloque mt-3 mb-2">
            <strong>spammer</strong>
            <p class="mb-1">Visita mi canal de YouTube para más reviews!!!</p>
            <small class="text-muted">en The Witcher 3</small>
          </div>

          <div class="d-flex gap-2 mt-3">
            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar">
              <i class="bi bi-trash me-1"></i> Eliminar Comentario
            </button>
            <button class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#modalRestringir">
              <i class="bi bi-person-x-fill"></i> Restringir Usuario
            </button>
            <button class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-x-circle me-1"></i> Descartar Reporte
            </button>
          </div>
        </div>
      </div>
      <div class="card mb-4 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
              <h6 class="mb-0">Reporte #3</h6>
              <span class="badge badge-resuelto ms-2">resuelto</span>
            </div>
          </div>

          <p class="mt-2 mb-1 text-muted">
            Reportado por: <strong>user789</strong> · Motivo:
            <strong>Información falsa</strong>
          </p>

          <div class="comment-bloque mt-3 mb-2">
            <strong>desinformador1</strong>
            <p class="mb-1">Este juego tiene virus, no lo descarguen.</p>
            <small class="text-muted">en League of Legends </small>
          </div>
        </div>
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