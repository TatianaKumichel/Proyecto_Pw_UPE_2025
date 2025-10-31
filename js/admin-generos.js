window.onload = function () {
  const tabla = document.getElementById("tablaGeneros");
  const form = document.getElementById("formGenero");
  const modalGenero = new bootstrap.Modal(
    document.getElementById("modalGenero")
  );
  const modalEliminar = new bootstrap.Modal(
    document.getElementById("modalEliminar")
  );
  const btnNuevo = document.getElementById("btnNuevoGenero");
  const btnConfirmarEliminar = document.getElementById("btnConfirmarEliminar");
  const inputId = document.getElementById("idGenero");
  const inputNombre = document.getElementById("nombre");
  let idAEliminar = null;

  cargarGeneros();

  btnNuevo.addEventListener("click", function () {
    form.reset();
    form.classList.remove("was-validated");
    inputId.value = "";
    document.getElementById("modalGeneroLabel").textContent = "Nuevo Género";
  });

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    if (!form.checkValidity()) {
      form.classList.add("was-validated");
      return;
    }

    const datos = { nombre: inputNombre.value.trim() };
    const url = inputId.value ? "updateGenero.php" : "insertGenero.php";
    if (inputId.value) datos.id_genero = inputId.value;

    fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(datos),
    })
      .then((r) => r.json())
      .then((res) => {
        if (res.ok) {
          modalGenero.hide();
          cargarGeneros();
        } else {
          mostrarError(res.error || "Error al guardar género.");
        }
      })
      .catch(() => mostrarError("Error de conexión al guardar género."));
  });

  btnConfirmarEliminar.addEventListener("click", function () {
    if (!idAEliminar) return;

    fetch("delGenero.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id_genero: idAEliminar }),
    })
      .then((r) => r.json())
      .then((res) => {
        if (res.ok) {
          modalEliminar.hide();
          cargarGeneros();
        } else {
          mostrarError(res.error || "Error al eliminar género.");
        }
      })
      .catch(() => mostrarError("Error de conexión al eliminar género."));
  });

  function cargarGeneros() {
    fetch("getGenero.php")
      .then((r) => r.json())
      .then((data) => {
        tabla.innerHTML = "";
        data.forEach((genero) => agregarFila(genero));
      })
      .catch(() => mostrarError("Error al cargar géneros."));
  }

  function agregarFila(genero) {
    const fila = document.createElement("tr");

    const tdId = document.createElement("td");
    tdId.textContent = genero.id_genero;

    const tdNombre = document.createElement("td");
    tdNombre.textContent = genero.nombre;

    const tdAcciones = document.createElement("td");
    const btnEditar = crearBoton("btn-warning", "bi-pencil-fill", function () {
      inputId.value = genero.id_genero;
      inputNombre.value = genero.nombre;
      document.getElementById("modalGeneroLabel").textContent = "Editar Género";
      modalGenero.show();
    });

    const btnEliminar = crearBoton("btn-danger", "bi-trash-fill", function () {
      idAEliminar = genero.id_genero;
      modalEliminar.show();
    });

    tdAcciones.appendChild(btnEditar);
    tdAcciones.appendChild(btnEliminar);

    fila.appendChild(tdId);
    fila.appendChild(tdNombre);
    fila.appendChild(tdAcciones);
    tabla.appendChild(fila);
  }

  function crearBoton(colorClase, iconoClase, onClick) {
    const btn = document.createElement("button");
    btn.className = `btn btn-sm me-2 ${colorClase}`;
    btn.type = "button";
    btn.innerHTML = `<i class="bi ${iconoClase}"></i>`;
    btn.addEventListener("click", onClick);
    return btn;
  }

  function mostrarError(msg) {
    const div = document.getElementById("divErroresGenerales");
    div.textContent = msg;
    div.classList.remove("d-none");
    setTimeout(() => div.classList.add("d-none"), 4000);
  }
};
