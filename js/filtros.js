/**
 * Sistema de filtros dinámico para catálogo de videojuegos
 * Carga juegos, géneros y plataformas desde la base de datos
 */

// Elementos del DOM
const inputNombre = document.getElementById("filtroNombre");
const selectGenero = document.getElementById("filtroGenero");
const selectPlataforma = document.getElementById("filtroPlataforma");
const contenedorResultados = document.getElementById("contenedorResultados");
const contadorResultados = document.getElementById("contadorResultados");

// Variables globales
let juegosOriginales = [];
let juegosFiltrados = [];

/**
 * Inicializar la página
 */
async function inicializar() {
  try {
    // Cargar géneros y plataformas para los filtros
    await Promise.all([cargarGeneros(), cargarPlataformas()]);

    // Cargar todos los juegos
    await cargarJuegos();

    // Configurar eventos de filtrado
    configurarEventos();
  } catch (error) {
    console.error("Error al inicializar:", error);
    mostrarError("Error al cargar los datos. Por favor, recarga la página.");
  }
}

/**
 * Cargar géneros desde la BD
 */
async function cargarGeneros() {
  try {
    const response = await fetch("./bd/juegos/getGeneros.php");
    const data = await response.json();

    if (data.success && data.generos) {
      data.generos.forEach((genero) => {
        const option = document.createElement("option");
        option.value = genero.id_genero;
        option.textContent = genero.nombre;
        selectGenero.appendChild(option);
      });
    }
  } catch (error) {
    console.error("Error al cargar géneros:", error);
  }
}

/**
 * Cargar plataformas desde la BD
 */
async function cargarPlataformas() {
  try {
    const response = await fetch("./bd/juegos/getPlataformas.php");
    const data = await response.json();

    if (data.success && data.plataformas) {
      data.plataformas.forEach((plataforma) => {
        const option = document.createElement("option");
        option.value = plataforma.id_plataforma;
        option.textContent = plataforma.nombre;
        selectPlataforma.appendChild(option);
      });
    }
  } catch (error) {
    console.error("Error al cargar plataformas:", error);
  }
}

/**
 * Cargar juegos desde la BD con filtros opcionales
 */
async function cargarJuegos() {
  try {
    // Construir URL con parámetros de filtro
    const params = new URLSearchParams();

    const nombre = inputNombre.value.trim();
    const genero = selectGenero.value;
    const plataforma = selectPlataforma.value;

    if (nombre) params.append("nombre", nombre);
    if (genero) params.append("id_genero", genero);
    if (plataforma) params.append("id_plataforma", plataforma);

    const url = `./bd/juegos/getJuegos.php${
      params.toString() ? "?" + params.toString() : ""
    }`;
    const response = await fetch(url);
    const data = await response.json();

    if (data.success) {
      juegosOriginales = data.juegos;
      juegosFiltrados = data.juegos;
      mostrarJuegos(data.juegos);
      // actualizarContador(data.juegos.length); // Contador removido del HTML
    } else {
      throw new Error(data.error || "Error al cargar juegos");
    }
  } catch (error) {
    console.error("Error al cargar juegos:", error);
    mostrarError("Error al cargar los juegos. Por favor, intenta nuevamente.");
  }
}

/**
 * Mostrar juegos en el contenedor
 */
function mostrarJuegos(juegos) {
  contenedorResultados.innerHTML = "";

  if (juegos.length === 0) {
    contenedorResultados.innerHTML = `
      <div class="col-12 text-center py-5">
        <i class="bi bi-search" style="font-size: 4rem; color: #ccc;"></i>
        <h4 class="mt-3 text-muted">No se encontraron juegos</h4>
        <p class="text-muted">Intenta con otros filtros</p>
      </div>
    `;
    return;
  }

  juegos.forEach((juego) => {
    const card = crearCardJuego(juego);
    contenedorResultados.appendChild(card);
  });
}

/**
 * Crear card de juego
 */
