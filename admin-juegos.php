<?php
// Proteger página - requiere permiso de gestionar géneros
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
    <h1 class="mb-4 text-center">📋 Lista de Juegos</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="h4">Gestión de Juegos</h2>
      <button id="btnAgregarJuego" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i> Agregar Juego
      </button>
    </div>

    <!-- FORMULARIO PARA NUEVO JUEGO -->
    <form id="formJuego" class="d-none mb-4" enctype="multipart/form-data">
      <div class="row g-2 align-items-end">

        <div class="col-12 ">
          <label for="imagenJuego" class="form-label">Imagen Portada</label>
          <input type="file" id="imagenJuego" name="imagen" class="form-control" accept="image/*" />
        </div>

        <!-- PREVIEW DE PORTADA -->
        <div id="previewPortada" class="my-2 d-flex gap-2 flex-wrap"></div>


        <div class="col-12">
          <label for="imagenesExtra" class="form-label">Imágenes adicionales</label>
          <input type="file" class="form-control" id="imagenesExtra" name="imagenesExtra[]" multiple accept="image/*" />
          <div class="form-text">Podés seleccionar varias imágenes.</div>
        </div>

        <!-- IMÁGENES YA EXISTENTES DEL JUEGO -->
        <div class="col-12 ">
          <label class="form-label">Imágenes existentes</label>
          <div id="imagenesExistentes" class="d-flex flex-wrap gap-2"></div>
        </div>

        <!-- PREVIEW DE IMÁGENES EXTRA -->
        <div id="imagenesExistentes" class="my-2 d-flex gap-2 flex-wrap"></div>


        <div class="col-md-4">
          <label for="nombreJuego" class="form-label">Nombre</label>
          <input type="text" id="nombreJuego" name="titulo" class="form-control" placeholder="Nombre del juego"
            required />
        </div>

        <div class="col-md-6">
          <label for="descripcionJuego" class="form-label">Descripción</label>
          <textarea id="descripcionJuego" name="descripcion" class="form-control" rows="2"
            placeholder="Breve descripción..." required></textarea>
        </div>


        <div class="col-12">
          <label class="form-label">Plataformas</label>
          <select id="selectPlataformas" multiple class="form-select"></select>
        </div>

        <div class="col-12 mt-3">
          <label class="form-label">Géneros</label>
          <select id="selectGeneros" multiple class="form-select"></select>
        </div>



        <div class="col-12">
          <label for="empresaJuego" class="form-label">Empresa</label>
          <select id="selectEmpresa" class="form-select" required></select>


        </div>

        <div class="col-md-3">
          <label for="fechaJuego" class="form-label">Fecha</label>
          <input type="date" id="fechaJuego" name="fecha" class="form-control" required />
        </div>

        <div class="col-12 text-end mt-2">
          <button type="submit" class="btn btn-success">💾 Guardar</button>
          <button type="button" id="cancelarJuego" class="btn btn-secondary">
            Cancelar
          </button>
        </div>

      </div>
    </form>


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
          <tr>
            <td class="game-img">
              <img src="./img/apex1.jpg" alt="Juego" class="img-thumbnail" />
            </td>
            <td>Halo Infinite</td>
            <td>Shooter futurista en mundo semiabierto</td>
            <td>Xbox, PC</td>
            <td>Shooter</td>
            <td>343 Industries</td>
            <td>2021-12-08</td>
            <td>
              <div class="d-flex justify-content-center gap-1">
                <button class="btn btn-outline-warning btn-sm btn-editar" data-bs-toggle="tooltip" title="Editar">
                  <i class="bi bi-pencil-square"></i>
                  <span class="d-none d-sm-inline"></span>
                </button>
                <button class="btn btn-outline-danger btn-sm btn-eliminar" data-bs-toggle="tooltip" title="Eliminar">
                  <i class="bi bi-trash"></i>
                  <span class="d-none d-sm-inline"></span>
                </button>
                <button class="btn btn-outline-success btn-sm btn-publicar" data-bs-toggle="tooltip" title="Publicar">
                  <i class="bi bi-check-circle"></i>
                  <span class="d-none d-sm-inline"></span>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
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