<!DOCTYPE html>
<html lang="es">
  <head>
    <?php
    require "./inc/head.php";
    ?>
    <link rel="stylesheet" href="./css/admin-empresas.css" />
    <script src="./js/admin-empresas.js" defer></script>
  </head>

  <body class="d-flex flex-column min-vh-100 bg-light">
    <header>
      <?php
      require "./inc/menu.php";
      ?>
    </header>

    <!-- Espaciador para navbar fija -->
    <div style="margin-top: 70px"></div>

    <!-- Main content -->
    <main class="container my-4 flex-fill">
      <h1 class="mb-4 text-center">📋 Gestión de Empresas</h1>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4">Panel de Empresas</h2>
        <button id="btnAgregarEmpresa" class="btn btn-primary">
          ➕ Agregar Empresa
        </button>
      </div>

      <!-- Formulario para nueva empresa -->
      <form id="formEmpresa" class="d-none mb-4">
        <div class="row g-2 align-items-end">
          <div class="col-md-6">
            <label for="nombreEmpresa" class="form-label"
              >Nombre de la Empresa</label
            >
            <input
              type="text"
              id="nombreEmpresa"
              class="form-control"
              placeholder="Ej: Ubisoft, Nintendo..."
              required
            />
          </div>
          <div class="col-md-6 text-end">
            <button type="submit" class="btn btn-success">💾 Guardar</button>
            <button
              type="button"
              id="cancelarEmpresa"
              class="btn btn-secondary"
            >
              Cancelar
            </button>
          </div>
        </div>
      </form>

      <!-- Tabla de empresas -->
      <div class="table-responsive shadow rounded">
        <table class="table table-striped align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Empresa</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tablaEmpresas">
            <tr>
              <td>1</td>
              <td>Empresa de ejemplo</td>
              <td>
                <div class="d-flex justify-content-center gap-1">
                  <button
                    class="btn btn-outline-warning btn-sm btn-editar"
                    data-bs-toggle="tooltip"
                    title="Editar"
                  >
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button
                    class="btn btn-outline-danger btn-sm btn-eliminar"
                    data-bs-toggle="tooltip"
                    title="Eliminar"
                  >
                    <i class="bi bi-trash"></i>
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
