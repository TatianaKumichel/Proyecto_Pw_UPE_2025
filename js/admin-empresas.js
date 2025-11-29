window.onload = function () {
  CargarEmpresas();

  const btnAgregarEmpresa = document.getElementById("btnAgregarEmpresa");
  const formEmpresa = document.getElementById("formEmpresa");
  const btnConfirmarEliminacion = document.getElementById("btnConfirmarEliminar");

  btnAgregarEmpresa.addEventListener("click", function () {
    LimpiarForm();
    formEmpresa.dataset.mode = "create";

    document.querySelector("#modalNuevaEmpresa .modal-title").innerText = "Nueva Empresa";
    document.getElementById("btnGuardarEmpresa").innerText = "Guardar";

    MostrarModal();
  });

  document.addEventListener("click", async function (event) {
    const botonEdit = event.target.closest(".btnEditarEmpresa");
    LimpiarErrores();
    if (botonEdit) {
      const id = botonEdit.getAttribute("data-id");

      // modifico el modal para editar
      formEmpresa.dataset.mode = "edit";
      formEmpresa.dataset.id = id;

      await CargarEmpresaPorId(id);

      document.querySelector("#modalNuevaEmpresa .modal-title").innerText = "Editar";
      document.getElementById("btnGuardarEmpresa").innerText = "Guardar Cambios";
      document.getElementById("descripcionForm").innerText = "Edita la empresa";
      MostrarModal();
    }
  });

  document.addEventListener("click", function (event) {
    const botonElim = event.target.closest(".btnEliminarEmpresa");

    if (botonElim) {
      const id_empresa = botonElim.getAttribute("data-id");
      const modalEliminacion = document.getElementById("modalEliminarEmpresa");
      modalEliminacion.dataset.idEmpresa = id_empresa;
    }
  });

  btnConfirmarEliminacion.addEventListener("click", function () {
    const modalEliminacion = document.getElementById("modalEliminarEmpresa");
    const idEmpresa = modalEliminacion.dataset.idEmpresa;

    if (idEmpresa) {
      EliminarEmpresa(idEmpresa);
    }
  });



  formEmpresa.addEventListener("submit", function (evento) {
    ValidarGuardarEmpresa(evento);
  });



}


function ValidarGuardarEmpresa(event) {
  event.preventDefault();

  const NombreEmpresa = document.getElementById("nombreEmpresa");
  let ErrornombreEmpresa = document.getElementById("ErrornombreEmpresa");
  const sitioWeb = document.getElementById("sitioWeb");
  let ErrorsitioWeb = document.getElementById("ErrorsitioWeb");
  let validacion = true;

  if (NombreEmpresa.value.trim() === "") {

    NombreEmpresa.classList.add("is-invalid");
    NombreEmpresa.classList.remove("is-valid");
    ErrornombreEmpresa.innerHTML = "Debe ingresar un nombre";
    validacion = false;

  } else {
    NombreEmpresa.classList.remove("is-invalid");
    NombreEmpresa.classList.add("is-valid");
    ErrornombreEmpresa.innerHTML = "";


  }

  if (sitioWeb.value.trim() === "") {
    sitioWeb.classList.add("is-invalid");
    sitioWeb.classList.remove("is-valid");
    ErrorsitioWeb.innerHTML = "Debe ingresar un sitio web para la empresa";
    validacion = false;

  } else {
    sitioWeb.classList.remove("is-invalid");
    sitioWeb.classList.add("is-valid");
    ErrorsitioWeb.innerHTML = "";


  }



  if (validacion) {
    GuardarEmpresa();
  }



}


function CargarEmpresas() {
  fetch("./bd/gestion-empresas/obtener-empresas.php")
    .then(response => {
      if (!response.ok) {
        throw new Error("Error en la respuesta del servidor");
      }
      return response.json();
    })
    .then(data => {
      MostrarEmpresas(data);
    })
    .catch(error => {
      console.error("Error al cargar empresas:", error);
      MostrarError("Ocurrió un error al cargar las Empresas. Verifique su conexión o inténtelo más tarde.");
    });
}



async function CargarEmpresaPorId(id) {
  try {
    let res = await fetch(`./bd/gestion-empresas/obtener-empresa.php?id_empresa=${id}`);
    let response = await res.json();

    if (!response.success) {
      console.error("Error:", response.message);
      return;
    }

    let data = response.data;
    document.getElementById("nombreEmpresa").value = data.nombre;
    document.getElementById("sitioWeb").value = data.sitio_web;

  } catch (err) {
    console.error("Error ", err);
  }
}


