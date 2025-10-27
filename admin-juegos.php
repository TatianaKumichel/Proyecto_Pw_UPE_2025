<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lista de Juegos</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./css/admin-juegos.css" />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      defer
    ></script>
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
          ➕ Agregar Juego
        </button>
      </div>

      <!-- FORMULARIO PARA NUEVO JUEGO (oculto al inicio) -->
      <form id="formJuego" class="d-none mb-4">
        <div class="row g-2 align-items-end">
          <div class="col-md-2 text-center">
            <label for="imagenJuego" class="form-label">Imagen</label>
            <input
              type="file"
              id="imagenJuego"
              class="form-control"
              accept="image/*"
            />
          </div>
          <div class="col-md-4">
            <label for="nombreJuego" class="form-label">Nombre</label>
            <input
              type="text"
              id="nombreJuego"
              class="form-control"
              placeholder="Nombre del juego"
              required
            />
          </div>
          <div class="col-md-6">
            <label for="descripcionJuego" class="form-label">Descripción</label>
            <textarea
              id="descripcionJuego"
              class="form-control"
              rows="2"
              placeholder="Breve descripción..."
              required
            ></textarea>
          </div>
          <div class="col-md-3">
            <label for="plataformaJuego" class="form-label">Plataforma</label>
            <input
              type="text"
              id="plataformaJuego"
              class="form-control"
              placeholder="PC, PS, XBOX..."
              required
            />
          </div>
          <div class="col-md-3">
            <label for="generoJuego" class="form-label">Género</label>
            <input
              type="text"
              id="generoJuego"
              class="form-control"
              placeholder="Acción, Aventura..."
              required
            />
          </div>
          <div class="col-md-3">
            <label for="empresaJuego" class="form-label">Empresa</label>
            <input
              type="text"
              id="empresaJuego"
              class="form-control"
              placeholder="Respawn, EA..."
              required
            />
          </div>
          <div class="col-md-3">
            <label for="fechaJuego" class="form-label">Fecha</label>
            <input type="date" id="fechaJuego" class="form-control" required />
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
              <th>Imagen</th>
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
                  <button
                    class="btn btn-outline-warning btn-sm btn-editar"
                    data-bs-toggle="tooltip"
                    title="Editar"
                  >
                    <i class="bi bi-pencil-square"></i>
                    <span class="d-none d-sm-inline"></span>
                  </button>
                  <button
                    class="btn btn-outline-danger btn-sm btn-eliminar"
                    data-bs-toggle="tooltip"
                    title="Eliminar"
                  >
                    <i class="bi bi-trash"></i>
                    <span class="d-none d-sm-inline"></span>
                  </button>
                  <button
                    class="btn btn-outline-success btn-sm btn-publicar"
                    data-bs-toggle="tooltip"
                    title="Publicar"
                  >
                    <i class="bi bi-check-circle"></i>
                    <span class="d-none d-sm-inline"></span>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>

    <footer class="bg-dark text-white mt-4 pt-3 pb-2">
      <?php
      require "./inc/footer.php";
    ?>
    </footer>

  </body>
</html>
