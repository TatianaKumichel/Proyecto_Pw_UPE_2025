/**
 * Detalle de juego
 * información completa del juego, permite marcar favorito, calificar y comentar
 */

window.addEventListener("DOMContentLoaded", () => {
  cargarDetalle();
  cargarComentarios();
  configurarEventosModales();
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
    // Crear copia del array
    imagenesDisponibles = [...juego.imagenes];
  }

  // Si hay imagen_portada y no está en el array, agregarla
  if (
    juego.imagen_portada &&
    !imagenesDisponibles.includes(juego.imagen_portada)
  ) {
    // Agregar como primer elemento
    imagenesDisponibles.unshift(juego.imagen_portada);
  }

  if (imagenesDisponibles.length > 1) {
    // Múltiples imágenes: usar carousel
    const carouselInner = document.getElementById("carousel-inner");
    // Por cada imagen crear bloque html
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
    const data = await obtenerComentarios(ID_JUEGO);

    if (data.error) {
      throw new Error(data.error);
    }

    // Renderizar sección completa
    renderizarSeccionComentarios(seccionComentarios, data, estaLogueado);

    // Configurar eventos si está logueado
    if (estaLogueado) {
      configurarEventosComentarios();
    }
  } catch (error) {
    console.error("Error al cargar comentarios:", error);
    mostrarErrorComentarios(seccionComentarios, error);
  }
}

/**
 * Obtener comentarios
 */
async function obtenerComentarios(idJuego) {
  const response = await fetch(
    `./bd/comentarios/getComentarios.php?id_juego=${idJuego}`
  );

  if (!response.ok) {
    throw new Error(`Error HTTP: ${response.status}`);
  }

  return await response.json();
}

/**
 * Renderizar sección completa de comentarios
 */
function renderizarSeccionComentarios(contenedor, data, estaLogueado) {
  const usuarioTieneComentario = data.usuario_tiene_comentario || false;

  const html = `
    <div class="card mt-4">
      ${crearHeaderComentarios(data.total)}
      <div class="card-body">
        ${crearFormularioNuevoComentario(estaLogueado, usuarioTieneComentario)}
        ${crearListaComentarios(data.comentarios, estaLogueado)}
      </div>
    </div>
  `;

  contenedor.innerHTML = html;
}

/**
 * Crear header de la sección de comentarios
 */
function crearHeaderComentarios(total) {
  return `
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">
        <i class="bi bi-chat-dots" aria-hidden="true"></i>
        Comentarios (${total})
      </h4>
    </div>
  `;
}

/**
 * Crear formulario para nuevo comentario o mensaje de login
 */
function crearFormularioNuevoComentario(
  estaLogueado,
  usuarioTieneComentario = false
) {
  if (!estaLogueado) {
    return `
      <div class="alert alert-info mb-4 text-center" role="alert">
        <i class="bi bi-info-circle" aria-hidden="true"></i>
        <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
          Inicia sesión
        </a>
        para comentar.
      </div>
    `;
  }

  // Usuario logueado pero ya tiene un comentario
  if (usuarioTieneComentario) {
    return `
      <div class="alert alert-info mb-4" role="alert">
        <h6 class="alert-heading">
          <i class="bi bi-info-circle"></i> Ya tienes un comentario publicado
        </h6>
        <div class="d-flex gap-2 flex-wrap">
          <button class="btn btn-sm btn-outline-primary" onclick="scrollToUserComment()">
            <i class="bi bi-arrow-down"></i> Ver mi comentario
          </button>
        </div>
      </div>
    `;
  }

  // Usuario logueado sin comentario - mostrar botón para abrir modal
  return `
    <div class="mb-4 text-center">
      <button class="btn btn-primary btn-lg"
              data-bs-toggle="modal"
              data-bs-target="#modalAgregarComentario">
        <i class="bi bi-chat-dots"></i> Agregar Comentario
      </button>
      <p class="text-muted mt-2 small">
        Comparte tu opinión sobre este juego
      </p>
    </div>
    <hr>
  `;
}

