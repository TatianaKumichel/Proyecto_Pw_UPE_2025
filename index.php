<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  require "./inc/head.php";
  ?>
</head>

<body class="bg-light">
  <header>
    <?php
    require "./inc/menu.php";
    ?>
  </header>

  <!--  contenedor -->
  <main class="container my-4">
    <div class="row">
      <!-- Aqui se ponen los cards de juegos en columnas -->
      <div class="col-lg-9">
        <h3 class="mb-4">Novedades</h3>
        <div class="row g-4">
          <!-- cards. Luego podrian ponerse dinamicamente tras conectar con una base de datos... -->
          <div class="col-12 col-sm-6 col-md-4" data-categoria="aventura">
            <div class="card h-100">
              <img src="https://i.3djuegos.com/juegos/17986/palworld/fotos/ficha/palworld-5861112.jpg"
                class="card-img-top" alt="Juego 1" />
              <div class="card-body">
                <h5 class="card-title">Juego 1</h5>
                <p class="card-text">Descripción.</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4" data-categoria="estrategia">
            <div class="card h-100">
              <img
                src="https://i0.wp.com/www.pcmrace.com/wp-content/uploads/2025/05/Warhammer-40.000-Dawn-of-War-%E2%80%93-Definitive-Edition-key-art-4k.jpg"
                class="card-img-top" alt="Juego 2" />
              <div class="card-body">
                <h5 class="card-title">Juego 2</h5>
                <p class="card-text">Descripción.</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4" data-categoria="supervivencia">
            <div class="card h-100">
              <img
                src="https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/3008130/b5944c43d563d780614d961ee859f7ce1248c9fa/capsule_616x353.jpg?t=1758717740"
                class="card-img-top" alt="Juego 3" />
              <div class="card-body">
                <h5 class="card-title">Juego 3</h5>
                <p class="card-text">Descripción.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carousel de imagenes en un costado o debajo acorde al tamaño de imagen -->
      <aside class="col-lg-3 mt-4 mt-lg-0">
        <h4>Tendencias</h4>
        <div id="tendenciasCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="https://upload.wikimedia.org/wikipedia/en/3/32/Kingdom_Come_Deliverance_II.jpg"
                class="d-block w-100" alt="Tendencia 1" />
            </div>
            <div class="carousel-item">
              <img
                src="https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/2208810/3667d481acc2a8693153cc978ff8cf6f744e8d63/capsule_616x353.jpg?t=1755181567"
                class="d-block w-100" alt="Tendencia 2" />
            </div>
            <div class="carousel-item">
              <img src="https://upload.wikimedia.org/wikipedia/en/7/7b/Silent_Hill_f_cover_art.png"
                class="d-block w-100" alt="Tendencia 3" />
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#tendenciasCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#tendenciasCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      </aside>
    </div>
  </main>

  <footer class="bg-dark text-white mt-4 pt-3 pb-2">
    <?php
    require "./inc/footer.php";
    ?>
  </footer>


</body>

</html>