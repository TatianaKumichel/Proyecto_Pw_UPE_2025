/**
 * Detalle de juego
 * información completa del juego, permite marcar favorito, calificar y comentar
 */

window.addEventListener("DOMContentLoaded", () => {
  cargarDetalle();
  cargarComentarios();
});

/**
 * Cargar detalle del juego
 */
async function cargarDetalle() {
  try {
    const res = await fetch(`./bd/juegos/getJuego.php?id_juego=${ID_JUEGO}`);
    const juego = await res.json();

    if (juego.error) {
      throw new Error(juego.error);
    }

    // Verificar si el usuario está logueado
    const estaLogueado = await verificarSesion();

    // Llenar datos básicos
    document.getElementById("titulo").textContent = juego.titulo;
    document.getElementById("descripcion").textContent =
      juego.descripcion || "Sin descripción disponible";
    document.getElementById("empresa").textContent = juego.empresa;

    // Manejar imágenes
    cargarImagenes(juego);

    // Plataformas
    if (juego.plataformas && juego.plataformas.length > 0) {
      document
        .getElementById("contenedor-plataformas")
        .classList.remove("d-none");
      document.getElementById("plataformas").textContent =
        juego.plataformas.join(", ");
    }

    // Géneros
    if (juego.generos && juego.generos.length > 0) {
      document.getElementById("contenedor-generos").classList.remove("d-none");
      document.getElementById("generos").textContent = juego.generos.join(", ");
    }

    // Fecha de lanzamiento
    if (juego.fecha_lanzamiento) {
      document
        .getElementById("contenedor-lanzamiento")
        .classList.remove("d-none");
      document.getElementById("lanzamiento").textContent = formatearFecha(
        juego.fecha_lanzamiento
      );
    }

    // Mostrar sección según estado de login
    if (estaLogueado) {
      document.getElementById("logged-user-section").classList.remove("d-none");
      document.getElementById("btn-favorito").dataset.idJuego = juego.id_juego;
      await configurarEventos(juego.id_juego);
      verificarFavorito(juego.id_juego);
    } else {
      document.getElementById("guest-section").classList.remove("d-none");
    }
  } catch (err) {
    console.error("Error al cargar detalle:", err);
    mostrarError(err.message);
  }
}

/**
 * Cargar imágenes del juego
 */
function cargarImagenes(juego) {
  const carouselContainer = document.getElementById("carousel-container");
  const singleImage = document.getElementById("single-image");

  // Obtener todas las imágenes
  let imagenesDisponibles = [];

  // Agregar imágenes del array si existen
  if (
    juego.imagenes &&
    Array.isArray(juego.imagenes) &&
    juego.imagenes.length > 0
  ) {
    imagenesDisponibles = [...juego.imagenes];
  }

  // Si hay imagen_portada y no está en el array, agregarla
  if (
    juego.imagen_portada &&
    !imagenesDisponibles.includes(juego.imagen_portada)
  ) {
    imagenesDisponibles.unshift(juego.imagen_portada);
  }
  if (imagenesDisponibles.length > 1) {
    // Múltiples imágenes: usar carousel
    const carouselInner = document.getElementById("carousel-inner");
    carouselInner.innerHTML = imagenesDisponibles
      .map(
        (img, i) => `
      <div class="carousel-item ${i === 0 ? "active" : ""}">
        <img src="${escapeHtml(
          img
        )}" class="d-block w-100 rounded carousel-game-image"
             alt="${escapeHtml(juego.titulo)}">
      </div>
    `
      )
      .join("");

    // Mostrar controles si hay más de una imagen
    document.getElementById("carousel-prev").classList.remove("d-none");
    document.getElementById("carousel-next").classList.remove("d-none");

    carouselContainer.classList.remove("d-none");
    singleImage.classList.add("d-none");
  } else if (imagenesDisponibles.length === 1) {
    // Una sola imagen: mostrar sin carousel
    singleImage.src = escapeHtml(imagenesDisponibles[0]);
    singleImage.alt = escapeHtml(juego.titulo);
    singleImage.classList.remove("d-none");
    carouselContainer.classList.add("d-none");
  } else {
    // Sin imagen
    singleImage.src = "img/juego-sin-imagen.svg";
    singleImage.alt = "Sin imagen disponible";
    singleImage.classList.remove("d-none");
    carouselContainer.classList.add("d-none");
  }
}

/**
 * Mostrar mensaje de error
 */