/**
 * Crear lista de comentarios
 * @param {Array} comentarios - Array de comentarios
 * @param {boolean} estaLogueado - Si el usuario está logueado
 * @returns {string} HTML de la lista
 */
function crearListaComentarios(comentarios, estaLogueado) {
  let html = '<div id="listaComentarios">';

  if (comentarios.length === 0) {
    html += crearMensajeComentariosVacios();
  } else {
    html += comentarios
      .map((comentario) => crearHTMLComentario(comentario, estaLogueado))
      .join("");
  }

  html += "</div>";
  return html;
}

/**
 * Crear mensaje cuando no hay comentarios
 */
function crearMensajeComentariosVacios() {
  return `
    <div class="text-center text-muted py-4" role="status">
      <i class="bi bi-chat empty-comments-icon" aria-hidden="true"></i>
      <p class="mt-2">Aún no hay comentarios. ¡Sé el primero en comentar!</p>
    </div>
  `;
}

/**
 * Mostrar error al cargar comentarios
 */
function mostrarErrorComentarios(contenedor, error) {
  contenedor.innerHTML = `
    <div class="card mt-4">
      <div class="card-body">
        <div class="alert alert-danger" role="alert">
          <h5 class="alert-heading">
            <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
            Error al cargar comentarios
          </h5>
          <p class="mb-0">${escapeHtml(error.message)}</p>
          <hr>
          <button class="btn btn-sm btn-outline-danger" onclick="cargarComentarios()">
            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Reintentar
          </button>
        </div>
      </div>
    </div>
  `;
}

/**
 * Crear HTML para un comentario
 * @param {Object} comentario - Datos del comentario
 * @param {boolean} estaLogueado - Si el usuario está logueado
 * @returns {string} HTML del comentario
 */
function crearHTMLComentario(comentario, estaLogueado) {
  // Validar datos requeridos
  if (!comentario || !comentario.id_comentario) {
    console.error("Comentario inválido:", comentario);
    return "";
  }

  const { id_comentario, username, fecha, contenido, avatar, es_propio } =
    comentario;

  return `
    <div class="comentario mb-3 p-3 border rounded ${
      es_propio ? "border-primary" : ""
    }"
         data-id="${id_comentario}"
         data-es-propio="${es_propio}">
      ${crearHeaderComentario(username, fecha, avatar)}
      ${crearBotonesAccion(id_comentario, es_propio, estaLogueado)}
      ${crearContenidoComentario(contenido)}
    </div>
  `;
}

/**
 * Crear header del comentario con avatar y datos del usuario
 */
function crearHeaderComentario(username, fecha, avatar) {
  const fechaFormateada = formatearFechaComentario(fecha);
  const avatarSeguro = avatar || "./img/user_gray.png";
  const usernameSeguro = escapeHtml(username || "Usuario");

  return `
    <div class="d-flex">
      <img src="${avatarSeguro}"
           alt="Avatar de ${usernameSeguro}"
           class="rounded-circle me-3 comment-avatar"
           aria-hidden="true">
      <div class="flex-grow-1">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <strong>${usernameSeguro}</strong>
            <small class="text-muted d-block">
              <time datetime="${fecha}">${fechaFormateada}</time>
            </small>
          </div>
  `;
}

/**
 * Crear botones de acción según el tipo de usuario
 * @param {number} id - ID del comentario
 * @param {boolean} esPropio - Si el comentario pertenece al usuario actual
 * @param {boolean} estaLogueado - Si el usuario está logueado
 * @returns {string} HTML de los botones
 */