function crearCardJuego(juego) {
  const col = document.createElement("div");
  col.className = "col-12 col-sm-6 col-md-4 col-lg-3 mb-4";

  // Imagen por defecto si no tiene
  const imagenUrl = juego.imagen_portada || "./img/juego-default.png";

  col.innerHTML = `
    <div class="card h-100 shadow-sm hover-card">
      <a href="detalle.php?id_juego=${
        juego.id_juego
      }" class="text-decoration-none">
        <img src="${imagenUrl}"
             class="card-img-top"
             alt="${escapeHtml(juego.titulo)}"
             onerror="this.src='./img/juego-default.png'">
      </a>
      <div class="card-body">
        <h5 class="card-title text-dark" title="${escapeHtml(juego.titulo)}">
          ${truncarTexto(escapeHtml(juego.titulo), 40)}
        </h5>
        <p class="card-text text-muted small mb-2">
          <i class="bi bi-building"></i> ${escapeHtml(juego.empresa)}
        </p>
        ${
          juego.generos
            ? `
          <p class="card-text small mb-1">
            <i class="bi bi-tags"></i> 
            <span class="text-muted">${escapeHtml(juego.generos)}</span>
          </p>
        `
            : ""
        }
        ${
          juego.plataformas
            ? `
          <p class="card-text small mb-0">
            <i class="bi bi-display"></i> 
            <span class="text-muted">${escapeHtml(juego.plataformas)}</span>
          </p>
        `
            : ""
        }
      </div>
      <div class="card-footer bg-transparent border-top-0 mt-auto">
        <a href="detalle.php?id_juego=${
          juego.id_juego
        }" class="btn btn-primary btn-sm w-100">
          <i class="bi bi-eye"></i> Ver Detalles
        </a>
      </div>
    </div>
  `;

  return col;
}

/**
 * Actualizar contador de resultados
 * FUNCIÓN DESHABILITADA - El contador fue removido del HTML
 */
/*
function actualizarContador(cantidad) {
  if (cantidad === 0) {
    contadorResultados.innerHTML =
      "No se encontraron juegos con los filtros seleccionados";
    contadorResultados.parentElement.className =
      "alert alert-warning d-flex align-items-center";
  } else if (cantidad === 1) {
    contadorResultados.innerHTML = "Se encontró 1 juego";
    contadorResultados.parentElement.className =
      "alert alert-info d-flex align-items-center";
  } else {
    contadorResultados.innerHTML = `Se encontraron ${cantidad} juegos`;
    contadorResultados.parentElement.className =
      "alert alert-success d-flex align-items-center";
  }
}
*/

/**
 * Mostrar mensaje de error
 */
function mostrarError(mensaje) {
  contenedorResultados.innerHTML = `
    <div class="col-12">
      <div class="alert alert-danger" role="alert">
        <i class="bi bi-exclamation-triangle"></i> ${mensaje}
      </div>
    </div>
  `;
}

/**
 * Configurar eventos de filtrado
 */
function configurarEventos() {
  // Filtrar al escribir en el input (con debounce)
  let timeoutId;
  inputNombre.addEventListener("input", () => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      cargarJuegos();
    }, 500); // Esperar 500ms después de que el usuario deje de escribir
  });

  // Filtrar al cambiar género
  selectGenero.addEventListener("change", () => {
    cargarJuegos();
  });

  // Filtrar al cambiar plataforma
  selectPlataforma.addEventListener("change", () => {
    cargarJuegos();
  });
}

/**
 * Utilidades
 */

// Escapar HTML para prevenir XSS
function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

// Truncar texto
function truncarTexto(texto, maxLength) {
  if (texto.length <= maxLength) return texto;
  return texto.substring(0, maxLength) + "...";
}

// Agregar estilos para hover en las cards
const style = document.createElement("style");
style.textContent = `
  .hover-card {
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2) !important;
  }
`;
document.head.appendChild(style);

// Inicializar cuando el DOM esté listo
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", inicializar);
} else {
  inicializar();
}
