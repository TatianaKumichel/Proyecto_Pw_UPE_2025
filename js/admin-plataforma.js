window.onload = function () {
  CargarPlataformas();

  const btnNuevaPlataforma = document.getElementById("btnAgregarPlataforma");
  const formPlataforma = document.getElementById("formPlataformaModal");
  const btnConfirmarEliminacion = document.getElementById("confirmYes");


  btnNuevaPlataforma.addEventListener("click", function () {
    LimpiarForm();
    formPlataforma.dataset.mode = "create";

    document.querySelector("#plataformaModal .modal-title").innerText = "Nueva Plataforma";
    document.getElementById("btnGuardarPlataforma").innerText = "Guardar";

    MostrarModal();
  });


  document.addEventListener("click", async function (event) {

    const btnEdit = event.target.closest(".btn-edit");

    if (btnEdit) {
      LimpiarErroresPlataforma();

      const id = btnEdit.getAttribute("data-id");

      formPlataforma.dataset.mode = "edit";
      formPlataforma.dataset.id = id;

      await CargarPlataformaPorId(id);

      document.querySelector("#plataformaModal .modal-title").innerText = "Editar Plataforma";
      document.getElementById("btnGuardarPlataforma").innerText = "Guardar Cambios";

      MostrarModal();
    }
  });


  document.addEventListener("click", function (event) {

    const btnDelete = event.target.closest(".btn-delete");

    if (btnDelete) {
      const id = btnDelete.getAttribute("data-id");
      const modalEliminar = document.getElementById("confirmModal");
      modalEliminar.dataset.idPlataforma = id;
    }
  });

  btnConfirmarEliminacion.addEventListener("click", function () {
    const modal = document.getElementById("confirmModal");
    const id = modal.dataset.idPlataforma;

    if (id) {
      EliminarPlataforma(id);
    }
  });


  formPlataforma.addEventListener("submit", function (evento) {
    evento.preventDefault();

    if (!ValidarNombre()) return;

    GuardarPlataforma();
  });

};



function MostrarModal() {
  bootstrap.Modal.getOrCreateInstance(document.getElementById("plataformaModal")).show();
}

function CerrarModal() {
  bootstrap.Modal.getOrCreateInstance(document.getElementById("plataformaModal")).hide();
}

function LimpiarForm() {
  document.getElementById("formPlataformaModal").reset();
  LimpiarErrores();
}

function LimpiarErrores() {
  const input = document.getElementById("modalNombrePlataforma");
  input.classList.remove("is-valid", "is-invalid");
  document.getElementById("ErrorNombrePlataforma").innerText = "";
}



function ValidarNombre() {
  const input = document.getElementById("modalNombrePlataforma");
  const error = document.getElementById("ErrorNombrePlataforma");

  if (input.value.trim() === "") {
    input.classList.add("is-invalid");
    error.innerText = "Debe ingresar un nombre";
    return false;
  }

  input.classList.remove("is-invalid");
  input.classList.add("is-valid");
  error.innerText = "";
  return true;
}



async function CargarPlataformas() {
  try {
    const res = await fetch("./bd/gestion-juegos/obtener-plataformas.php");
    const data = await res.json();

    const tabla = document.getElementById("tablaPlataformas");
    tabla.innerHTML = "";

    if (!data.success || data.data.length === 0) {
      tabla.innerHTML = `<tr><td colspan='3'>No hay plataformas registradas</td></tr>`;
      return;
    }

    data.data.forEach(p => {
      tabla.innerHTML += `
        <tr>
          <td>${p.id_plataforma}</td>
          <td>${p.nombre}</td>
          <td>
            <button class="btn btn-warning btn-sm btn-edit" data-id="${p.id_plataforma}">
              <i class="bi bi-pencil-square"></i>
            </button>
            <button class="btn btn-danger btn-sm btn-delete" data-id="${p.id_plataforma}"
                    data-bs-toggle="modal" data-bs-target="#confirmModal">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>`;
    });

  } catch (err) {
    console.error(err);
    MostrarError("Ocurrió un error al cargar las plataformas.");
  }
}



async function CargarPlataformaPorId(id) {
  try {
    const res = await fetch(`./bd/gestion-plataformas/obtener-plataforma.php?id=${id}`);
    const data = await res.json();

    if (!data.success) {
      MostrarError(data.message || "No se pudo cargar la plataforma");
      return;
    }

    document.getElementById("modalNombrePlataforma").value = data.data.nombre;

  } catch (err) {
    console.error(err);
    MostrarError("Error al cargar la plataforma.");
  }
}


async function GuardarPlataforma() {
  const form = document.getElementById("formPlataformaModal");
  const formData = new FormData();

  LimpiarErroresPlataforma();

  const nombreInput = document.getElementById("modalNombrePlataforma");
  const nombre = nombreInput.value.trim();

  formData.append("nombre", nombre);
  formData.append("action", form.dataset.mode);

  if (form.dataset.mode === "edit") {
    formData.append("id", form.dataset.id);
  }

  try {
    const response = await fetch("./bd/gestion-plataformas/guardar-plataforma.php", {
      method: "POST",
      body: formData
    });

    const result = await response.json();

    if (result.success) {
      CerrarModal();
      CargarPlataformas();
      MostrarExito("Éxito", result.message);

    } else if (result.errors) {



      if (result.errors.nombre) {
        nombreInput.classList.add("is-invalid");
        document.getElementById("ErrorNombrePlataforma").innerText = result.errors.nombre;
      }


      if (result.errors.general) {
        MostrarError("Error", result.errors.general);
      }
    }

  } catch (err) {
    console.error("Error:", err);
    MostrarError("Error inesperado al guardar la plataforma.");
  }
}




async function EliminarPlataforma(id) {
  try {
    const res = await fetch("./bd/gestion-plataformas/eliminar-plataforma.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    });

    const data = await res.json();

    bootstrap.Modal.getOrCreateInstance(document.getElementById("confirmModal")).hide();

    if (data.success) {
      CargarPlataformas();
      MostrarExito("Eliminada", data.message);
    } else {
      MostrarError(data.message || "No se pudo eliminar.");
    }

  } catch (err) {
    console.error(err);
    MostrarError("Error inesperado al eliminar.");
  }
}
function LimpiarErroresPlataforma() {
  const nombre = document.getElementById("modalNombrePlataforma");
  nombre.classList.remove("is-invalid", "is-valid");
  document.getElementById("ErrorNombrePlataforma").innerText = "";
}


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
function LimpiarForm() {

  document.getElementById("formPlataformaModal").reset();
  LimpiarErroresPlataforma();
}