function mostrarError(mensaje) {
  const contenedor = document.getElementById("contenedor-detalle");
  contenedor.innerHTML = `
    <div class="col-12">
      <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle"></i> Error: ${escapeHtml(mensaje)}
      </div>
    </div>
  `;
}

/**
 * Verificar si el usuario está logueado
 */
async function verificarSesion() {
  try {
    const response = await fetch("./bd/verificar-sesion.php");
    const data = await response.json();
    return data.logueado === true;
  } catch (error) {
    console.error("Error al verificar sesión:", error);
    return false;
  }
}

/**
 * Verificar si el juego ya está en favoritos
 */
async function verificarFavorito(id_juego) {
  try {
    const response = await fetch(
      `./bd/gestion-favoritos/verificarFavorito.php?id_juego=${id_juego}`
    );
    const data = await response.json();

    const btnFavorito = document.getElementById("btn-favorito");
    const textoFavorito = document.getElementById("texto-favorito");

    if (data.esFavorito) {
      btnFavorito.classList.remove("btn-outline-danger");
      btnFavorito.classList.add("btn-danger");
      textoFavorito.innerHTML = '<i class="bi bi-heart-fill"></i> En favoritos';
    }
  } catch (error) {
    console.error("Error al verificar favorito:", error);
  }
}

/**
 * Cargar calificación existente del usuario
 */
async function cargarCalificacion(id_juego) {
  try {
    const response = await fetch(
      `./bd/juegos/getCalificacion.php?id_juego=${id_juego}`
    );
    const data = await response.json();

    if (data.calificacion) {
      mostrarCalificacion(data.calificacion);
      return data.calificacion;
    }
    return 0;
  } catch (error) {
    console.error("Error al cargar calificación:", error);
    return 0;
  }
}

/**
 * Mostrar calificación en las estrellas
 */
function mostrarCalificacion(puntuacion) {
  document.querySelectorAll(".estrella").forEach((s, i) => {
    if (puntuacion > 0 && i < puntuacion) {
      s.classList.remove("bi-star");
      s.classList.add("bi-star-fill");
    } else {
      s.classList.remove("bi-star-fill");
      s.classList.add("bi-star");
    }
  });
}

/**
 * Configurar eventos de interacción
 */
async function configurarEventos(id_juego) {
  // Evento de favorito
  const btnFavorito = document.getElementById("btn-favorito");
  if (btnFavorito) {
    btnFavorito.addEventListener("click", () => toggleFavorito(id_juego));
  }

  // Variable para guardar la calificación actual
  let calificacionActual = 0;

  // Cargar calificación existente
  const calificacionGuardada = await cargarCalificacion(id_juego);
  calificacionActual = calificacionGuardada;

  // Eventos de calificación
  document.querySelectorAll(".estrella").forEach((star, index) => {
    star.addEventListener("mouseenter", () => {
      document.querySelectorAll(".estrella").forEach((s, i) => {
        if (i <= index) {
          s.classList.remove("bi-star");
          s.classList.add("bi-star-fill");
        } else {
          s.classList.remove("bi-star-fill");
          s.classList.add("bi-star");
        }
      });
    });

    star.addEventListener("click", async (e) => {
      const valor = parseInt(e.target.dataset.valor);

      // Si se hace clic en la misma estrella que ya está seleccionada, quitar calificación
      if (valor === calificacionActual) {
        await calificar(id_juego, 0);
        calificacionActual = 0;
        mostrarCalificacion(0);
      } else {
        await calificar(id_juego, valor);
        calificacionActual = valor;
      }
    });
  });

  // Restaurar calificación guardada al salir del área
  document.getElementById("calificacion").addEventListener("mouseleave", () => {
    mostrarCalificacion(calificacionActual);
  });
}

/**
 * Toggle favorito
 */
async function toggleFavorito(id_juego) {
  const btnFavorito = document.getElementById("btn-favorito");
  const textoFavorito = document.getElementById("texto-favorito");
  const esFavorito = btnFavorito.classList.contains("btn-danger");

  try {
    const url = esFavorito
      ? "./bd/gestion-favoritos/delFavoritos.php"
      : "./bd/gestion-favoritos/insertFavoritos.php";

    const res = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id_juego }),
    });

    const data = await res.json();

    if (data.success || data.message) {
      if (esFavorito) {
        btnFavorito.classList.remove("btn-danger");
        btnFavorito.classList.add("btn-outline-danger");
        textoFavorito.innerHTML =
          '<i class="bi bi-heart"></i> Marcar como favorito';
        mostrarNotificacion("Eliminado de favoritos", "info");
      } else {
        btnFavorito.classList.remove("btn-outline-danger");
        btnFavorito.classList.add("btn-danger");
        textoFavorito.innerHTML =
          '<i class="bi bi-heart-fill"></i> En favoritos';
        mostrarNotificacion("Agregado a favoritos", "success");
      }
    } else {
      throw new Error(data.error || "Error al actualizar favorito");
    }
  } catch (error) {
    console.error("Error:", error);
    mostrarNotificacion("Error al actualizar favorito", "danger");
  }
}

