<?php
// Proteger página - requiere permiso de gestionar faqs
require_once './inc/auth.php';
requierePermiso('gestionar_faq');
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  require "./inc/head.php";
  ?>
  <script src="./js/moderador-script-gestion-faq.js" defer></script>
</head>

<body>
  <header>
    <?php
    require "./inc/menu.php";
    ?>
  </header>
  <main class="container my-4">
    <div class="container py-4">
      <div class="row">
        <div class="col-md-9 col-lg-10 p-4">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h3 class="mb-0">Gestión de Preguntas Frecuentes</h3>
              <p class="text-muted mb-0">
                Administra las preguntas frecuentes del sitio
              </p>
            </div>
            <button class="btn btn-dark" id="btnNuevaFaq">
              <i class="bi bi-plus-lg me-1"></i>Nueva FAQ
            </button>
          </div>

          <!--FAQS cargadas  con ajax-->
          <div id="contenedor-faqs"></div>




        </div>
      </div>
    </div>
  </main>

  <footer class="bg-dark text-white mt-4 pt-3 pb-2">
    <?php
    require "./inc/footer.php";
    ?>
  </footer>

  <!-- modal crear/editar-->

  <div class="modal fade" id="modalNuevaFAQ">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nueva FAQ</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <form id="formNuevaFAQ">
          <div class="modal-body">
            <p class="text-muted" id="descripcionForm">Crea una nueva pregunta frecuente</p>
            <div class="mb-3">
              <label for="pregunta">Pregunta</label>
              <input type="text" id="pregunta" class="form-control" placeholder="Escribe la pregunta..." />
              <div class="valid-feedback"></div>
              <div id="ErrorPregunta" class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
              <label for="respuesta">Respuesta</label>
              <textarea id="respuesta" class="form-control" rows="3" placeholder="Escribe la respuesta..."></textarea>
              <div class="valid-feedback"></div>
              <div id="ErrorRespuesta" class="invalid-feedback"></div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
              Cancelar
            </button>
            <button type="submit" class="btn btn-dark" id="btnCrearFaq">
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--modal para confirmar eliminacion  -->
  <div class="modal fade" id="modalEliminarFAQ">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Eliminar FAQ</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          ¿Estás seguro de que deseas eliminar esta FAQ? Esta acción no se
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

  <?php
  include './componentes/modal_exito.php';
  include './componentes/modal_error.php';
  ?>
</body>

</html>