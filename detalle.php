<!DOCTYPE html>
<html lang="es">

<head>
  <?php
    require "./inc/head.php";
    ?>
  <link rel="stylesheet" href="./css/detalle.css" />
  <script src="./js/detalle.js" defer></script> 
</head>

<body>
  <header>
  <?php
    require "./inc/menu.php";
    ?>
  </header>

  <!-- Espaciador para no tapar contenido -->
  <div style="margin-top: 70px"></div>

  <!-- fin menu que se reemplazara por el posta -->

  <main class="container my-4">
    <div class="row">
      <!-- Carousel -->
      <div class="col-12 col-md-6">
        <div id="carouselJuego" class="carousel slide mb-2" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="img/apex1.jpg" class="d-block w-100 rounded" alt="apex1" />
            </div>
            <div class="carousel-item">
              <img src="img/apex2.jpg" class="d-block w-100 rounded" alt="apex2" />
            </div>
            <div class="carousel-item">
              <img src="img/apex3.jpg" class="d-block w-100 rounded" alt="apex3" />
            </div>
            <div class="carousel-item">
              <img src="img/apex4.jpg" class="d-block w-100 rounded" alt="apex4" />
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselJuego" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Anterior</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselJuego" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Siguiente</span>
          </button>
        </div>

        <!-- Miniaturas -->
        <div id="miniaturas" class="d-flex justify-content-center gap-2 mt-1">
          <img src="img/apex1.jpg" class="img-thumbnail miniatura active-mini" data-bs-target="#carouselJuego"
            data-bs-slide-to="0" />
          <img src="img/apex2.jpg" class="img-thumbnail miniatura" data-bs-target="#carouselJuego"
            data-bs-slide-to="1" />
          <img src="img/apex3.jpg" class="img-thumbnail miniatura" data-bs-target="#carouselJuego"
            data-bs-slide-to="2" />
          <img src="img/apex4.jpg" class="img-thumbnail miniatura" data-bs-target="#carouselJuego"
            data-bs-slide-to="2" />
        </div>
      </div>

      <!-- Datos del juego -->
      <div class="col-12 col-md-6 d-flex flex-column justify-content-start">
        <h2 id="tituloJuego">Apex Legends</h2>
        <p>
          <strong>Descripción:</strong> Apex Legends es el galardonado juego
          gratuito de acción en primera persona de Respawn Entertainment.
          Domina un elenco creciente de leyendas con potentes habilidades.
          Juego estratégico basado en pelotones y jugabilidad innovadora en la
          nueva evolución del Battle Royale y la acción en primera persona.
        </p>
        <p><strong>Plataforma:</strong> PC, PS, XBOX</p>
        <p><strong>Género:</strong> Acción, Aventura, Free to Play</p>
        <p><strong>Empresa:</strong> Respawn</p>
        <p><strong>Lanzamiento:</strong> 20/04/2020</p>

        <!-- Favorito -->
        <button id="btn-favorito" class="btn btn-outline-danger mb-3">
          <i class="bi bi-heart"></i> Marcar como favorito
        </button>

        <!-- Calificación -->
        <h5 id="tituloCalificacion">Calificar este juego:</h5>
        <span id="calificacion">
          <i class="bi bi-star estrella" data-valor="1"></i>
          <i class="bi bi-star estrella" data-valor="2"></i>
          <i class="bi bi-star estrella" data-valor="3"></i>
          <i class="bi bi-star estrella" data-valor="4"></i>
          <i class="bi bi-star estrella" data-valor="5"></i>
        </span>
      </div>
    </div>

    <!-- Comentarios -->
    <section id="comentarios" class="mt-4">
      <h4>Comentarios</h4>

      <!-- Comentarios de otros usuarios -->
      <div class="card mb-3">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">DaniJ - 01/08/2025</h6>
          <p class="card-text">Entretenido, aunque algo complicado.</p>
          <button class="btn btn-sm btn-warning">Reportar</button>
        </div>
      </div>

      <!-- Comentario propio -->
      <div class="card mb-3 border-primary">
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">Yo - Hoy</h6>
          <p class="card-text">Excelente! Para jugar con amigos.</p>
          <button class="btn btn-sm btn-secondary">Editar</button>
          <button class="btn btn-sm btn-danger">Eliminar</button>
        </div>
      </div>

      <!-- Agregar comentario -->
      <form id="form-comentario" class="mt-3">
        <div class="mb-2">
          <textarea id="nuevoComentario" class="form-control" rows="2" placeholder="Agregar comentario..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Enviar</button>
      </form>
    </section>
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