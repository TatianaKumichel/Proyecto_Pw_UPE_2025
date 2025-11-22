<?php
$id_juego = isset($_GET['id_juego']) ? intval($_GET['id_juego']) : 0;
if ($id_juego <= 0) {
  die("Juego no especificado.");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php require "./inc/head.php"; ?>
  <link rel="stylesheet" href="./css/detalle.css" />
  <script>
    const ID_JUEGO = <?= $id_juego ?>;
  </script>
  <script src="./js/detalle.js" defer></script>
  <script src="./js/detalle-reportar-modal.js" defer></script>
</head>

<body>
  <header>
    <?php require "./inc/menu.php"; ?>
  </header>

  <main class="container my-4">
    <!-- Detalle del juego -->
    <div id="contenedor-detalle" class="row">

      <!-- Imagenes del juego -->
      <div class="col-12 col-md-6">
        <!-- Carousel -->
        <div id="carousel-container" class="d-none">
          <div id="carouselJuego" class="carousel slide mb-2" data-bs-ride="carousel">
            <div id="carousel-inner" class="carousel-inner">
              <!-- imagenes cargadas dinámicamente -->
            </div>
            <button class="carousel-control-prev d-none" id="carousel-prev" type="button"
              data-bs-target="#carouselJuego" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next d-none" id="carousel-next" type="button"
              data-bs-target="#carouselJuego" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
            </button>
          </div>
        </div>

        <!-- si solo hay una imagen -->
        <img id="single-image" src="" class="img-fluid rounded mb-2 game-cover-image d-none" alt="">
      </div>

      <!-- datos del juego -->
      <div class="col-12 col-md-6">
        <h2 id="titulo" class="mb-3"></h2>

        <div class="mb-3">
          <h5><i class="bi bi-info-circle"></i> Descripción:</h5>
          <p id="descripcion" class="text-muted"></p>
        </div>

        <div class="mb-2">
          <strong><i class="bi bi-building"></i> Empresa:</strong>
          <span id="empresa" class="text-muted"></span>
        </div>

        <div id="contenedor-plataformas" class="mb-2 d-none">
          <strong><i class="bi bi-display"></i> Plataformas:</strong>
          <span id="plataformas" class="text-muted"></span>
        </div>

        <div id="contenedor-generos" class="mb-2 d-none">
          <strong><i class="bi bi-tags"></i> Géneros:</strong>
          <span id="generos" class="text-muted"></span>
        </div>

        <div id="contenedor-lanzamiento" class="mb-3 d-none">
          <strong><i class="bi bi-calendar"></i> Lanzamiento:</strong>
          <span id="lanzamiento" class="text-muted"></span>
        </div>

        <hr>

        <!-- Para usuarios logueados -->
        <div id="logged-user-section" class="d-none">

          <div class="mb-3">
            <button id="btn-favorito" class="btn btn-outline-danger">
              <span id="texto-favorito">
                <i class="bi bi-heart"></i> Marcar como favorito
              </span>
            </button>
          </div>

          <!-- Calificar -->
          <div class="mb-3">
            <h5><i class="bi bi-star"></i> Calificar este juego:</h5>
            <div id="calificacion" class="fs-4">
              <i class="bi bi-star estrella star-rating" data-valor="1"></i>
              <i class="bi bi-star estrella star-rating" data-valor="2"></i>
              <i class="bi bi-star estrella star-rating" data-valor="3"></i>
              <i class="bi bi-star estrella star-rating" data-valor="4"></i>
              <i class="bi bi-star estrella star-rating" data-valor="5"></i>
            </div>
          </div>
        </div>

        <!-- Sección para usuarios no logueados -->
        <div id="guest-section" class="alert alert-info d-none">
          <i class="bi bi-info-circle"></i>
          <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Inicia sesión</a>
          para marcar como favorito, calificar y comentar.
        </div>
      </div>
    </div>

    <!-- Sección de comentarios -->
    <section id="comentarios" class="mt-4"></section>
  </main>

  <footer class="bg-dark text-white mt-4 pt-3 pb-2">
    <?php require "./inc/footer.php"; ?>
  </footer>

  <!-- Modal para reportar comentario -->
  <div class="modal fade" id="modalReportarComentario" tabindex="-1" aria-labelledby="modalReportarLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalReportarLabel">
            <i class="bi bi-flag-fill text-warning"></i> Reportar Comentario
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label for="motivoReporte" class="form-label"></label>
            <textarea class="form-control" id="motivoReporte" rows="4" maxlength="255" placeholder="Describe el motivo."
              required></textarea>
            <div class="form-text">
              <span id="contadorReporte">0</span>/255 caracteres
            </div>
          </div>
          <div class="alert alert-info mb-0">
            <small>
              <i class="bi bi-info-circle"></i>
              Tu reporte será revisado por un moderador.
            </small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cancelar
          </button>
          <button type="button" class="btn btn-warning" id="btnConfirmarReporte">
            <i class="bi bi-flag-fill"></i> Enviar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Agregar Comentario -->
  <div class="modal fade" id="modalAgregarComentario" tabindex="-1" aria-labelledby="modalAgregarComentarioLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalAgregarComentarioLabel">
            <i class="bi bi-chat-dots"></i> Agregar Comentario
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formAgregarComentario">
            <div class="mb-3">
              <label for="textoNuevoComentario" class="form-label">
              </label>
              <textarea id="textoNuevoComentario" class="form-control" rows="5" maxlength="500"
                placeholder="Escribe tu comentario aquí..." required></textarea>
              <div class="form-text">
                <span id="contadorNuevo">0</span>/500 caracteres
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x"></i> Cancelar
          </button>
          <button type="button" class="btn btn-primary" id="btnPublicarComentario">
            <i class="bi bi-send"></i> Publicar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar Comentario -->
  <div class="modal fade" id="modalEditarComentario" tabindex="-1" aria-labelledby="modalEditarComentarioLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title" id="modalEditarComentarioLabel">
            <i class="bi bi-pencil"></i> Editar Comentario
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formEditarComentario">
            <input type="hidden" id="idComentarioEditar" />
            <div class="mb-3">
              <label for="textoEditarComentario" class="form-label">
              </label>
              <textarea id="textoEditarComentario" class="form-control" rows="5" maxlength="500" required></textarea>
              <div class="form-text">
                <span id="contadorEditar">0</span>/500 caracteres
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x"></i> Cancelar
          </button>
          <button type="button" class="btn btn-warning" id="btnGuardarEdicion">
            <i class="bi bi-check"></i> Guardar Cambios
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Eliminar Comentario -->
  <div class="modal fade" id="modalEliminarComentario" tabindex="-1" aria-labelledby="modalEliminarComentarioLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="modalEliminarComentarioLabel">
            <i class="bi bi-exclamation-triangle"></i> Eliminar Comentario
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idComentarioEliminar" />
          <p class="mb-2">¿Estás seguro de que deseas eliminar este comentario?</p>
          <div class="card bg-light">
            <div class="card-body">
              <p class="mb-0 mt-2" id="previsualizacionEliminar"></p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x"></i> Cancelar
          </button>
          <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">
            <i class="bi bi-trash"></i> Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>