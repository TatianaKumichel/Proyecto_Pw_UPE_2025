/**
 * Cargar próximos lanzamientos
 */
 async function cargarProximosLanzamientos() {
  try {
    const response = await fetch("./bd/juegos/getProximosLanzamientos.php");
    const data = await response.json();
/*     const raw = await response.text();
    console.log("RAW RESPONSE:", raw);
    return; */
    const contenedor = document.getElementById("proximosLanzamientos");
    const noDataDiv = document.getElementById("noProximosLanzamientos");

    if (data.success && data.juegos.length > 0) {
      contenedor.innerHTML = "";
      data.juegos.forEach((juego) => {
        const card = crearCardJuego(juego);
        contenedor.appendChild(card);
      });
      noDataDiv.classList.add("d-none");
    } else {
      contenedor.innerHTML = "";
      noDataDiv.classList.remove("d-none");
    }
  } catch (error) {
    console.error("Error:", error);
  }
}
  
  
  
/**
 * Crear card de juego (sin botón)
 */
 function crearCardJuego(juego) {
  const col = document.createElement("div");
  col.className = "col-12 col-sm-6 col-md-4";

  const imagenUrl = juego.imagen_portada || "./img/juego-default.png";

  col.innerHTML = `
    <div class="card h-100 shadow-sm">
    <a href="detalle.php?id_juego=${
      juego.id_juego
    }" class="text-decoration-none"> <img src="${imagenUrl}" 
           class="card-img-top" 
           alt="${escapeHtml(juego.titulo)}"
           onerror="this.src='./img/juego-default.png'"> </a>
      <div class="card-body">
        <h5 class="card-title">${escapeHtml(juego.titulo)}</h5>
        <p class="card-text text-muted small mb-2">
          <i class="bi bi-building"></i> ${escapeHtml(juego.empresa)}
        </p>
        ${
          juego.generos
            ? `
          <p class="card-text small mb-1">
            <i class="bi bi-tags"></i> ${escapeHtml(juego.generos)}
          </p>
        `
            : ""
        }
        ${
          juego.plataformas
            ? `
          <p class="card-text small mb-0">
            <i class="bi bi-display"></i> ${escapeHtml(juego.plataformas)}
          </p>
        `
            : ""
        }
      </div>
    </div>
  `;

  return col;
}
  
  /**
   * Escapar HTML
   */
function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}
  
  /**
   * Inicializar al cargar la página
   */
  document.addEventListener("DOMContentLoaded", () => {
    cargarProximosLanzamientos();
  });