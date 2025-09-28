document.addEventListener("DOMContentLoaded", () => {
  const formModerador = document.getElementById("formModerador");
  const nombreInput = document.getElementById("nombreModerador");
  const emailInput = document.getElementById("emailModerador");
  const tabla = document.getElementById("tablaModeradores");
  const btnAgregar = document.getElementById("btnAgregarModerador");

  let nextId = tabla.children.length + 1;

  // Mostrar/ocultar formulario
  btnAgregar.addEventListener("click", () => {
    formModerador.classList.toggle("d-none");
    formModerador.reset();
  });

  // Agregar nuevo moderador
  formModerador.addEventListener("submit", (e) => {
    e.preventDefault();
    const nombre = nombreInput.value.trim();
    const email = emailInput.value.trim();

    if (!nombre || !email) return;

    const fila = document.createElement("tr");
    fila.innerHTML = `
      <td>${nextId++}</td>
      <td>${nombre}</td>
      <td>${email}</td>
      <td>
        <button class="btn btn-sm btn-warning btn-editar">Editar</button>
        <button class="btn btn-sm btn-danger btn-eliminar">Eliminar</button>
        <button class="btn btn-sm btn-secondary btn-permiso">Permiso OFF</button>
      </td>
    `;
    tabla.appendChild(fila);

    formModerador.reset();
    formModerador.classList.add("d-none");
  });

  // Delegación de eventos para toda la tabla
  tabla.addEventListener("click", (e) => {
    const target = e.target.closest("button");
    if (!target) return;

    const fila = target.closest("tr");
    const userCell = fila.cells[1];
    const emailCell = fila.cells[2];

    // --- EDITAR / GUARDAR ---
    if (target.classList.contains("btn-editar")) {
      if (target.textContent.includes("Editar")) {
        // Cambiar a inputs
        userCell.innerHTML = `<input type="text" class="form-control form-control-sm" value="${userCell.textContent}">`;
        emailCell.innerHTML = `<input type="email" class="form-control form-control-sm" value="${emailCell.textContent}">`;
        target.textContent = "Guardar";
        target.classList.replace("btn-warning", "btn-success");
      } else {
        // Guardar cambios
        const nuevoNombre = userCell.querySelector("input").value.trim();
        const nuevoEmail = emailCell.querySelector("input").value.trim();

        userCell.textContent = nuevoNombre || "Sin nombre";
        emailCell.textContent = nuevoEmail || "sin@email.com";

        target.textContent = "Editar";
        target.classList.replace("btn-success", "btn-warning");
      }
    }

    // --- ELIMINAR ---
    if (target.classList.contains("btn-eliminar")) {
      fila.remove();
    }

    // --- PERMISO ON/OFF ---
    if (target.classList.contains("btn-permiso")) {
      if (target.textContent.includes("OFF")) {
        target.textContent = "Permiso ON";
        target.classList.replace("btn-secondary", "btn-success");
      } else {
        target.textContent = "Permiso OFF";
        target.classList.replace("btn-success", "btn-secondary");
      }
    }
  });
});
