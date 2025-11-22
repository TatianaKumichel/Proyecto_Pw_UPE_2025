/**
   * Cargar tendencias (carousel)
   */
 async function cargarTendencias() {
    try {
      const response = await fetch("./bd/juegos/getTendencias.php");
      const data = await response.json();
  
      const carouselInner = document.querySelector(
        "#tendenciasCarousel .carousel-inner"
      );
      const indicators = document.querySelector(
        "#tendenciasCarousel .carousel-indicators"
      );
      const noDataDiv = document.getElementById("noTendencias");
  
      if (data.success && data.juegos.length > 0) {
        carouselInner.innerHTML = "";
        indicators.innerHTML = "";
  
        data.juegos.forEach((juego, index) => {
          // Crear item del carousel
          const item = crearItemCarousel(juego, index);
          carouselInner.appendChild(item);
  
          // Crear indicador
          const indicator = document.createElement("button");
          indicator.type = "button";
          indicator.setAttribute("data-bs-target", "#tendenciasCarousel");
          indicator.setAttribute("data-bs-slide-to", index);
          indicator.setAttribute("aria-label", `Slide ${index + 1}`);

          if (index === 0) indicator.classList.add("active");
          indicators.appendChild(indicator);
        });
  
        document.getElementById("tendenciasCarousel").classList.remove("d-none");
        noDataDiv.classList.add("d-none");
      } else {
        document.getElementById("tendenciasCarousel").classList.add("d-none");
        noDataDiv.classList.remove("d-none");
      }
    } catch (error) {
      console.error("Error:", error);
    }
  }

   
  /**
   * Crear item de carousel
   */
   function crearItemCarousel(juego, index) {
    const div = document.createElement("div");
    div.className = `carousel-item${index === 0 ? " active" : ""}`;
  
    const imagenUrl = juego.imagen_portada || "./img/juego-default.png";
    const estrellas = mostrarEstrellas(juego.calificacion_promedio);
  
    div.innerHTML = `
      <a href="detalle.php?id_juego=${juego.id_juego}">
        <img src="${imagenUrl}" 
             class="d-block w-100" 
             alt="${escapeHtml(juego.titulo)}"
             onerror="this.src='./img/juego-default.png'">
        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-75 rounded p-2">
          <h5>${escapeHtml(juego.titulo)}</h5>
          <p class="mb-0">
            ${estrellas}
            <strong>${juego.calificacion_promedio}</strong> / 5.0
            <small>(${juego.total_calificaciones} calificaciones)</small>
          </p>
        </div>
      </a>
    `;
    return div;
  }

  /**
   * Mostrar estrellas según calificación
   */
   function mostrarEstrellas(calificacion) {
    const estrellasLlenas = Math.floor(calificacion);
    const tieneMedia = calificacion % 1 >= 0.5;
    const estrellasVacias = 5 - estrellasLlenas - (tieneMedia ? 1 : 0);
  
    let html = "";
  
    // Estrellas llenas
    for (let i = 0; i < estrellasLlenas; i++) {
      html += '<i class="bi bi-star-fill text-warning"></i>';
    }
  
    // Media estrella
    if (tieneMedia) {
      html += '<i class="bi bi-star-half text-warning"></i>';
    }
  
    // Estrellas vacías
    for (let i = 0; i < estrellasVacias; i++) {
      html += '<i class="bi bi-star text-warning"></i>';
    }
  
    return html;
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
    cargarTendencias();
  });