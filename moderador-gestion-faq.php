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
        <div id="contenedor-faqs" class="col-md-9 col-lg-10 p-4">
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

          <div class="card faq-card mb-4 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">
                <i class="bi bi-question-circle me-1 text-primary"></i>¿Cómo
                puedo cambiar mi contraseña?
              </h5>
              <p class="card-text text-muted">
                Puedes cambiar tu contraseña desde la sección de configuración
                en tu perfil.
              </p>
              <div class="d-flex gap-2">
                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarFAQ">
                  <i class="bi bi-pencil-square"></i> Editar
                </button>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar">
                  <i class="bi bi-trash"></i>Eliminar
                </button>
              </div>
            </div>
          </div>
          <div class="card faq-card mb-4 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">
                <i class="bi bi-question-circle me-1 text-primary"></i>¿Cómo
                contacto con soporte?
              </h5>
              <p class="card-text text-muted">
                Puedes enviar un correo a soporte@ejemplo.com.
              </p>
              <div class="d-flex gap-2">
                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarFAQ">
                  <i class="bi bi-pencil-square"></i>Editar
                </button>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar">
                  <i class="bi bi-trash"></i>Eliminar
                </button>
              </div>
            </div>
          </div>

          <div class="card faq-card mb-4 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">
                <i class="bi bi-question-circle me-1 text-primary"></i>¿Cómo
                funciona el sistema de favoritos?
              </h5>
              <p class="card-text text-muted">
                Puedes agregar juegos a tus favoritos haciendo clic en el
                corazón en la tarjeta del juego. Luego puedes ver todos tus
                favoritos en tu perfil.
              </p>
              <div class="d-flex gap-2">
                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarFAQ">
                  <i class="bi bi-pencil-square"></i> Editar
                </button>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar">
                  <i class="bi bi-trash"></i>Eliminar
                </button>
              </div>
            </div>
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

  <!-- modal para crear una pregunta frecuente, en un futuro se utilizara tambien para editar
       y se mostrara la pregunta y respuesta actual-->

  <div class="modal fade" id="modalNuevaFAQ">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nueva FAQ</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <form id="formNuevaFAQ">
          <div class="modal-body">
            <p class="text-muted">Crea una nueva pregunta frecuente</p>
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
  <!--modal para confirmar eliminacion , todavia no se realiza -->
  <div class="modal fade" id="modalEliminar">
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
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>