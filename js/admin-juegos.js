window.onload = function () {
  const tablaJuegos = document.getElementById("tabla-juegos");
  const formJuego = document.getElementById("formJuego");
  const btnAgregarJuego = document.getElementById("btnAgregarJuego");
  const cancelarJuego = document.getElementById("cancelarJuego");

  // Cargar los juegos al inicio
  cargarPlataformas();
  cargarGeneros();
  cargarJuegos();


  async function cargarPlataformas() {
    const res = await fetch("./bd/gestion-juegos/obtener-plataformas.php");
    const data = await res.json();

    const cont = document.getElementById("checkboxPlataformas");
    cont.innerHTML = "";

    data.data.forEach(p => {
      cont.innerHTML += `
      <label class="form-check-label">
        <input type="checkbox" class="form-check-input plataformaCheck" value="${p.id_plataforma}">
        ${p.nombre}
      </label>
    `;
    });
  }

  async function cargarGeneros() {
    const res = await fetch("./bd/gestion-juegos/obtener-genero.php");
    const data = await res.json();

    const cont = document.getElementById("checkboxGeneros");
    cont.innerHTML = "";

    data.data.forEach(g => {
      cont.innerHTML += `
      <label class="form-check-label">
        <input type="checkbox" class="form-check-input generoCheck" value="${g.id_genero}">
        ${g.nombre}
      </label>
    `;
    });
  }




  // Mostrar / ocultar formulario
  btnAgregarJuego.addEventListener("click", () => {
    formJuego.classList.toggle("d-none");
    formJuego.reset();
    formJuego.dataset.mode = "create";
    delete formJuego.dataset.id;
  });

  cancelarJuego.addEventListener("click", () => {
    formJuego.classList.add("d-none");
    formJuego.reset();
  });

  // Enviar formulario
  formJuego.addEventListener("submit", async (e) => {
    e.preventDefault();

    const fd = new FormData(formJuego);

    // Indicar si es creación o edición
    fd.append("action", formJuego.dataset.mode === "edit" ? "update" : "create");

    if (formJuego.dataset.id) {
      fd.append("id", formJuego.dataset.id);
    }

    let plataformas = [];
    document.querySelectorAll(".plataformaCheck:checked").forEach(c => plataformas.push(c.value));

    let generos = [];
    document.querySelectorAll(".generoCheck:checked").forEach(c => generos.push(c.value));

    fd.append("plataformas", JSON.stringify(plataformas));
    fd.append("generos", JSON.stringify(generos));




    const res = await fetch("./bd/gestion-juegos/guardar-juego.php", {
      method: "POST",
      body: fd,
    });

    const data = await res.json();
    if (data.success) {
      alert(data.message);
      formJuego.reset();
      formJuego.classList.add("d-none");
      cargarJuegos();
    } else {
      console.error(data.errors || data.error);
      alert("Error al guardar el juego.");
    }
  });

  // Función para cargar y mostrar los juegos
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
        <td><img src="${juego.imagen_portada || './img/placeholder.png'}" style="width:70px;"></td>
  <td>${juego.titulo}</td>
  <td>${juego.descripcion}</td>
  <td>${juego.plataformas.map(p => p.nombre).join(", ")}</td>
  <td>${juego.generos.map(g => g.nombre).join(", ")}</td>
  <td>${juego.empresa}</td>
  <td>${juego.fecha_lanzamiento || "-"}</td>
        <div class="acciones-buttons">
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
      </td>

      `;
        tablaJuegos.appendChild(tr);
      });

      document.querySelectorAll(".btn-eliminar").forEach((btn) => {
        btn.addEventListener("click", () => eliminarJuego(btn.dataset.id));
      });

      document.querySelectorAll(".btn-editar").forEach((btn) => {
        btn.addEventListener("click", () => editarJuego(btn.dataset.id));
      });

      //Publicar Juego
      document.querySelectorAll(".btn-publicar").forEach((btn) => {
        btn.addEventListener("click", () => cambiarPublicacion(btn));
      });

    } catch (err) {
      console.error("Error al cargar juegos:", err);
      tablaJuegos.innerHTML = "<tr><td colspan='8'>Error al cargar los juegos</td></tr>";
    }

  }



  // Editar juego
  async function editarJuego(id) {
    try {
      const res = await fetch("./bd/gestion-juegos/obtener-juegos.php");
      const data = await res.json();

      const juego = data.data.find(j => j.id_juego == id);
      if (!juego) return alert("Juego no encontrado");

      formJuego.classList.remove("d-none");
      formJuego.dataset.mode = "edit";
      formJuego.dataset.id = id;

      document.getElementById("nombreJuego").value = juego.titulo;
      document.getElementById("descripcionJuego").value = juego.descripcion;
      document.getElementById("empresaJuego").value = juego.empresa;
      document.getElementById("fechaJuego").value = juego.fecha_lanzamiento;

      // === Marcar Plataformas ===
      const plataformasSeleccionadas = juego.plataformas.map(p => p.id_plataforma);
      document.querySelectorAll(".plataformaCheck").forEach(chk => {
        chk.checked = plataformasSeleccionadas.includes(parseInt(chk.value));
      });

      // === Marcar Géneros ===
      const generosSeleccionados = juego.generos.map(g => g.id_genero);
      document.querySelectorAll(".generoCheck").forEach(chk => {
        chk.checked = generosSeleccionados.includes(parseInt(chk.value));
      });

    } catch (error) {
      console.error("Error al cargar datos del juego:", error);
    }
  }


  // Eliminar juego
  async function eliminarJuego(id) {
    if (!confirm("¿Seguro que deseas eliminar este juego?")) return;

    const res = await fetch("./bd/gestion-juegos/eliminar-juego.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    });

    const data = await res.json();

    if (data.success) {
      alert(data.message);
      cargarJuegos();
    } else {
      alert("Error al eliminar el juego: " + (data.error || "desconocido"));
    }
  }
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
      // actualizar boton inmediatamente
      btn.dataset.publicado = nuevoEstado;
      btn.textContent = nuevoEstado === 1 ? "Publicado" : "Oculto";
      btn.classList.remove("btn-success", "btn-danger");
      btn.classList.add(nuevoEstado === 1 ? "btn-success" : "btn-danger");

      // refrescar tabla para mantener consistencia
      cargarJuegos();
    } else {
      alert("Error: " + data.error);
    }
  }




};
