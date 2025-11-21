function mostrarErroresGenerales(errores, divErrores) {
  if (errores.general) {
    divErrores.innerText = errores.general;
    divErrores.classList.remove("d-none");
  } else {
    divErrores.classList.add("d-none");
    divErrores.innerText = "";
  }
}

/**
 * Crea el HTML para el mensaje cuando no hay favoritos
 */
function crearMensajeSinFavoritos() {
  return `
    <div class="col-12 d-flex align-items-center justify-content-center" style="min-height: 60vh;">
      <div class="text-center">
        <i class="bi bi-heart" style="font-size: 4rem; color: #ccc;"></i>
        <h4 class="mt-3 text-muted">No tienes juegos favoritos</h4>
        <p class="text-muted">Explora el catálogo y agrega tus juegos favoritos</p>
        <a href="filtros.php" class="btn btn-primary mt-3">
          <i class="bi bi-collection"></i> Ir al Catálogo
        </a>
      </div>
    </div>
  `;
}

/**
 * Crea y devuelve el HTML del card de un juego.
 */
function crearCardFavorito(juego) {
  const imagenUrl = juego.imagen_portada || "./img/juego-default.png";
  const tituloEscapado = escapeHtml(juego.titulo);
  const descripcionEscapada = escapeHtml(
    juego.descripcion || "Sin descripción"
  );

  return `
    <div class="card h-100 shadow-sm hover-card d-flex flex-column">
      <a href="detalle.php?id_juego=${
        juego.id_juego
      }" class="text-decoration-none">
        <img src="${imagenUrl}"
             class="card-img-top"
             alt="${tituloEscapado}"
             onerror="this.src='./img/juego-default.png'">
      </a>
      <div class="card-body d-flex flex-column flex-grow-1">
        <h5 class="card-title text-dark" title="${tituloEscapado}">
          ${truncarTexto(tituloEscapado, 40)}
        </h5>
        <p class="card-text text-muted small mb-3">
          ${truncarTexto(descripcionEscapada, 80)}
        </p>
        <div class="mt-auto d-flex gap-2">
          <a href="detalle.php?id_juego=${juego.id_juego}"
             class="btn btn-primary btn-sm flex-grow-1">
            <i class="bi bi-eye"></i> Ver Detalles
          </a>
          <button class="btn btn-outline-danger btn-sm btn-favorito"
                  data-id-juego="${juego.id_juego}">
            <i class="bi bi-heart-fill"></i>
          </button>
        </div>
      </div>
    </div>
  `;
}

async function cargarFavoritos(divErrores, contenedor, modal) {
  var errores = {};
  divErrores.classList.add("d-none");
  divErrores.innerHTML = "";

  try {
    var response = await fetch("./bd/gestion-favoritos/getFavoritos.php", {
      method: "GET",
    });
    if (!response.ok) {
      errores["conexion"] = "Error al consultar los favoritos.";
      mostrarErrores(divErrores, errores);
      return;
    }

    var data = await response.json();
    if (!Array.isArray(data)) {
      errores["formato"] = "Error al consultar los favoritos.";
      mostrarErrores(divErrores, errores);
      return;
    }

    contenedor.innerHTML = "";

    // Si no hay favoritos, mostrar mensaje
    if (data.length === 0) {
      contenedor.innerHTML = crearMensajeSinFavoritos();
      return;
    }

    // Renderizar cada juego favorito
    for (var i = 0; i < data.length; i++) {
      var juego = data[i];

      var divCol = document.createElement("div");
      divCol.className = "col-12 col-sm-6 col-md-4 col-lg-3 mb-4";

      // Usar la función de template para crear el HTML
      divCol.innerHTML = crearCardFavorito(juego);

      // Agregar evento al botón de quitar favorito
      var botonFavorito = divCol.querySelector(".btn-favorito");
      botonFavorito.addEventListener("click", function () {
        var idJuego = this.getAttribute("data-id-juego");
        confirmarEliminacion(idJuego, modal, divErrores, contenedor);
      });

      contenedor.appendChild(divCol);
    }
  } catch (e) {
    errores["excepcion"] = "Error inesperado: " + e.message;
    mostrarErrores(divErrores, errores);
  }
}

// Modal confirmacion
function confirmarEliminacion(idJuego, modal, divErrores, contenedor) {
  var btnConfirmar = document.getElementById("btnConfirmarEliminar");

  // Id juego
  btnConfirmar.dataset.idJuego = idJuego;

  var bsModal = new bootstrap.Modal(modal);
  bsModal.show();
}

window.onload = function () {
  var divErrores = document.getElementById("divErroresGenerales");
  var contenedor = document.getElementById("listaFavoritos");
  var modal = document.getElementById("modalConfirmar");
  var btnConfirmar = document.getElementById("btnConfirmarEliminar");

  btnConfirmar.addEventListener("click", async function () {
    var idJuego = this.dataset.idJuego;
    if (idJuego) {
      await eliminarFavorito(idJuego, modal, divErrores, contenedor);
    }
  });

  cargarFavoritos(divErrores, contenedor, modal);
};

async function eliminarFavorito(id, modal, divErrores, contenedor) {
  var errores = {};
  divErrores.classList.add("d-none");
  divErrores.innerHTML = "";
  let data = {
    id: id,
  };
  try {
    var response = await fetch("./bd/gestion-favoritos/delFavoritos.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    if (!response.ok) {
      errores["conexion"] = "Error al eliminar el favorito.";
      mostrarErrores(divErrores, errores);
      return;
    }

    var result = await response.json();
    console.log(result);

    if (result.success || result.ok) {
      var bsModal = bootstrap.Modal.getInstance(modal);
      bsModal.hide();
      await cargarFavoritos(divErrores, contenedor, modal);
    } else {
      errores["negocio"] = result.error || "Error al eliminar.";
      mostrarErrores(divErrores, errores);
    }
  } catch (e) {
    errores["excepcion"] = "Error inesperado: " + e.message;
    mostrarErrores(divErrores, errores);
  }
}

function mostrarErrores(divErrores, errores) {
  divErrores.classList.remove("d-none");
  divErrores.innerHTML = "";
  for (var clave in errores) {
    var p = document.createElement("p");
    p.textContent = errores[clave];
    divErrores.appendChild(p);
  }
}

/**
 * Escapar HTML para prevenir XSS
 */
function escapeHtml(text) {
  if (!text) return "";
  var div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

/**
 * Truncar texto a una longitud máxima
 */
function truncarTexto(texto, maxLength) {
  if (!texto) return "";
  if (texto.length <= maxLength) return texto;
  return texto.substring(0, maxLength) + "...";
}
