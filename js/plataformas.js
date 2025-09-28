// Mock de plataformas de ejemplo hasta que haya backend
const plataformas = [
  { id: 1, nombre: "PC" },
  { id: 2, nombre: "PS4" },
  { id: 3, nombre: "PS5" },
  { id: 4, nombre: "Nintendo 3DS" },
  { id: 5, nombre: "Nintendo Switch" },
  { id: 6, nombre: "Xbox" },
];

// inicializar
window.onload = function () {
  const tablaPlataformas = document.getElementById("tablaPlataformas");
  const btnAgregar = document.getElementById("btnAgregar");
  const plataformaModalEl = document.getElementById("plataformaModal");
  const modalTitle = plataformaModalEl.querySelector(".modal-title");
  const plataformaIdInput = document.getElementById("plataformaId");
  const nombreInput = document.getElementById("nombre");
  const formPlataforma = document.getElementById("formPlataforma");

  // datos del backend
  mostrarDatos(tablaPlataformas);

  // Configurar evento Agregar
  btnAgregar.addEventListener("click", function () {
    abrirModalAgregar(
      plataformaModalEl,
      modalTitle,
      plataformaIdInput,
      nombreInput
    );
  });

  // Configurar form
  configurarFormulario(formPlataforma);

  // Configurar submit modal
  formPlataforma.addEventListener("submit", function (event) {
    guardarPlataforma(event, tablaPlataformas, plataformaModalEl);
  });
};

// Lista de datos del backend
function mostrarDatos(tablaPlataformas) {
  tablaPlataformas.innerHTML = "";

  for (let i = 0; i < plataformas.length; i++) {
    const plataforma = plataformas[i];
    const tr = document.createElement("tr");

    const tdNombre = document.createElement("td");
    tdNombre.textContent = plataforma.nombre;
    tr.appendChild(tdNombre);

    const tdAcciones = document.createElement("td");
    tdAcciones.className = "text-center";
    const btnEditar = document.createElement("button");
    btnEditar.textContent = "Editar";
    btnEditar.className = "btn btn-warning btn-sm me-2";
    btnEditar.addEventListener("click", function () {
      abrirModalEditar(plataforma);
    });

    const btnEliminar = document.createElement("button");
    btnEliminar.textContent = "Eliminar";
    btnEliminar.className = "btn btn-danger btn-sm";
    btnEliminar.addEventListener("click", function () {
      eliminarPlataforma(plataforma.id, tablaPlataformas);
    });

    tdAcciones.appendChild(btnEditar);
    tdAcciones.appendChild(btnEliminar);

    tr.appendChild(tdAcciones);
    tablaPlataformas.appendChild(tr);
  }
}

// modal agregar
function abrirModalAgregar(
  plataformaModal,
  modalTitle,
  plataformaIdInput,
  nombreInput
) {
  plataformaIdInput.value = "";
  nombreInput.value = "";
  modalTitle.textContent = "Agregar Plataforma";
  const modal = new bootstrap.Modal(plataformaModal);
  modal.show();
}

// modal editar
function abrirModalEditar(plataforma) {
  const plataformaModal = document.getElementById("plataformaModal");
  const modalTitle = plataformaModal.querySelector(".modal-title");
  const plataformaIdInput = document.getElementById("plataformaId");
  const nombreInput = document.getElementById("nombre");
  plataformaIdInput.value = plataforma.id;
  nombreInput.value = plataforma.nombre;
  modalTitle.textContent = "Editar Plataforma";
  const modal = new bootstrap.Modal(plataformaModal);
  modal.show();
}

// Guardar
function guardarPlataforma(event, tablaPlataformas, plataformaModalEl) {
  event.preventDefault();

  const formPlataforma = document.getElementById("formPlataforma");

  if (!formPlataforma.checkValidity()) {
    formPlataforma.classList.add("was-validated");
    return;
  }

  const plataformaIdInput = document.getElementById("plataformaId");
  const nombreInput = document.getElementById("nombre");
  const id = parseInt(plataformaIdInput.value, 10);
  const nombre = nombreInput.value.trim();

  if (id) {
    // Actualizar
    for (let i = 0; i < plataformas.length; i++) {
      if (plataformas[i].id === id) {
        plataformas[i].nombre = nombre;
        break;
      }
    }
  } else {
    // Agregar
    const nuevoId =
      plataformas.length > 0 ? plataformas[plataformas.length - 1].id + 1 : 1;
    plataformas.push({ id: nuevoId, nombre: nombre });
  }

  // Cerrar
  const modal = bootstrap.Modal.getInstance(plataformaModalEl);
  modal.hide();

  // Limpiar
  formPlataforma.reset();
  formPlataforma.classList.remove("was-validated");

  mostrarDatos(tablaPlataformas);
}

// Eliminar
function eliminarPlataforma(id, tablaPlataformas) {
  const index = plataformas.findIndex(function (e) {
    return e.id === id;
  });
  if (index !== -1) {
    // desde el elemento index, eliminar 1
    plataformas.splice(index, 1);
  }
  mostrarDatos(tablaPlataformas);
}

function configurarFormulario(miFormulario) {
  miFormulario.addEventListener("submit", function (event) {
    if (!miFormulario.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
    }
    miFormulario.classList.add("was-validated");
  });
}
