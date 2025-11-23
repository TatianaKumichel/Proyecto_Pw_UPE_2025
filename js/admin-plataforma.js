window.onload = function () {
  const tabla = document.getElementById("tablaPlataformas");
  const form = document.getElementById("formPlataforma");
  const btnAgregar = document.getElementById("btnAgregarPlataforma");
  const cancelar = document.getElementById("cancelarPlataforma");

  cargarPlataformas();

  async function cargarPlataformas() {
    const res = await fetch("./bd/gestion-juegos/obtener-plataformas.php");
    const data = await res.json();

    tabla.innerHTML = "";

    if (!data.success || data.data.length === 0) {
      tabla.innerHTML = "<tr><td colspan='3'>No hay plataformas registradas</td></tr>";
      return;
    }

    data.data.forEach(p => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${p.id_plataforma}</td>
        <td>${p.nombre}</td>
        <td>
          <button class="btn btn-warning btn-sm btn-edit"
                  data-id="${p.id_plataforma}"
                  data-nombre="${p.nombre}">
            <i class="bi bi-pencil-square"></i>
          </button>

          <button class="btn btn-danger btn-sm btn-delete"
                  data-id="${p.id_plataforma}">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      `;
      tabla.appendChild(tr);
    });

    document.querySelectorAll(".btn-edit").forEach(btn => {
      btn.addEventListener("click", () => editarPlataforma(btn));
    });

    document.querySelectorAll(".btn-delete").forEach(btn => {
      btn.addEventListener("click", () => eliminarPlataforma(btn.dataset.id));
    });
  }

  // --- BOTÓN AGREGAR ---
  btnAgregar.addEventListener("click", () => {
    form.classList.toggle("d-none");
    form.reset();
    form.dataset.mode = "create"; // modo correcto
    delete form.dataset.id;
  });

  cancelar.addEventListener("click", () => {
    form.classList.add("d-none");
    form.reset();
    delete form.dataset.id;
  });

  // --- EDITAR ---
  function editarPlataforma(btn) {
    form.classList.remove("d-none");
    form.dataset.mode = "update";  // el backend espera "update"
    form.dataset.id = btn.dataset.id;

    document.getElementById("nombrePlataforma").value = btn.dataset.nombre;
  }

  // --- GUARDAR ---
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const fd = new FormData();
    fd.append("nombre", document.getElementById("nombrePlataforma").value);
    fd.append("action", form.dataset.mode);

    if (form.dataset.mode === "update") {
      fd.append("id", form.dataset.id);
    }

    const res = await fetch("./bd/gestion-plataformas/guardar-plataforma.php", {
      method: "POST",
      body: fd,
    });

    const data = await res.json();

    if (data.success) {
      alert(data.message);
      form.reset();
      form.classList.add("d-none");
      delete form.dataset.id;
      cargarPlataformas();
    } else {
      alert("Error: " + data.error);
    }
  });

  // --- ELIMINAR ---
  async function eliminarPlataforma(id) {
    if (!confirm("¿Seguro que deseas eliminar esta plataforma?")) return;

    const res = await fetch("./bd/gestion-plataformas/eliminar-plataforma.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    });

    const data = await res.json();

    if (data.success) {
      alert(data.message);
      cargarPlataformas();
    } else {
      alert("Error: " + data.error);
    }
  }
};