function crearBotonesAccion(id, esPropio, estaLogueado) {
  // Si es comentario propio, mostrar botones de editar y eliminar
  if (esPropio) {
    return `
      <div class="btn-group btn-group-sm" role="group" aria-label="Acciones del comentario">
        <button class="btn btn-outline-warning btn-editar"
                onclick="abrirModalEditarComentario(${id})"
                aria-label="Editar comentario">
          <i class="bi bi-pencil" aria-hidden="true"></i> Editar
        </button>
        <button class="btn btn-outline-danger btn-eliminar"
                onclick="abrirModalEliminarComentario(${id})"
                aria-label="Eliminar comentario">
          <i class="bi bi-trash" aria-hidden="true"></i> Eliminar
        </button>
      </div>
    `;
  }

  // Si no es propio y el usuario está logueado, mostrar botón reportar
  if (estaLogueado) {
    return `
      <button class="btn btn-outline-warning btn-sm btn-reportar"
              data-id="${id}"
              aria-label="Reportar comentario">
        <i class="bi bi-flag" aria-hidden="true"></i> Reportar
      </button>
    `;
  }

  // Si no está logueado, no mostrar ningún botón
  return "";
}

/**
 * Crear contenido del comentario
 */
function crearContenidoComentario(contenido) {
  const contenidoSeguro = escapeHtml(contenido || "");

  return `
          </div>
          <p class="mt-2 mb-0 comentario-texto">${contenidoSeguro}</p>
        </div>
      </div>
    </div>
  `;
}

/**
 * Formatear fecha para mostrar en comentarios
 */
function formatearFechaComentario(fecha) {
  if (!fecha) return "Fecha no disponible";

  try {
    return new Date(fecha).toLocaleDateString("es-ES", {
      year: "numeric",
      month: "long",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  } catch (error) {
    console.error("Error al formatear fecha:", error);
    return "Fecha inválida";
  }
}

/**
 * Scroll suave al comentario del usuario y resaltarlo
 */
function scrollToUserComment() {
  const comentarioUsuario = document.querySelector(
    '.comentario[data-es-propio="true"]'
  );
  if (comentarioUsuario) {
    comentarioUsuario.scrollIntoView({ behavior: "smooth", block: "center" });
    comentarioUsuario.classList.add("highlight-comment");
    setTimeout(() => {
      comentarioUsuario.classList.remove("highlight-comment");
    }, 2000);
  }
}

/**
 * Configurar eventos de los modales de comentarios
 */
function configurarEventosModales() {
  // Modal Agregar Comentario
  const modalAgregar = document.getElementById("modalAgregarComentario");
  if (modalAgregar) {
    // Contador de caracteres
    const textareaAgregar = document.getElementById("textoNuevoComentario");
    const contadorAgregar = document.getElementById("contadorNuevo");

    if (textareaAgregar && contadorAgregar) {
      textareaAgregar.addEventListener("input", () => {
        contadorAgregar.textContent = textareaAgregar.value.length;
      });
    }

    // Limpiar al cerrar
    modalAgregar.addEventListener("hidden.bs.modal", () => {
      if (textareaAgregar) {
        textareaAgregar.value = "";
        contadorAgregar.textContent = "0";
      }
    });

    // Botón publicar
    const btnPublicar = document.getElementById("btnPublicarComentario");
    if (btnPublicar) {
      btnPublicar.addEventListener("click", agregarComentarioModal);
    }
  }

  // Modal Editar Comentario
  const modalEditar = document.getElementById("modalEditarComentario");
  if (modalEditar) {
    const textareaEditar = document.getElementById("textoEditarComentario");
    const contadorEditar = document.getElementById("contadorEditar");

    if (textareaEditar && contadorEditar) {
      textareaEditar.addEventListener("input", () => {
        contadorEditar.textContent = textareaEditar.value.length;
      });
    }

    const btnGuardar = document.getElementById("btnGuardarEdicion");
    if (btnGuardar) {
      btnGuardar.addEventListener("click", guardarEdicionModal);
    }
  }

  // Modal Eliminar
  const btnConfirmarEliminar = document.getElementById("btnConfirmarEliminar");
  if (btnConfirmarEliminar) {
    btnConfirmarEliminar.addEventListener("click", eliminarComentarioModal);
  }
}

/**
 * Agregar comentario desde modal
 */
async function agregarComentarioModal() {
  const textarea = document.getElementById("textoNuevoComentario");
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
      // Cerrar modal
      const modal = bootstrap.Modal.getInstance(
        document.getElementById("modalAgregarComentario")
      );
      modal.hide();

      mostrarNotificacion("Comentario publicado exitosamente", "success");
      await cargarComentarios();
    } else {
      throw new Error(data.error || "Error al publicar comentario");
    }
  } catch (error) {
    console.error("Error:", error);
    mostrarNotificacion(error.message, "danger");
  }
}