/**
 * Calificar juego
 */
async function calificar(id_juego, valor) {
  try {
    const res = await fetch("./bd/juegos/insertCalificacion.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id_juego, puntuacion: parseInt(valor) }),
    });

    const data = await res.json();

    if (data.success || data.message) {
      if (valor === 0) {
        mostrarNotificacion("Calificación eliminada", "info");
      } else {
        mostrarNotificacion(
          `Calificación registrada: ${valor} estrellas`,
          "success"
        );
      }

      // Mostrar la calificación en las estrellas
      mostrarCalificacion(parseInt(valor));
    } else {
      throw new Error(data.error || "Error al calificar");
    }
  } catch (error) {
    console.error("Error:", error);
    mostrarNotificacion("Error al registrar calificación", "danger");
  }
}

/**
 * Cargar comentarios del juego
 */
async function cargarComentarios() {
  const seccionComentarios = document.getElementById("comentarios");
  if (!seccionComentarios) return;

  try {
    // Verificar si el usuario está logueado
    const estaLogueado = await verificarSesion();

    // Obtener comentarios
    const response = await fetch(
      `./bd/comentarios/getComentarios.php?id_juego=${ID_JUEGO}`
    );
    const data = await response.json();

    if (data.error) {
      throw new Error(data.error);
    }

    // Construir HTML
    let html = `
      <div class="card mt-4">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0"><i class="bi bi-chat-dots"></i> Comentarios (${data.total})</h4>
        </div>
        <div class="card-body">
    `;

    // Formulario para agregar comentario (solo si está logueado)
    if (estaLogueado) {
      html += `
        <div class="mb-4">
          <h5>Agregar comentario</h5>
          <div class="mb-3">
            <textarea id="nuevoComentario" class="form-control" rows="3"
                      placeholder="Escribe tu comentario aquí (máximo 500 caracteres)"
                      maxlength="500"></textarea>
            <small class="text-muted">
              <span id="contadorCaracteres">0</span>/500 caracteres
            </small>
          </div>
          <button id="btnAgregarComentario" class="btn btn-primary">
            <i class="bi bi-send"></i> Publicar Comentario
          </button>
        </div>
        <hr>
      `;
    } else {
      html += `
        <div class="alert alert-info mb-4">
          <i class="bi bi-info-circle"></i>
          <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Inicia sesión</a>
          para comentar.
        </div>
      `;
    }

    // Lista de comentarios
    html += `<div id="listaComentarios">`;

    if (data.comentarios.length === 0) {
      html += `
        <div class="text-center text-muted py-4">
          <i class="bi bi-chat empty-comments-icon"></i>
          <p class="mt-2">Aún no hay comentarios. ¡Sé el primero en comentar!</p>
        </div>
      `;
    } else {
      data.comentarios.forEach((comentario) => {
        html += crearHTMLComentario(comentario);
      });
    }

    html += `
        </div>
      </div>
    </div>
    
    <!-- Modal de confirmación para eliminar comentario -->
    <div class="modal fade" id="modalEliminarComentario" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">
              <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>¿Estás seguro de que deseas eliminar este comentario?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x"></i> Cancelar
            </button>
            <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">
              <i class="bi bi-trash"></i> Eliminar Comentario
            </button>
          </div>
        </div>
      </div>
    </div>
    `;

    seccionComentarios.innerHTML = html;

    // Configurar eventos si está logueado
    if (estaLogueado) {
      configurarEventosComentarios();
    }
  } catch (error) {
    console.error("Error al cargar comentarios:", error);
    seccionComentarios.innerHTML = `
      <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle"></i> Error al cargar comentarios
      </div>
    `;
  }
}

/**
 * Crear HTML para un comentario
 */
