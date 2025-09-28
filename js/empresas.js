// Mock de empresas de ejemplo hasta que haya backend
const empresas = [
  { id: 1, nombre: "Ubisoft" },
  { id: 2, nombre: "Nintendo" },
  { id: 3, nombre: "Sony" },
];

// inicializar
window.onload = function () {
  const tablaEmpresas = document.getElementById("tablaEmpresas");
  const btnAgregar = document.getElementById("btnAgregar");
  const empresaModalEl = document.getElementById("empresaModal");
  const modalTitle = empresaModalEl.querySelector(".modal-title");
  const empresaIdInput = document.getElementById("empresaId");
  const nombreInput = document.getElementById("nombre");
  const formEmpresa = document.getElementById("formEmpresa");

  // datos del backend
  mostrarDatos(tablaEmpresas);

  // Configurar evento Agregar
  btnAgregar.addEventListener("click", function () {
    abrirModalAgregar(empresaModalEl, modalTitle, empresaIdInput, nombreInput);
  });

  // Configurar form
  configurarFormulario(formEmpresa);

  // Configurar submit modal
  formEmpresa.addEventListener("submit", function (event) {
    guardarEmpresa(event, tablaEmpresas, empresaModalEl);
  });
};

// Lista de datos del backend
function mostrarDatos(tablaEmpresas) {
  tablaEmpresas.innerHTML = "";

  for (let i = 0; i < empresas.length; i++) {
    const empresa = empresas[i];
    const tr = document.createElement("tr");

    const tdNombre = document.createElement("td");
    tdNombre.textContent = empresa.nombre;
    tr.appendChild(tdNombre);

    const tdAcciones = document.createElement("td");
    tdAcciones.className = "text-center";
    const btnEditar = document.createElement("button");
    btnEditar.textContent = "Editar";
    btnEditar.className = "btn btn-warning btn-sm me-2";
    btnEditar.addEventListener("click", function () {
      abrirModalEditar(empresa);
    });

    const btnEliminar = document.createElement("button");
    btnEliminar.textContent = "Eliminar";
    btnEliminar.className = "btn btn-danger btn-sm";
    btnEliminar.addEventListener("click", function () {
      eliminarEmpresa(empresa.id, tablaEmpresas);
    });

    tdAcciones.appendChild(btnEditar);
    tdAcciones.appendChild(btnEliminar);

    tr.appendChild(tdAcciones);
    tablaEmpresas.appendChild(tr);
  }
}

// modal agregar
function abrirModalAgregar(
  empresaModal,
  modalTitle,
  empresaIdInput,
  nombreInput
) {
  empresaIdInput.value = "";
  nombreInput.value = "";
  modalTitle.textContent = "Agregar Empresa";
  const modal = new bootstrap.Modal(empresaModal);
  modal.show();
}

// modal editar
function abrirModalEditar(empresa) {
  const empresaModal = document.getElementById("empresaModal");
  const modalTitle = empresaModal.querySelector(".modal-title");
  const empresaIdInput = document.getElementById("empresaId");
  const nombreInput = document.getElementById("nombre");
  empresaIdInput.value = empresa.id;
  nombreInput.value = empresa.nombre;
  modalTitle.textContent = "Editar Empresa";
  const modal = new bootstrap.Modal(empresaModal);
  modal.show();
}

// Guardar
function guardarEmpresa(event, tablaEmpresas, empresaModalEl) {
  event.preventDefault();

  const formEmpresa = document.getElementById("formEmpresa");

  if (!formEmpresa.checkValidity()) {
    formEmpresa.classList.add("was-validated");
    return;
  }

  const empresaIdInput = document.getElementById("empresaId");
  const nombreInput = document.getElementById("nombre");
  const id = parseInt(empresaIdInput.value, 10);
  const nombre = nombreInput.value.trim();

  if (id) {
    // Actualizar
    for (let i = 0; i < empresas.length; i++) {
      if (empresas[i].id === id) {
        empresas[i].nombre = nombre;
        break;
      }
    }
  } else {
    // Agregar
    const nuevoId =
      empresas.length > 0 ? empresas[empresas.length - 1].id + 1 : 1;
    empresas.push({ id: nuevoId, nombre: nombre });
  }

  // Cerrar
  const modal = bootstrap.Modal.getInstance(empresaModalEl);
  modal.hide();

  // Limpiar
  formEmpresa.reset();
  formEmpresa.classList.remove("was-validated");

  mostrarDatos(tablaEmpresas);
}

// Eliminar
function eliminarEmpresa(id, tablaEmpresas) {
  const index = empresas.findIndex(function (e) {
    return e.id === id;
  });
  if (index !== -1) {
    // desde el elemento index, eliminar 1
    empresas.splice(index, 1);
  }
  mostrarDatos(tablaEmpresas);
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
