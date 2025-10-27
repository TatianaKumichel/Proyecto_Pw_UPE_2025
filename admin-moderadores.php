<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  require "./inc/head.php";
  ?>
  <link rel="stylesheet" href="./css/admin-moderadores.css" />
  <script src="./js/admin-moderadores.js" defer></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
  <header>
    <?php
    require "./inc/menu.php";
    ?>
  </header>

  <!-- Espaciador -->
  <div style="margin-top: 70px"></div>

  <!-- Panel de Moderadores -->
  <main class="container my-4 flex-fill">
    <h1 class="mb-4 text-center">📋 Gestión de Moderadores</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="h4">Panel de Moderadores</h2>
      <button id="btnAgregarModerador" class="btn btn-primary">
        ➕ Agregar Moderador
      </button>
    </div>

    <!-- FORMULARIO PARA NUEVO MODERADOR (oculto al inicio) -->
    <form id="formModerador" class="d-none mb-4">
      <div class="row g-2 align-items-end">
        <div class="col-md-4">
          <label for="nombreModerador" class="form-label">Nombre de usuario</label>
          <input type="text" id="nombreModerador" class="form-control" placeholder="Ej: ModJuan" required />
        </div>
        <div class="col-md-4">
          <label for="emailModerador" class="form-label">Correo electrónico</label>
          <input type="email" id="emailModerador" class="form-control" placeholder="juan@example.com" required />
        </div>
        <div class="col-md-4 text-end">
          <button type="submit" class="btn btn-success">💾 Guardar</button>
          <button type="button" id="cancelarModerador" class="btn btn-secondary">
            Cancelar
          </button>
        </div>
      </div>
    </form>

    <!-- Tabla de moderadores -->
    <div class="table-responsive shadow rounded">
      <table class="table table-striped align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Email</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaModeradores">
          <tr>
            <td>1</td>
            <td>ModJuan</td>
            <td>juan@example.com</td>
            <td>
              <button class="btn btn-sm btn-warning btn-editar">
                Editar
              </button>
              <button class="btn btn-sm btn-danger btn-eliminar">
                Eliminar
              </button>
              <button class="btn btn-sm btn-secondary btn-permiso">
                Permiso OFF
              </button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>ModAna</td>
            <td>ana@example.com</td>
            <td>
              <button class="btn btn-sm btn-warning btn-editar">
                Editar
              </button>
              <button class="btn btn-sm btn-danger btn-eliminar">
                Eliminar
              </button>
              <button class="btn btn-sm btn-success btn-permiso">
                Permiso ON
              </button>
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