function crearHTMLComentario(comentario) {
  const fecha = new Date(comentario.fecha).toLocaleDateString("es-ES", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });

  const avatar = comentario.avatar || "./img/user_gray.png";

  let html = `
    <div class="comentario mb-3 p-3 border rounded" data-id="${
      comentario.id_comentario
    }">
      <div class="d-flex">
        <img src="${avatar}" alt="${escapeHtml(comentario.username)}"
             class="rounded-circle me-3 comment-avatar">
        <div class="flex-grow-1">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <strong>${escapeHtml(comentario.username)}</strong>
              <small class="text-muted d-block">${fecha}</small>
            </div>
  `;

  // Botones de acción
  if (comentario.es_propio) {
    // Comentario propio: editar y eliminar
    html += `
      <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-primary btn-editar" data-id="${comentario.id_comentario}">
          <i class="bi bi-pencil"></i> Editar
        </button>
        <button class="btn btn-outline-danger btn-eliminar" data-id="${comentario.id_comentario}">
          <i class="bi bi-trash"></i> Eliminar
        </button>
      </div>
    `;
  } else {
    // Comentario de otro: reportar
    html += `
      <button class="btn btn-outline-warning btn-sm btn-reportar" data-id="${comentario.id_comentario}">
        <i class="bi bi-flag"></i> Reportar
      </button>
    `;
  }

  html += `
          </div>
          <p class="mt-2 mb-0 comentario-texto">${escapeHtml(
            comentario.contenido
          )}</p>
          
          <!-- Formulario de edición (oculto por defecto) -->
          <div class="form-edicion mt-2 d-none">
            <textarea class="form-control mb-2" rows="3" maxlength="500">${escapeHtml(
              comentario.contenido
            )}</textarea>
            <button class="btn btn-sm btn-success btn-guardar-edicion" data-id="${
              comentario.id_comentario
            }">
              <i class="bi bi-check"></i> Guardar
            </button>
            <button class="btn btn-sm btn-secondary btn-cancelar-edicion">
              <i class="bi bi-x"></i> Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
  `;

  return html;
}

/**
 * Configurar eventos de comentarios
 */
function configurarEventosComentarios() {
  // Contador de caracteres
  const textarea = document.getElementById("nuevoComentario");
  const contador = document.getElementById("contadorCaracteres");

  if (textarea && contador) {
    textarea.addEventListener("input", () => {
      contador.textContent = textarea.value.length;
    });
  }

  // Botón agregar comentario
  const btnAgregar = document.getElementById("btnAgregarComentario");
  if (btnAgregar) {
    btnAgregar.addEventListener("click", agregarComentario);
  }

  // Botones de editar
  document.querySelectorAll(".btn-editar").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const id = e.currentTarget.dataset.id;
      mostrarFormularioEdicion(id);
    });
  });

  // Botones de eliminar
  document.querySelectorAll(".btn-eliminar").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const id = e.currentTarget.dataset.id;
      confirmarEliminarComentario(id);
    });
  });

  // Botones de reportar
  document.querySelectorAll(".btn-reportar").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const id = e.currentTarget.dataset.id;
      mostrarFormularioReporte(id);
    });
  });

  // Botones de guardar edición
  document.querySelectorAll(".btn-guardar-edicion").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const id = e.currentTarget.dataset.id;
      guardarEdicionComentario(id);
    });
  });

  // Botones de cancelar edición
  document.querySelectorAll(".btn-cancelar-edicion").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const comentarioDiv = e.currentTarget.closest(".comentario");
      ocultarFormularioEdicion(comentarioDiv);
    });
  });
}

/**
 * Agregar nuevo comentario
 */
async function agregarComentario() {
  const textarea = document.getElementById("nuevoComentario");
  const contenido = textarea.value.trim();

  if (!contenido) {
    mostrarNotificacion("El comentario no puede estar vacío", "warning");
    return;
  }

  try {
    const response = await fetch("./bd/comentarios/insertComentario.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id_juego: ID_JUEGO,
        contenido: contenido,
      }),
    });

    const data = await response.json();

    if (data.success) {
      mostrarNotificacion("Comentario publicado exitosamente", "success");
      textarea.value = "";
      document.getElementById("contadorCaracteres").textContent = "0";
      await cargarComentarios(); // Recargar comentarios
    } else {
      throw new Error(data.error || "Error al publicar comentario");
    }
  } catch (error) {
    console.error("Error:", error);
    mostrarNotificacion(error.message, "danger");
  }
}

/**
 * Mostrar formulario de edición
 */
function mostrarFormularioEdicion(id) {
  const comentarioDiv = document.querySelector(`.comentario[data-id="${id}"]`);
  if (!comentarioDiv) return;

  const textoDiv = comentarioDiv.querySelector(".comentario-texto");
  const formEdicion = comentarioDiv.querySelector(".form-edicion");
  const botones = comentarioDiv.querySelector(".btn-group");

  textoDiv.classList.add("d-none");
  botones.classList.add("d-none");
  formEdicion.classList.remove("d-none");
}

