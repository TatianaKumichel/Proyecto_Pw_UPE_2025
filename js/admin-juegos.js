window.onload = function () {
  const tablaJuegos = document.getElementById("tabla-juegos");
  const formJuego = document.getElementById("formJuego");
  const btnAgregarJuego = document.getElementById("btnAgregarJuego");
  const cancelarJuego = document.getElementById("cancelarJuego");

  // =============================
  //  MODAL PARA MENSAJES
  // =============================

  function mostrarModal(titulo, mensaje) {
    document.getElementById("msgModalTitle").innerText = titulo;
    document.getElementById("msgModalBody").innerHTML = mensaje;

    let modal = new bootstrap.Modal(document.getElementById("msgModal"));
    modal.show();
  }

  // =============================
  //  MODAL CONFIRMACIÓN
  // =============================

  function confirmarAccion(mensaje) {
    return new Promise((resolve) => {
      document.querySelector("#confirmModal .modal-body").innerHTML = mensaje;

      const modal = new bootstrap.Modal(document.getElementById("confirmModal"));
      modal.show();

      const btnConfirmar = document.getElementById("confirmDeleteBtn");
      const btnCancelar = document.querySelector("#confirmModal .btn-secondary");

      const confirmarHandler = () => {
        modal.hide();
        btnConfirmar.removeEventListener("click", confirmarHandler);
        resolve(true);
      };

      btnConfirmar.addEventListener("click", confirmarHandler);

      btnCancelar.onclick = () => {
        modal.hide();
        resolve(false);
      };
    });
  }

  // =============================
  //  CARGAR DATOS INICIALES
  // =============================

  cargarPlataformas();
  cargarGeneros();
  cargarEmpresas();
  cargarJuegos();

  // =============================
  //  CARGAR PLATAFORMAS
  // =============================

  async function cargarPlataformas() {
    const res = await fetch("./bd/gestion-juegos/obtener-plataformas.php");
    const data = await res.json();

    const select = $("#selectPlataformas");
    select.empty();

    data.data.forEach(p => {
      select.append(new Option(p.nombre, p.id_plataforma));
    });

    select.select2({
      placeholder: "Seleccionar plataformas",
      width: "100%",
      allowClear: true
    });
  }

  // =============================
  //  CARGAR GENEROS
  // =============================

  async function cargarGeneros() {
    const res = await fetch("./bd/gestion-juegos/obtener-genero.php");
    const data = await res.json();

    const select = $("#selectGeneros");
    select.empty();

    data.data.forEach(g => {
      select.append(new Option(g.nombre, g.id_genero));
    });

    select.select2({
      placeholder: "Seleccionar géneros",
      width: "100%",
      allowClear: true
    });
  }

  // =============================
  //  CARGAR EMPRESAS
  // =============================

  async function cargarEmpresas() {
    const res = await fetch("./bd/gestion-juegos/obtener-empresas.php");
    const data = await res.json();

    const select = $("#selectEmpresa");
    select.empty();

    data.data.forEach(e => {
      select.append(new Option(e.nombre, e.id_empresa));
    });

    select.select2({
      placeholder: "Seleccionar empresa",
      width: "100%",
      allowClear: true
    });
  }

  // =============================
  //   MOSTRAR / OCULTAR FORM
  // =============================

  btnAgregarJuego.addEventListener("click", () => {
    formJuego.classList.toggle("d-none");
    formJuego.reset();
    formJuego.dataset.mode = "create";
    delete formJuego.dataset.id;

    $("#selectPlataformas").val(null).trigger("change");
    $("#selectGeneros").val(null).trigger("change");
    $("#selectEmpresa").val(null).trigger("change");
  });

  cancelarJuego.addEventListener("click", () => {
    formJuego.classList.add("d-none");
    formJuego.reset();

    $("#selectPlataformas").val(null).trigger("change");
    $("#selectGeneros").val(null).trigger("change");
    $("#selectEmpresa").val(null).trigger("change");
  });

  // =============================
  //        GUARDAR JUEGO
  // =============================

  formJuego.addEventListener("submit", async (e) => {
    e.preventDefault();

    const fd = new FormData(formJuego);

    fd.append("action", formJuego.dataset.mode === "edit" ? "update" : "create");

    if (formJuego.dataset.id) {
      fd.append("id", formJuego.dataset.id);
    }

    let plataformas = $("#selectPlataformas").val() || [];
    let generos = $("#selectGeneros").val() || [];
    let empresaSeleccionada = $("#selectEmpresa").val();

    fd.append("plataformas", JSON.stringify(plataformas));
    fd.append("generos", JSON.stringify(generos));
    fd.append("empresa", empresaSeleccionada);

    const res = await fetch("./bd/gestion-juegos/guardar-juego.php", {
      method: "POST",
      body: fd,
    });

    const data = await res.json();

    if (data.success) {
      mostrarModal("Éxito", data.message);

      formJuego.reset();
      formJuego.classList.add("d-none");

      $("#selectPlataformas").val(null).trigger("change");
      $("#selectGeneros").val(null).trigger("change");
      $("#selectEmpresa").val(null).trigger("change");

      cargarJuegos();
    } else {
      console.error(data.errors || data.error);
      mostrarModal("Error", "Error al guardar el juego.");
    }
  });

  // =============================
  //        CARGAR JUEGOS
  // =============================

  async function cargarJuegos() {
    try {
      const res = await fetch("./bd/gestion-juegos/obtener-juegos.php");
      const data = await res.json();

      tablaJuegos.innerHTML = "";

      if (!data.success || data.data.length === 0) {
        tablaJuegos.innerHTML = "<tr><td colspan='8'>No hay juegos registrados</td></tr>";
        return;
      }

      data.data.forEach((juego) => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
        <td>
          <div class="img-container">
            <img src="${juego.imagen_portada || './img/placeholder.png'}" class="img-thumb">
          </div>
        </td>

        <td>${juego.titulo}</td>
        <td>${juego.descripcion}</td>
        <td>${juego.plataformas.map(p => p.nombre).join(", ")}</td>
        <td>${juego.generos.map(g => g.nombre).join(", ")}</td>
        <td>${juego.empresa}</td>
        <td>${juego.fecha_lanzamiento || "-"}</td>
        <td>
          <div class="acciones-buttons d-flex justify-content-center gap-1">
            <button class="btn btn-warning btn-sm btn-editar" data-id="${juego.id_juego}">
              <i class="bi bi-pencil-square"></i>
            </button>

            <button class="btn btn-danger btn-sm btn-eliminar" data-id="${juego.id_juego}">
              <i class="bi bi-trash"></i>
            </button>

            <button class="btn btn-sm btn-publicar ${juego.publicado == 1 ? "btn-success" : "btn-danger"}"
                    data-id="${juego.id_juego}" data-publicado="${juego.publicado}">
              ${juego.publicado == 1 ? "Publicado" : "Oculto"}
            </button>
          </div>
        </td>`;

        tablaJuegos.appendChild(tr);
      });

      document.querySelectorAll(".btn-eliminar").forEach((btn) => {
        btn.addEventListener("click", () => eliminarJuego(btn.dataset.id));
      });

      document.querySelectorAll(".btn-editar").forEach((btn) => {
        btn.addEventListener("click", () => editarJuego(btn.dataset.id));
      });

      document.querySelectorAll(".btn-publicar").forEach((btn) => {
        btn.addEventListener("click", () => cambiarPublicacion(btn));
      });

    } catch (err) {
      console.error("Error al cargar juegos:", err);
      tablaJuegos.innerHTML = "<tr><td colspan='8'>Error al cargar los juegos</td></tr>";
    }
  }

  // =============================
  //        EDITAR JUEGO
  // =============================

  async function editarJuego(id) {
    try {
      const res = await fetch("./bd/gestion-juegos/obtener-juegos.php");
      const data = await res.json();

      const juego = data.data.find(j => j.id_juego == id);
      if (!juego) return mostrarModal("Error", "Juego no encontrado");

      formJuego.classList.remove("d-none");
      formJuego.dataset.mode = "edit";
      formJuego.dataset.id = id;

      document.getElementById("nombreJuego").value = juego.titulo;
      document.getElementById("descripcionJuego").value = juego.descripcion;
      document.getElementById("fechaJuego").value = juego.fecha_lanzamiento;

      $("#selectPlataformas").val(juego.plataformas.map(p => p.id_plataforma)).trigger("change");
      $("#selectGeneros").val(juego.generos.map(g => g.id_genero)).trigger("change");
      $("#selectEmpresa").val(juego.id_empresa).trigger("change");

    } catch (error) {
      console.error("Error al cargar datos del juego:", error);
    }
  }

  // =============================
  //        ELIMINAR JUEGO
  // =============================

  async function eliminarJuego(id) {

    const confirmar = await confirmarAccion("¿Seguro que deseas eliminar este juego?");
    if (!confirmar) return;

    const res = await fetch("./bd/gestion-juegos/eliminar-juego.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id })
    });

    const data = await res.json();

    if (data.success) {
      mostrarModal("Eliminado", data.message);
      cargarJuegos();
    } else {
      mostrarModal("Error", "Error al eliminar el juego: " + (data.error || "desconocido"));
    }
  }

  // =============================
  //     PUBLICAR / OCULTAR
  // =============================

  async function cambiarPublicacion(btn) {
    const id = btn.dataset.id;
    const estadoActual = Number(btn.dataset.publicado);
    const nuevoEstado = estadoActual === 1 ? 0 : 1;

    const res = await fetch("./bd/gestion-juegos/toggle-publicar.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: id, estado: nuevoEstado })
    });

    const data = await res.json();

    if (data.success) {
      btn.dataset.publicado = nuevoEstado;
      btn.textContent = nuevoEstado === 1 ? "Publicado" : "Oculto";
      btn.classList.remove("btn-success", "btn-danger");
      btn.classList.add(nuevoEstado === 1 ? "btn-success" : "btn-danger");

      cargarJuegos();
    } else {
      mostrarModal("Error", data.error);
    }
  }
};
