// Obtener elementos
const inputNombre = document.getElementById("filtroNombre");
const selectGenero = document.getElementById("filtroGenero");
const selectPlataforma = document.getElementById("filtroPlataforma");
const contenedor = document.getElementById("contenedorResultados");
const items = Array.from(contenedor.children);

// Función para filtrar
function filtrarVideojuegos() {
  const textoNombre = inputNombre.value.toLowerCase();
  const generoSeleccionado = selectGenero.value;
  const plataformaSeleccionada = selectPlataforma.value;

  items.forEach((item) => {
    const nombre = item.querySelector(".card-title").textContent.toLowerCase();
    const genero = item.dataset.genero;
    const plataforma = item.dataset.plataforma;

    const coincideNombre = nombre.includes(textoNombre);
    const coincideGenero =
      generoSeleccionado === "" || genero === generoSeleccionado;
    const coincidePlataforma =
      plataformaSeleccionada === "" || plataforma === plataformaSeleccionada;

    if (coincideNombre && coincideGenero && coincidePlataforma) {
      item.classList.remove("d-none");
    } else {
      item.classList.add("d-none");
    }
  });
}

// Eventos para filtrar en tiempo real
inputNombre.addEventListener("input", filtrarVideojuegos);
selectGenero.addEventListener("change", filtrarVideojuegos);
selectPlataforma.addEventListener("change", filtrarVideojuegos);

// Mostrar al inicio
filtrarVideojuegos();