/**
 * Ocultar formulario de edición
 */
function ocultarFormularioEdicion(comentarioDiv) {
  const textoDiv = comentarioDiv.querySelector(".comentario-texto");
  const formEdicion = comentarioDiv.querySelector(".form-edicion");
  const botones = comentarioDiv.querySelector(".btn-group");

  textoDiv.classList.remove("d-none");
  botones.classList.remove("d-none");
  formEdicion.classList.add("d-none");
}

/**
 * Guardar edición de comentario
 */
async function guardarEdicionComentario(id) {
  const comentarioDiv = document.querySelector(`.comentario[data-id="${id}"]`);
  if (!comentarioDiv) return;

  const textarea = comentarioDiv.querySelector(".form-edicion textarea");
  const contenido = textarea.value.trim();

  if (!contenido) {
    mostrarNotificacion("El comentario no puede estar vacío", "warning");
    return;
  }

  try {
    const response = await fetch("./bd/comentarios/updateComentario.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id_comentario: parseInt(id),
        contenido: contenido,
      }),
    });

    const data = await response.json();

    if (data.success) {
      mostrarNotificacion("Comentario actualizado exitosamente", "success");
      await cargarComentarios(); // Recargar comentarios
    } else {
      throw new Error(data.error || "Error al actualizar comentario");
    }
  } catch (error) {
    console.error("Error:", error);
    mostrarNotificacion(error.message, "danger");
  }
}

/**
 * Confirmar eliminación de comentario
 */
function confirmarEliminarComentario(id) {
  // Mostrar modal de confirmación
  const modal = new bootstrap.Modal(
    document.getElementById("modalEliminarComentario")
  );
  modal.show();

  // Configurar botón de confirmación
  const btnConfirmar = document.getElementById("btnConfirmarEliminar");

  // Remover event listeners anteriores (si existen)
  const nuevoBtn = btnConfirmar.cloneNode(true);
  btnConfirmar.parentNode.replaceChild(nuevoBtn, btnConfirmar);

  // Agregar nuevo event listener
  nuevoBtn.addEventListener("click", () => {
    modal.hide();
    eliminarComentario(id);
  });
}

/**
 * Eliminar comentario
 */
async function eliminarComentario(id) {
  try {
    const response = await fetch("./bd/comentarios/deleteComentario.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id_comentario: parseInt(id),
      }),
    });

    const data = await response.json();

    if (data.success) {
      mostrarNotificacion("Comentario eliminado exitosamente", "success");
      await cargarComentarios(); // Recargar comentarios
    } else {
      throw new Error(data.error || "Error al eliminar comentario");
    }
  } catch (error) {
    console.error("Error:", error);
    mostrarNotificacion(error.message, "danger");
  }
}

/**
 * Mostrar formulario de reporte
 */
function mostrarFormularioReporte(id) {
  const motivo = prompt(
    "¿Por qué deseas reportar este comentario?\n(Máximo 255 caracteres)"
  );

  if (motivo && motivo.trim()) {
    reportarComentario(id, motivo.trim());
  }
}

/**
 * Reportar comentario
 */
async function reportarComentario(id, motivo) {
  try {
    const response = await fetch("./bd/comentarios/reportarComentario.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id_comentario: parseInt(id),
        motivo: motivo,
      }),
    });

    const data = await response.json();

    if (data.success) {
      mostrarNotificacion(data.message, "success");
      await cargarComentarios(); // Recargar comentarios
    } else {
      throw new Error(data.error || "Error al reportar comentario");
    }
  } catch (error) {
    console.error("Error:", error);
    mostrarNotificacion(error.message, "danger");
  }
}

/**
 * Mostrar notificación toast
 */
function mostrarNotificacion(mensaje, tipo = "info") {
  const toast = document.createElement("div");
  toast.className = `alert alert-${tipo} position-fixed top-0 end-0 m-3 toast-notification`;
  toast.innerHTML = `
    <i class="bi bi-${
      tipo === "success"
        ? "check-circle"
        : tipo === "danger"
        ? "exclamation-triangle"
        : "info-circle"
    }"></i>
    ${mensaje}
  `;
  document.body.appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, 3000);
}

function escapeHtml(text) {
  if (!text) return "";
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

function formatearFecha(fecha) {
  const date = new Date(fecha);
  return date.toLocaleDateString("es-ES", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}
