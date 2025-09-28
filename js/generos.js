// Mock de generos de ejemplo hasta que haya backend
const generos = [
  { id: 1, nombre: "Accion" },
  { id: 2, nombre: "Aventura" },
  { id: 3, nombre: "Rol" },
  { id: 3, nombre: "Shooter" },
  { id: 3, nombre: "Deportes" },
  { id: 3, nombre: "Battle Royale" },
];

// inicializar
window.onload = function () {
  const tablaGeneros = document.getElementById("tablaGeneros");
  const btnAgregar = document.getElementById("btnAgregar");
  const generoModalEl = document.getElementById("generoModal");
  const modalTitle = generoModalEl.querySelector(".modal-title");
  const generoIdInput = document.getElementById("generoId");
  const nombreInput = document.getElementById("nombre");
  const formGenero = document.getElementById("formGenero");

  // datos del backend
  mostrarDatos(tablaGeneros);

  // Configurar evento Agregar
  btnAgregar.addEventListener("click", function () {
    abrirModalAgregar(generoModalEl, modalTitle, generoIdInput, nombreInput);
  });

  // Configurar form
  configurarFormulario(formGenero);

  // Configurar submit modal
  formGenero.addEventListener("submit", function (event) {
    guardarGenero(event, tablaGeneros, generoModalEl);
  });
};

// Lista de datos del backend
function mostrarDatos(tablaGeneros) {
  tablaGeneros.innerHTML = "";

  for (let i = 0; i < generos.length; i++) {
    const genero = generos[i];
    const tr = document.createElement("tr");

    const tdNombre = document.createElement("td");
    tdNombre.textContent = genero.nombre;
    tr.appendChild(tdNombre);

    const tdAcciones = document.createElement("td");
    tdAcciones.className = "text-center";
    const btnEditar = document.createElement("button");
    btnEditar.textContent = "Editar";
    btnEditar.className = "btn btn-warning btn-sm me-2";
    btnEditar.addEventListener("click", function () {
      abrirModalEditar(genero);
    });

    const btnEliminar = document.createElement("button");
    btnEliminar.textContent = "Eliminar";
    btnEliminar.className = "btn btn-danger btn-sm";
    btnEliminar.addEventListener("click", function () {
      eliminarGenero(genero.id, tablaGeneros);
    });

    tdAcciones.appendChild(btnEditar);
    tdAcciones.appendChild(btnEliminar);

    tr.appendChild(tdAcciones);
    tablaGeneros.appendChild(tr);
  }
}

// modal agregar
function abrirModalAgregar(
  generoModal,
  modalTitle,
  generoIdInput,
  nombreInput
) {
  generoIdInput.value = "";
  nombreInput.value = "";
  modalTitle.textContent = "Agregar Género";
  const modal = new bootstrap.Modal(generoModal);
  modal.show();
}

// modal editar
function abrirModalEditar(genero) {
  const generoModal = document.getElementById("generoModal");
  const modalTitle = generoModal.querySelector(".modal-title");
  const generoIdInput = document.getElementById("generoId");
  const nombreInput = document.getElementById("nombre");
  generoIdInput.value = genero.id;
  nombreInput.value = genero.nombre;
  modalTitle.textContent = "Editar Género";
  const modal = new bootstrap.Modal(generoModal);
  modal.show();
}

// Guardar
function guardarGenero(event, tablaGeneros, generoModalEl) {
  event.preventDefault();

  const formGenero = document.getElementById("formGenero");

  if (!formGenero.checkValidity()) {
    formGenero.classList.add("was-validated");
    return;
  }

  const generoIdInput = document.getElementById("generoId");
  const nombreInput = document.getElementById("nombre");
  const id = parseInt(generoIdInput.value, 10);
  const nombre = nombreInput.value.trim();

  if (id) {
    // Actualizar
    for (let i = 0; i < generos.length; i++) {
      if (generos[i].id === id) {
        generos[i].nombre = nombre;
        break;
      }
    }
  } else {
    // Agregar
    const nuevoId = generos.length > 0 ? generos[generos.length - 1].id + 1 : 1;
    generos.push({ id: nuevoId, nombre: nombre });
  }

  // Cerrar
  const modal = bootstrap.Modal.getInstance(generoModalEl);
  modal.hide();

  // Limpiar
  formGenero.reset();
  formGenero.classList.remove("was-validated");

  mostrarDatos(tablaGeneros);
}

// Eliminar
function eliminarGenero(id, tablaGeneros) {
  const index = generos.findIndex(function (e) {
    return e.id === id;
  });
  if (index !== -1) {
    // desde el elemento index, eliminar 1
    generos.splice(index, 1);
  }
  mostrarDatos(tablaGeneros);
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
