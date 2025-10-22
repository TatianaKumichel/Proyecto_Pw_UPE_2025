<!DOCTYPE html>
<html lang="es">

<head>
<?php
  require "./inc/head.php";
  ?>
  <link rel="stylesheet" href="css/favoritos.css" />
  <script src="./js/favoritos.js" defer></script>
</head>

<body>
  <header>
  <?php
    require "./inc/menu.php";
    ?>
  </header>

  <main class="container my-4">
    <h2 class="mb-4">Favoritos</h2>

    <div id="listaFavoritos" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <div class="col">
        <div class="card h-100">
          <img src="img/hapex.jpg" class="card-img-top" alt="Apex Legends" />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Apex Legends</h5>
            <p class="card-text">
              Apex Legends es el galardonado juego gratuito de acción en
              primera persona de Respawn Entertainment. Domina un elenco
              creciente de leyendas con potentes habilidades. Juego
              estratégico basado en pelotones y jugabilidad innovadora en la
              nueva evolución del Battle Royale y la acción en primera
              persona.
            </p>
            <button class="btn btn-outline-danger mt-auto btn-favorito">
              <i class="bi bi-heart-fill"></i> Quitar de favoritos
            </button>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100">
          <img src="img/hage.jpg" class="card-img-top" alt="Age of Empires IV" />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Age of Empires IV</h5>
            <p class="card-text">
              Para celebrar su primer año cautivando a millones de jugadores
              en todo el mundo, la galardonada y exitosa franquicia de
              estrategia continúa con Age of Empires IV: Edición Aniversario,
              sumergiéndote en las épicas batallas históricas que cambiaron el
              mundo.
            </p>
            <button class="btn btn-outline-danger mt-auto btn-favorito">
              <i class="bi bi-heart-fill"></i> Quitar de favoritos
            </button>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100">
          <img src="img/hassasin.jpg" class="card-img-top logo-img" alt="Assassin's Creed Origins" />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Assassin's Creed Origins</h5>
            <p class="card-text">
              Explora el antiguo Egipto en este juego de acción y aventura.
              Enfréntate a enemigos poderosos, desvela conspiraciones y
              descubre la historia del origen de la Hermandad de Asesinos.
            </p>
            <button class="btn btn-outline-danger mt-auto btn-favorito">
              <i class="bi bi-heart-fill"></i> Quitar de favoritos
            </button>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100">
          <img src="img/hcall.jpg" class="card-img-top" alt="Call of Duty: Black Ops 7" />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Call of Duty: Black Ops 7</h5>
            <p class="card-text">
              La entrega más alucinante de Black Ops hasta la fecha con una
              innovativa campaña cooperativa, una experiencia multijugador
              eléctrica y el legendario modo Zombis por rondas.
            </p>
            <button class="btn btn-outline-danger mt-auto btn-favorito">
              <i class="bi bi-heart-fill"></i> Quitar de favoritos
            </button>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100">
          <img src="img/hcs.jpg" class="card-img-top" alt="Counter Strike 2" />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Counter Strike 2</h5>
            <p class="card-text">
              Durante las dos últimas décadas, Counter-Strike ha proporcionado
              una experiencia competitiva de primer nivel para los millones de
              jugadores de todo el mundo que contribuyeron a darle forma.
              Ahora el próximo capítulo en la historia de CS está a punto de
              comenzar.
            </p>
            <button class="btn btn-outline-danger mt-auto btn-favorito">
              <i class="bi bi-heart-fill"></i> Quitar de favoritos
            </button>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100">
          <img src="img/hdiablo.jpg" class="card-img-top" alt="Diablo IV" />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Diablo IV</h5>
            <p class="card-text">
              Únete a la lucha por Santuario en Diablo IV, la aventura de rol
              y acción definitiva. Vive la campaña alabada por la crítica y
              nuevo contenido de temporada.
            </p>
            <button class="btn btn-outline-danger mt-auto btn-favorito">
              <i class="bi bi-heart-fill"></i> Quitar de favoritos
            </button>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100">
          <img src="img/hgta.jpg" class="card-img-top" alt="Grand Theft Auto V" />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Grand Theft Auto V</h5>
            <p class="card-text">
              Disfruta de los superventas del entretenimiento Grand Theft Auto
              V y Grand Theft Auto Online, ahora mejorados para una nueva
              generación, con impresionantes gráficos, carga más rápida, audio
              3D y mucho más.
            </p>
            <button class="btn btn-outline-danger mt-auto btn-favorito">
              <i class="bi bi-heart-fill"></i> Quitar de favoritos
            </button>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100">
          <img src="img/hnba.jpg" class="card-img-top" alt="NBA 2K26" />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">NBA 2K26</h5>
            <p class="card-text">
              Exhibe tu colección de movimientos con hiperrealismo, gracias a
              la tecnología ProPLAY y desafía a tus amigos, o rivales, en los
              modos competitivos de NBA 2K26, y leave no doubt: tú eres el
              rey.
            </p>
            <button class="btn btn-outline-danger mt-auto btn-favorito">
              <i class="bi bi-heart-fill"></i> Quitar de favoritos
            </button>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100">
          <img src="img/hparty.jpg" class="card-img-top" alt="Party Animals" />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Party Animals</h5>
            <p class="card-text">
              Pelea contra tus amigos como perritos, gatitos y otras criaturas
              peludas en PARTY ANIMALS! Patea a tus amigos tanto online como
              offline. Interactúa con el mundo bajo nuestro motor de físicas
              realistas.¿Ya mencioné PERRITOS?
            </p>
            <button class="btn btn-outline-danger mt-auto btn-favorito">
              <i class="bi bi-heart-fill"></i> Quitar de favoritos
            </button>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100">
          <img src="img/hresident.jpg" class="card-img-top" alt="Resident Evil 4" />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Resident Evil 4</h5>
            <p class="card-text">
              Sobrevivir es solo el principio. Con una mecánica de juego
              modernizada, una historia reimaginada y unos gráficos
              espectacularmente detallados, Resident Evil 4 supone el
              renacimiento de un gigante del mundo de los videojuegos.
            </p>
            <button class="btn btn-outline-danger mt-auto btn-favorito">
              <i class="bi bi-heart-fill"></i> Quitar de favoritos
            </button>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100">
          <img src="img/hsf.jpg" class="card-img-top" alt="Street Fighter 6" />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Street Fighter 6</h5>
            <p class="card-text">
              Aquí llega el peso pesado de Capcom! Street Fighter 6 trae
              consigo una nueva evolución de la saga Street Fighter! Incluye
              tres modos de juego: World Tour, Fighting Ground y Battle Hub
            </p>
            <button class="btn btn-outline-danger mt-auto btn-favorito">
              <i class="bi bi-heart-fill"></i> Quitar de favoritos
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
  <footer class="bg-dark text-white mt-4 pt-3 pb-2">
    <div class="container d-flex justify-content-between align-items-start">
    <?php
      require "./inc/footer.php";
    ?>
  </footer>
</body>

</html>