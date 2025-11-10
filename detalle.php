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
    <div id="contenedor-detalle" class="row"></div>
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
          <p class="text-muted">Por favor, indica el motivo por el cual deseas reportar este comentario:</p>
          <div class="mb-3">
            <label for="motivoReporte" class="form-label">Motivo del reporte</label>
            <textarea class="form-control" id="motivoReporte" rows="4" maxlength="255"
              placeholder="Describe el motivo del reporte (máximo 255 caracteres)" required></textarea>
            <div class="form-text">
              <span id="contadorReporte">0</span>/255 caracteres
            </div>
          </div>
          <div class="alert alert-info mb-0">
            <small>
              <i class="bi bi-info-circle"></i>
              Tu reporte será revisado por un moderador. Los reportes falsos pueden resultar en sanciones.
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
</body>

</html>