/**
 * Abrir modal para editar comentario
 */
function abrirModalEditarComentario(id = null) {
  // Si no se pasa ID, buscar el comentario del usuario
  const comentarioUsuario = id
    ? document.querySelector(`.comentario[data-id="${id}"]`)
    : document.querySelector('.comentario[data-es-propio="true"]');

  if (!comentarioUsuario) {
    mostrarNotificacion("No se encontró el comentario", "danger");
    return;
  }

  const idComentario = comentarioUsuario.dataset.id;
  const contenidoActual =
    comentarioUsuario.querySelector(".comentario-texto").textContent;

  // Llenar modal
  document.getElementById("idComentarioEditar").value = idComentario;
  document.getElementById("textoEditarComentario").value = contenidoActual;
  document.getElementById("contadorEditar").textContent =
    contenidoActual.length;

  // Abrir modal
  const modal = new bootstrap.Modal(
    document.getElementById("modalEditarComentario")
  );
  modal.show();
}

/**
 * Guardar edición desde modal
 */
async function guardarEdicionModal() {
  const id = document.getElementById("idComentarioEditar").value;
  const textarea = document.getElementById("textoEditarComentario");
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
      const modal = bootstrap.Modal.getInstance(
        document.getElementById("modalEditarComentario")
      );
      modal.hide();

      mostrarNotificacion("Comentario actualizado exitosamente", "success");
      await cargarComentarios();
    } else {
      throw new Error(data.error || "Error al actualizar comentario");
    }
  } catch (error) {
    console.error("Error:", error);
    mostrarNotificacion(error.message, "danger");
  }
}

/**
 * Abrir modal para eliminar comentario
 */
function abrirModalEliminarComentario(id) {
  const comentario = document.querySelector(`.comentario[data-id="${id}"]`);
  if (!comentario) return;

  const contenido = comentario.querySelector(".comentario-texto").textContent;

  // Llenar modal
  document.getElementById("idComentarioEliminar").value = id;
  document.getElementById("previsualizacionEliminar").textContent =
    contenido.length > 100 ? contenido.substring(0, 100) + "..." : contenido;

  // Abrir modal
  const modal = new bootstrap.Modal(
    document.getElementById("modalEliminarComentario")
  );
  modal.show();
}

/**
 * Eliminar comentario desde modal
 */
async function eliminarComentarioModal() {
  const id = document.getElementById("idComentarioEliminar").value;

  try {
    const response = await fetch("./bd/comentarios/deleteComentario.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id_comentario: parseInt(id) }),
    });

    const data = await response.json();

    if (data.success) {
      const modal = bootstrap.Modal.getInstance(
        document.getElementById("modalEliminarComentario")
      );
      modal.hide();

      mostrarNotificacion("Comentario eliminado exitosamente", "success");
      await cargarComentarios();
    } else {
      throw new Error(data.error || "Error al eliminar comentario");
    }
  } catch (error) {
    console.error("Error:", error);
    mostrarNotificacion(error.message, "danger");
  }
}

/**
 * Configurar eventos de comentarios
 */
function configurarEventosComentarios() {
  // Botones de reportar
  document.querySelectorAll(".btn-reportar").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const id = e.currentTarget.dataset.id;
      mostrarFormularioReporte(id);
    });
  });
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
