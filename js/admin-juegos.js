window.onload = function () {

  // =============================
  // ELEMENTOS GLOBALES
  // =============================

  const tablaJuegos = document.getElementById("tabla-juegos");
  const formJuego = document.getElementById("formJuego");
  const btnAgregarJuego = document.getElementById("btnAgregarJuego");
  const cancelarJuego = document.getElementById("cancelarJuego");

  const selectPlataformas = $("#selectPlataformas");
  const selectGeneros = $("#selectGeneros");
  const selectEmpresa = $("#selectEmpresa");

  const contenedorPortada = document.getElementById("previewPortada");
  const contenedorImagenesExtra = document.getElementById("imagenesExistentes");


  // =============================
  // LIMPIAR FORMULARIO COMPLETO
  // =============================

  function resetForm() {
    formJuego.reset();
    delete formJuego.dataset.mode;
    delete formJuego.dataset.id;

    selectPlataformas.val(null).trigger("change");
    selectGeneros.val(null).trigger("change");
    selectEmpresa.val(null).trigger("change");

    contenedorPortada.innerHTML = "";
    contenedorImagenesExtra.innerHTML = "";

    document.getElementById("imagenJuego").value = "";
    document.getElementById("imagenesExtra").value = "";
  }


  // =============================
  // MODAL DE MENSAJES
  // =============================

  function mostrarModal(titulo, mensaje) {
    document.getElementById("msgModalTitle").innerText = titulo;
    document.getElementById("msgModalBody").innerHTML = mensaje;
    new bootstrap.Modal(document.getElementById("msgModal")).show();
  }


  // =============================
  // MODAL DE CONFIRMACIÓN
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
  // CARGA INICIAL
  // =============================

  cargarPlataformas();
  cargarGeneros();
  cargarEmpresas();
  cargarJuegos();


  // =============================
  // CARGAR SELECT DE PLATAFORMAS
  // =============================

  async function cargarPlataformas() {
    const res = await fetch("./bd/gestion-juegos/obtener-plataformas.php");
    const data = await res.json();

    selectPlataformas.empty();
    data.data.forEach(p => {
      selectPlataformas.append(new Option(p.nombre, p.id_plataforma));
    });

    selectPlataformas.select2({ placeholder: "Seleccionar plataformas", width: "100%", allowClear: true });
  }


  // =============================
  // CARGAR SELECT DE GÉNEROS
  // =============================

  async function cargarGeneros() {
    const res = await fetch("./bd/gestion-juegos/obtener-genero.php");
    const data = await res.json();

    selectGeneros.empty();
    data.data.forEach(g => {
      selectGeneros.append(new Option(g.nombre, g.id_genero));
    });

    selectGeneros.select2({ placeholder: "Seleccionar géneros", width: "100%", allowClear: true });
  }


  // =============================
  // CARGAR SELECT DE EMPRESAS
  // =============================

  async function cargarEmpresas() {
    const res = await fetch("./bd/gestion-juegos/obtener-empresas.php");
    const data = await res.json();

    selectEmpresa.empty();
    data.data.forEach(e => {
      selectEmpresa.append(new Option(e.nombre, e.id_empresa));
    });

    selectEmpresa.select2({ placeholder: "Seleccionar empresa", width: "100%", allowClear: true });
  }


  // =============================
  // EVENTOS DEL FORMULARIO
  // =============================

  btnAgregarJuego.addEventListener("click", () => {
    const oculto = formJuego.classList.contains("d-none");

    resetForm();
    formJuego.dataset.mode = "create";

    formJuego.classList.toggle("d-none", !oculto);
  });

  cancelarJuego.addEventListener("click", () => {
    formJuego.classList.add("d-none");
    resetForm();
  });


  // =============================
  // GUARDAR (CREATE / UPDATE)
  // =============================

  formJuego.addEventListener("submit", async (e) => {
    e.preventDefault();

    const fd = new FormData(formJuego);

    const isEdit = formJuego.dataset.mode === "edit";
    fd.append("action", isEdit ? "update" : "create");

    if (isEdit) fd.append("id", formJuego.dataset.id);

    const plataformas = selectPlataformas.val() || [];
    const generos = selectGeneros.val() || [];
    const empresaSel = selectEmpresa.val();

    fd.append("plataformas", JSON.stringify(plataformas));
    fd.append("generos", JSON.stringify(generos));
    fd.append("empresa", empresaSel);

    const res = await fetch("./bd/gestion-juegos/guardar-juego.php", { method: "POST", body: fd });
    const data = await res.json();

    if (!data.success) {
      mostrarModal("Error", "Error al guardar el juego.");
      return;
    }

    mostrarModal("Éxito", data.message);

    formJuego.classList.add("d-none");
    resetForm();
    cargarJuegos();
  });


  // =============================
  // CARGAR TABLA DE JUEGOS
  // =============================

  async function cargarJuegos() {
    const res = await fetch("./bd/gestion-juegos/obtener-juegos.php");
    const data = await res.json();

    tablaJuegos.innerHTML = "";

    if (!data.success || data.data.length === 0) {
      tablaJuegos.innerHTML = "<tr><td colspan='8'>No hay juegos registrados</td></tr>";
      return;
    }

    data.data.forEach(juego => {
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
          <div class="d-flex justify-content-center gap-1">
            <button class="btn btn-warning btn-sm btn-editar" data-id="${juego.id_juego}">
              <i class="bi bi-pencil-square"></i>
            </button>

            <button class="btn btn-danger btn-sm btn-eliminar" data-id="${juego.id_juego}">
              <i class="bi bi-trash"></i>
            </button>

            <button class="btn btn-sm btn-publicar ${
              juego.publicado == 1 ? "btn-success" : "btn-danger"
            }" data-id="${juego.id_juego}" data-publicado="${juego.publicado}">
              ${juego.publicado == 1 ? "Publicado" : "Oculto"}
            </button>
          </div>
        </td>
      `;

      tablaJuegos.appendChild(tr);
    });

    document.querySelectorAll(".btn-editar").forEach(btn =>
      btn.addEventListener("click", () => editarJuego(btn.dataset.id))
    );

    document.querySelectorAll(".btn-eliminar").forEach(btn =>
      btn.addEventListener("click", () => eliminarJuego(btn.dataset.id))
    );

    document.querySelectorAll(".btn-publicar").forEach(btn =>
      btn.addEventListener("click", () => cambiarPublicacion(btn))
    );
  }


  // =============================
  // EDITAR JUEGO (CARGAR DATOS)
  // =============================

  async function editarJuego(id) {
    resetForm();

    const res = await fetch("./bd/gestion-juegos/obtener-juegos.php");
    const data = await res.json();
    const juego = data.data.find(j => j.id_juego == id);

    if (!juego) {
      mostrarModal("Error", "Juego no encontrado");
      return;
    }

    formJuego.classList.remove("d-none");
    formJuego.dataset.mode = "edit";
    formJuego.dataset.id = id;

    document.getElementById("nombreJuego").value = juego.titulo;
    document.getElementById("descripcionJuego").value = juego.descripcion;
    document.getElementById("fechaJuego").value = juego.fecha_lanzamiento;

    selectPlataformas.val(juego.plataformas.map(p => p.id_plataforma)).trigger("change");
    selectGeneros.val(juego.generos.map(g => g.id_genero)).trigger("change");
    selectEmpresa.val(juego.id_empresa).trigger("change");

    // Cargar portada
if (juego.imagen_portada) {
  const div = document.createElement("div");
  div.className = "img-extra-box";

  div.innerHTML = `
    <img src="${juego.imagen_portada}">
    <button type="button" class="btn-delete-img" data-portada="1">&times;</button>
  `;

  contenedorPortada.appendChild(div);

  div.querySelector(".btn-delete-img").onclick = async (e) => {
    e.preventDefault();
    e.stopPropagation();

    const ok = await confirmarAccion("¿Eliminar la imagen de portada?");
    if (!ok) return;

    const res = await fetch("./bd/gestion-juegos/eliminar-imagen-portada.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id })
    });

    const data = await res.json();
    if (data.success) div.remove();
    else mostrarModal("Error", data.error);
  };
}


    // Cargar imágenes extra
if (juego.imagenes_extra) {
  juego.imagenes_extra.forEach(img => {
    const div = document.createElement("div");
    div.className = "img-extra-box";

    div.innerHTML = `
      <img src="${img.url_imagen}">
      <button type="button" class="btn-delete-img" data-id="${img.id_imagen}">&times;</button>
    `;

    contenedorImagenesExtra.appendChild(div);

    div.querySelector(".btn-delete-img").onclick = async (e) => {
      e.preventDefault();
      e.stopPropagation();

      const ok = await confirmarAccion("¿Eliminar esta imagen?");
      if (!ok) return;

      const res = await fetch("./bd/gestion-juegos/eliminar-imagen.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: img.id_imagen })
      });

      const data = await res.json();
      if (data.success) div.remove();
      else mostrarModal("Error", data.error);
    };
  });
}




  }


  // =============================
  // ELIMINAR JUEGO
  // =============================

  async function eliminarJuego(id) {
    const ok = await confirmarAccion("¿Seguro que deseas eliminar este juego?");
    if (!ok) return;

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
      mostrarModal("Error", "Error al eliminar el juego.");
    }
  }


  // =============================
  // CAMBIAR ESTADO PUBLICADO
  // =============================

  async function cambiarPublicacion(btn) {
    const id = btn.dataset.id;
    const estadoActual = Number(btn.dataset.publicado);
    const nuevoEstado = estadoActual === 1 ? 0 : 1;

    const res = await fetch("./bd/gestion-juegos/toggle-publicar.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, estado: nuevoEstado })
    });

    const data = await res.json();

    if (!data.success) {
      mostrarModal("Error", data.error);
      return;
    }

    btn.dataset.publicado = nuevoEstado;
    btn.textContent = nuevoEstado === 1 ? "Publicado" : "Oculto";
    btn.classList.toggle("btn-success", nuevoEstado === 1);
    btn.classList.toggle("btn-danger", nuevoEstado === 0);

    cargarJuegos();
  }

};
