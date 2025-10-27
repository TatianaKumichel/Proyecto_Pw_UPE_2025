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
      <h2 class="mb-4 text-center">Buscar Videojuegos</h2>

      <!-- Filtros -->
      <div class="row mb-4">
        <div class="col-md-4 mb-2">
          <input type="text" id="filtroNombre" class="form-control" placeholder="Buscar por nombre..." />
        </div>
        <div class="col-md-4 mb-2">
          <select id="filtroGenero" class="form-select">
            <option value="">Todos los géneros</option>
            <option value="Aventura">Aventura</option>
            <option value="RPG">RPG</option>
            <option value="Deportes">Deportes</option>
            <option value="Sandbox">Sandbox</option>
          </select>
        </div>
        <div class="col-md-4 mb-2">
          <select id="filtroPlataforma" class="form-select">
            <option value="">Todas las plataformas</option>
            <option value="PlayStation">PlayStation</option>
            <option value="PC">PC</option>
            <option value="Nintendo Switch">Nintendo Switch</option>
          </select>
        </div>
      </div>

      <!-- Resultados -->
      <div class="row" id="contenedorResultados">
        <div class="col-12 col-md-4 mb-3 card p-0" data-genero="Aventura" data-plataforma="PlayStation">
          <a href="detalle.php" target="_blank"><img src="./img/apex1.jpg" class="card-img-top" alt="The Last of Us" />
          </a>
          <div class="card-body">
            <h5 class="card-title">Apex Legends</h5>
            <p class="card-text">Respawn Entertainment - FPS - PlayStation</p>
          </div>
        </div>

        <div class="col-12 col-md-4 mb-3 card p-0" data-genero="Fps" data-plataforma="Playstation">
          <img src="img/315718bce7eed62e3cf3fb02d61b81ff1782d6b6cf850fa4.avif" class="card-img-top"
            alt="The Last of Us" />
          <div class="card-body">
            <h5 class="card-title">The Last of Us</h5>
            <p class="card-text">Naughty Dog - Aventura - PlayStation</p>
          </div>
        </div>

        <div class="col-12 col-md-4 mb-3 card p-0" data-genero="RPG" data-plataforma="PC">
          <img src="img/7bp19fsj4g531.webp" class="card-img-top" alt="Cyberpunk 2077" />
          <div class="card-body">
            <h5 class="card-title">Cyberpunk 2077</h5>
            <p class="card-text">CD Projekt - RPG - PC</p>
          </div>
        </div>

        <div class="col-12 col-md-4 mb-3 card p-0" data-genero="Deportes" data-plataforma="PC">
          <img src="img/easportsfc-24-official-cover-v0-8d71oqtiurbb1.webp" class="card-img-top" alt="FIFA 24" />
          <div class="card-body">
            <h5 class="card-title">FIFA 24</h5>
            <p class="card-text">EA Sports - Deportes - PC</p>
          </div>
        </div>

        <div class="col-12 col-md-4 mb-3 card p-0" data-genero="Sandbox" data-plataforma="PC">
          <img src="img/d7074117b126b7fcdbf13358137a927a.png" class="card-img-top" alt="Minecraft" />
          <div class="card-body">
            <h5 class="card-title">Minecraft</h5>
            <p class="card-text">Mojang - Sandbox - PC</p>
          </div>
        </div>

        <div class="col-12 col-md-4 mb-3 card p-0" data-genero="Aventura" data-plataforma="Nintendo Switch">
          <img src="img/MV5BNjI0NTEwZDYtZDhlOC00MGM2LWFlNGItY2U2ZWRkMzk2ODI0XkEyXkFqcGc@._V1_.jpg" class="card-img-top"
            alt="Zelda" />
          <div class="card-body">
            <h5 class="card-title">Zelda: Breath of the Wild</h5>
            <p class="card-text">Nintendo - Aventura - Nintendo Switch</p>
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

  <!-- Modal Reportar -->
  <div class="modal fade" id="modalReportar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reportar Comentario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <label for="razonReporte" class="form-label">Motivo:</label>
          <textarea id="razonReporte" class="form-control" rows="3" placeholder="Motivo..." required></textarea>
          <div class="invalid-feedback">El motivo es obligatorio.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="button" id="btnAceptarReportar" class="btn btn-primary">
            Aceptar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar -->
  <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Comentario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="text" id="inputEditar" class="form-control" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="button" id="btnAceptarEditar" class="btn btn-primary">
            Aceptar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Eliminar -->
  <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Eliminar Comentario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">¿Eliminar el comentario?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="button" id="btnConfirmarEliminar" class="btn btn-danger">
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>