function MostrarEmpresas(empresas) {
  const contenedor = document.getElementById("tablaEmpresas");
  contenedor.innerHTML = "";
  if (!empresas || empresas.length === 0) {
    contenedor.innerHTML = `
             <tr>
      <td colspan="3" class="text-center py-5">
        <div class="d-flex flex-column align-items-center text-secondary">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none"
            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-database">
            <ellipse cx="12" cy="5" rx="9" ry="3" />
            <path d="M3 5v14c0 1.7 4 3 9 3s9-1.3 9-3V5" />
            <path d="M3 12c0 1.7 4 3 9 3s9-1.3 9-3" />
          </svg>
          <p class="mt-3 mb-0">No hay empresas registradas aún</p>
          <small class="text-muted">Usa el botón “Nueva Empresa” para agregar una.</small>
        </div>
      </td>
    </tr>
        `;
    return;
  }
  empresas.forEach(empresa => {
    contenedor.innerHTML += `
  <tr>
    <td>${empresa.nombre}</td>

    <td>
      ${empresa.sitio_web
        ? `<a href="${empresa.sitio_web}" target="_blank">${empresa.sitio_web}</a>`
        : "-"
      }
    </td>

    <td class="text-center">

      <button class="btn btn-warning btn-sm btnEditarEmpresa" data-id="${empresa.id_empresa}">
        <i class="bi bi-pencil-square"></i>
      </button>

      <button class="btn btn-danger btn-sm btnEliminarEmpresa"
              data-bs-toggle="modal"
              data-bs-target="#modalEliminarEmpresa"
              data-id="${empresa.id_empresa}">
        <i class="bi bi-trash"></i>
      </button>

    </td>
  </tr>
`;
  });
}


async function EliminarEmpresa(idEmpresa) {
  const modal = document.getElementById("modalEliminarEmpresa");
  const modalBootstrap = bootstrap.Modal.getOrCreateInstance(modal);

  try {
    const formData = new FormData();
    formData.append("id", idEmpresa);

    const response = await fetch("./bd/gestion-empresas/eliminar-empresa.php", {
      method: "POST",
      body: formData
    });

    const result = await response.json();

    if (result.success) {
      modalBootstrap.hide();
      CargarEmpresas();
      MostrarExito("¡Eliminada!", "La empresa fue eliminada correctamente.");
    } else {
      modalBootstrap.hide();
      MostrarError(result.message || "No se pudo eliminar la empresa.");
    }

  } catch (err) {
    console.error("Error:", err);
    modalBootstrap.hide();
    MostrarError("Error inesperado al eliminar la empresa.");
  }
}


async function GuardarEmpresa() {
  const form = document.getElementById("formEmpresa");
  const formData = new FormData();
  LimpiarErrores();
  formData.append("nombre_empresa", document.getElementById("nombreEmpresa").value.trim());
  formData.append("sitio_web", document.getElementById("sitioWeb").value.trim());

  if (form.dataset.mode === "edit") {
    formData.append("id", form.dataset.id);
  }

  try {
    const response = await fetch("./bd/gestion-empresas/guardar-empresa.php", {
      method: "POST",
      body: formData
    });

    const result = await response.json();

    if (result.success) {  // se guardo

      CerrarModal();
      CargarEmpresas();
      MostrarExito("¡Éxito!", result.message);
    } else if (result.errors) {
      // errores abajo de los input
      if (result.errors.nombre_empresa) {
        const nombreEmpresa = document.getElementById("nombreEmpresa");
        nombreEmpresa.classList.add("is-invalid");
        document.getElementById("ErrornombreEmpresa").innerText = result.errors.nombre_empresa;
      }

      if (result.errors.sitio_web) {
        const sitioWeb = document.getElementById("sitioWeb");
        sitioWeb.classList.add("is-invalid");
        document.getElementById("ErrorsitioWeb").innerText = result.errors.sitio_web;
      }

      if (result.errors.general) {
        MostrarError(result.errors.general);
      }
    }

  } catch (err) {
    console.error("Error:", err);
    MostrarError("Error inesperado al guardar la empresa.");
  }
}





///




function MostrarModal() {

  const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalNuevaEmpresa"));
  modal.show();

}
function CerrarModal() {
  const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalNuevaEmpresa"));
  modal.hide();

}


function LimpiarForm() {

  document.getElementById("formEmpresa").reset();
  LimpiarErrores();
}

function LimpiarErrores() {
  document.getElementById("nombreEmpresa").classList.remove("is-valid", "is-invalid");
  document.getElementById("sitioWeb").classList.remove("is-valid", "is-invalid");
}



// modales de exito - error 
function MostrarExito(titulo = "Éxito", mensaje = "Operación realizada correctamente") {
  const modalExito = document.getElementById("modalExito");

  document.getElementById("titulo-exito").innerText = titulo;
  modalExito.querySelector(".modal-body p").innerText = mensaje;

  const instancia = bootstrap.Modal.getOrCreateInstance(modalExito);
  instancia.show();
}


function MostrarError(mensaje = "Ocurrió un error inesperado") {
  document.getElementById("mensaje-error").innerText = mensaje;
  const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalError"));
  modal.show();
}