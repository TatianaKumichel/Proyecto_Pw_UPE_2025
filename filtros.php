<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  require "./inc/head.php";
  ?>
  <script src="./js/filtros.js" defer></script>
  <link rel="stylesheet" href="./css/style_filtros.css" />
</head>

<body class="bg-light">
  <header>
    <?php
    require "./inc/menu.php";
    ?>
  </header>
  <main class="container my-4">
    <div class="container py-5">
      <h2 class="mb-4 text-center">
        <i class="bi bi-collection"></i> Catálogo de Videojuegos
      </h2>

      <!-- Filtros -->
      <div class="row mb-4">
        <div class="col-md-3 mb-2">
          <label for="filtroNombre" class="form-label">
            <i class="bi bi-search"></i> Buscar por nombre
          </label>
          <input type="text" id="filtroNombre" class="form-control" placeholder="Escribe el nombre del juego..." />
        </div>
        <div class="col-md-3 mb-2">
          <label for="filtroGenero" class="form-label">
            <i class="bi bi-tags"></i> Género
          </label>
          <select id="filtroGenero" class="form-select">
            <option value="">Todos los géneros</option>
            <!-- Se cargan dinámicamente desde la BD -->
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label for="filtroPlataforma" class="form-label">
            <i class="bi bi-display"></i> Plataforma
          </label>
          <select id="filtroPlataforma" class="form-select">
            <option value="">Todas las plataformas</option>
            <!-- Se cargan dinámicamente desde la BD -->
          </select>
        </div>

        <div class="col-md-3 mb-2">
          <label for="filtroEmpresa" class="form-label">
            <i class="bi bi-display"></i> Empresa
          </label>
          <select id="filtroEmpresa" class="form-select">
            <option value="">Todas las Empresas</option>
            <!-- Se cargan dinámicamente desde la BD -->
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label class="form-label">
            <i class="bi bi-star-fill"></i> Ver
          </label>
          <select id="filtroDestacados" class="form-select">
            <option value="todos">Todos</option>
            <option value="destacados">Mejor Calificados</option>
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label for="filtroEstado" class="form-label">
            <i class="bi bi-clock-history"></i> Estado
          </label>
          <select id="filtroEstado" class="form-select">
            <option value="">Todos</option>
            <option value="disponible">Disponible</option>
            <option value="proximamente">Próximamente</option>
          </select>
        </div>
      </div>


      <!-- Resultados -->
      <div class="row" id="contenedorResultados">
        <!-- Los juegos se cargan dinámicamente aquí -->
        <div class="col-12 text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
          <p class="mt-3 text-muted">Cargando videojuegos...</p>
        </div>
      </div>
    </div>
  </main>
  <footer class="bg-dark text-white mt-4 pt-3 pb-2">
    <?php require "./inc/footer.php"; ?>
  </footer>
</body>

</html>