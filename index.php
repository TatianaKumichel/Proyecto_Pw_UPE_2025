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
    <link rel="stylesheet" href="./css/index.css" />
    <script src="./js/proximos-lanzamientos.js" defer></script>
    <script src="./js/tendencias.js" defer></script>
  </header>

  <!--  contenedor -->
  <main class="container my-4">
    <div class="row">
      <!-- Aqui se ponen los cards de juegos en columnas -->
      <div class="col-lg-8">
        <h3 class="mb-4">
        <i class="bi bi-calendar-event"></i> Próximos Lanzamientos
        </h3>
        <div id="proximosLanzamientos" class="row g-4">
          <!-- Se llenará dinámicamente con JavaScript -->
        </div>
        <div id="noProximosLanzamientos" class="alert alert-info d-none">
          <i class="bi bi-info-circle"></i>
          No hay próximos lanzamientos programados en este momento.
        </div>
      </div>
    <!--</div>-->

      <!-- Carousel de imagenes en un costado o debajo acorde al tamaño de imagen -->
      <aside class="col-lg-4 mt-4 mt-lg-0">
        <h4>
          <i class="bi bi-fire"></i> Tendencias
        </h4>
        <div id="tendenciasCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-indicators"> 
          </div>
          <div class="carousel-inner">
          <!-- Se llenará dinámicamente con JavaScript -->
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#tendenciasCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#tendenciasCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      <div id="noTendencias" class="alert alert-info d-none mt-3">
        <i class="bi bi-info-circle"></i>
          No hay suficientes calificaciones para mostrar tendencias.
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