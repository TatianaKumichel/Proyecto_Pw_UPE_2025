<?php
// Proteger página - requiere permiso de gestionar juegos 
require_once './inc/auth.php';
requierePermiso('gestionar_juegos');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  require "./inc/head.php";
  ?>
  <link rel="stylesheet" href="./css/admin-juegos.css" />
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="./js/admin-juegos.js" defer></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
  <header>
    <?php
    require "./inc/menu.php";
    ?>
  </header>

  <!-- Espaciador -->
  <div style="margin-top: 70px"></div>

  <!-- Panel de Juegos -->
  <main class="container my-4 flex-fill">
    <h1 class="mb-4 text-center">Lista de Juegos</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="h4">Gestión de Juegos</h2>
      <button id="btnAgregarJuego" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i> Agregar Juego
      </button>
    </div>

    <!-- Tabla de juegos -->
    <div class="table-responsive shadow rounded">
      <table class="table table-striped align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>Portada</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Plataforma</th>
            <th>Género</th>
            <th>Empresa</th>
            <th>Lanzamiento</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tabla-juegos">
          <!-- Se llena dinámicamente -->
        </tbody>
      </table>
    </div>

    <!-- MODAL PARA ABM JUEGOS -->
    <div class="modal fade" id="juegoModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- modal-lg para más espacio -->
        <div class="modal-content">
          
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="juegoModalLabel">Nuevo Juego</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <form id="formJuegoModal" enctype="multipart/form-data">
              <div class="row g-2">

                <!-- COLUMNA IZQUIERDA: TEXTOS -->
                <div class="col-md-7">
                  
                  <div class="mb-3">
                    <label for="nombreJuego" class="form-label">Nombre</label>
                    <input type="text" id="nombreJuego" name="titulo" class="form-control" placeholder="Nombre del juego" required />
                    <div class="invalid-feedback"></div>
                  </div>

                  <div class="mb-3">
                    <label for="descripcionJuego" class="form-label">Descripción</label>
                    <textarea id="descripcionJuego" name="descripcion" class="form-control" rows="3" placeholder="Breve descripción..." required></textarea>
                    <div class="invalid-feedback"></div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="fechaJuego" class="form-label">Fecha Lanzamiento</label>
                      <input type="date" id="fechaJuego" name="fecha" class="form-control" />
                      <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="selectEmpresa" class="form-label">Empresa</label>
                      <select id="selectEmpresa" class="form-select" style="width: 100%;"></select>
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Plataformas</label>
                    <select id="selectPlataformas" multiple class="form-select" style="width: 100%;"></select>
                    <div class="invalid-feedback"></div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Géneros</label>
                    <select id="selectGeneros" multiple class="form-select" style="width: 100%;"></select>
                    <div class="invalid-feedback"></div>
                  </div>

                </div>

                <!-- COLUMNA DERECHA: IMÁGENES -->
                <div class="col-md-5">
                  
                  <!-- PORTADA -->
                  <div class="mb-3">
                    <label for="imagenJuego" class="form-label">Imagen Portada</label>
                    <input type="file" id="imagenJuego" name="imagen" class="form-control" accept="image/*" />
                    <div class="invalid-feedback"></div>
                    <!-- Preview Portada -->
                    <div id="previewPortada" class="mt-2 d-flex justify-content-center border rounded bg-light p-2" style="min-height: 100px;">
                      <span class="text-muted align-self-center small">Vista previa</span>
                    </div>
                  </div>

                  <!-- IMÁGENES EXTRA -->
                  <div class="mb-3">
                    <label for="imagenesExtra" class="form-label">Imágenes adicionales</label>
                    <input type="file" class="form-control" id="imagenesExtra" name="imagenesExtra[]" multiple accept="image/*" />
                    <div class="form-text small">Selecciona varias imágenes.</div>
                    <div class="invalid-feedback"></div>
                  </div>

                  <!-- CONTENEDOR IMÁGENES EXISTENTES (SOLO EDITAR) -->
                  <label class="form-label small fw-bold">Galería Actual</label>
                  <div id="containerImagenesExistentes" class="d-flex flex-wrap gap-2 border rounded p-2 bg-light" style="min-height: 80px;">
                    <!-- Se llena dinámicamente -->
                  </div>

                  <!-- PREVIEW NUEVAS IMÁGENES EXTRA -->
                  <div id="previewImagenesExtra" class="mt-2 d-flex gap-2 flex-wrap"></div>

                </div>

              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="formJuegoModal" class="btn btn-success">💾 Guardar</button>
          </div>

        </div>
      </div>
    </div>

    <!-- MODAL PARA MENSAJES -->
    <div class="modal fade" id="msgModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="msgModalTitle">Mensaje</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" id="msgModalBody">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmar acción</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            ¿Seguro que deseas eliminar este juego?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary " data-bs-dismiss="modal ">Cancelar</button>
            <button id="confirmDeleteBtn" type="button" class="btn btn-danger">Aceptar</button>
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

</body>

</html>