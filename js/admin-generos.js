/**
 * Gestión de Géneros - Admin
 * Permite crear, editar y eliminar géneros de videojuegos
 */

document.addEventListener("DOMContentLoaded", () => {
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
  const modalEliminarNombre = document.getElementById("modalEliminarNombre");

  let idAEliminar = null;
  let nombreAEliminar = "";

  // Cargar géneros al iniciar
  cargarGeneros();

  /**
   * Preparar modal para nuevo género
   */
  btnNuevo.addEventListener("click", () => {
    form.reset();
    form.classList.remove("was-validated");
    inputId.value = "";
    document.getElementById("modalGeneroLabel").innerHTML =
      '<i class="bi bi-plus-circle"></i> Nuevo Género';
  });

  /**
   * Manejar envío del formulario
   */
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    if (!form.checkValidity()) {
      form.classList.add("was-validated");
      return;
    }

    const btnGuardar = document.getElementById("btnGuardarGenero");
    btnGuardar.disabled = true;
    const textoOriginal = btnGuardar.innerHTML;
    btnGuardar.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    try {
      const datos = { nombre: inputNombre.value.trim() };
      const url = inputId.value
        ? "./bd/gestion-generos/updateGenero.php"
        : "./bd/gestion-generos/insertGenero.php";
      if (inputId.value) datos.id_genero = inputId.value;

      const response = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos),
      });

      const res = await response.json();

      if (res.ok) {
        modalGenero.hide();
        mostrarExito(
          res.message ||
            (inputId.value
              ? "Género actualizado correctamente"
              : "Género creado correctamente")
        );
        cargarGeneros();
      } else {
        mostrarError(res.error || "Error al guardar género.");
      }
    } catch (error) {
      mostrarError("Error de conexión al guardar género.");
    } finally {
      btnGuardar.disabled = false;
      btnGuardar.innerHTML = textoOriginal;
    }
  });

  /**
   * Confirmar eliminación
   */
  btnConfirmarEliminar.addEventListener("click", async () => {
    if (!idAEliminar) return;

    btnConfirmarEliminar.disabled = true;
    const textoOriginal = btnConfirmarEliminar.innerHTML;
    btnConfirmarEliminar.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Eliminando...';

    try {
      const response = await fetch("./bd/gestion-generos/delGenero.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_genero: idAEliminar }),
      });

      const res = await response.json();

      if (res.ok) {
        modalEliminar.hide();
        mostrarExito(res.message || "Género eliminado correctamente");
        cargarGeneros();
      } else {
        mostrarError(res.error || "Error al eliminar género.");
      }
    } catch (error) {
      mostrarError("Error de conexión al eliminar género.");
    } finally {
      btnConfirmarEliminar.disabled = false;
      btnConfirmarEliminar.innerHTML = textoOriginal;
      idAEliminar = null;
      nombreAEliminar = "";
    }
  });

  /**
   * Carga todos los géneros desde el backend
   */
  async function cargarGeneros() {
    try {
      const response = await fetch("./bd/gestion-generos/getGenero.php");
      const data = await response.json();

      tabla.innerHTML = "";

      if (data.length === 0) {
        tabla.innerHTML =
          '<tr><td colspan="3" class="text-center py-4 text-muted">No hay géneros registrados</td></tr>';
        return;
      }

      data.forEach((genero) => agregarFila(genero));
    } catch (error) {
      mostrarError("Error al cargar géneros.");
      tabla.innerHTML =
        '<tr><td colspan="3" class="text-center py-4 text-danger">Error al cargar datos</td></tr>';
    }
  }

  /**
   * Agrega una fila a la tabla
   */
  function agregarFila(genero) {
    const fila = document.createElement("tr");
    fila.innerHTML = `
      <td>${genero.id_genero}</td>
      <td><strong>${genero.nombre}</strong></td>
      <td>
        <button class="btn btn-sm btn-warning me-2" data-action="editar" data-id="${genero.id_genero}" data-nombre="${genero.nombre}">
          <i class="bi bi-pencil-fill"></i> Editar
        </button>
        <button class="btn btn-sm btn-danger" data-action="eliminar" data-id="${genero.id_genero}" data-nombre="${genero.nombre}">
          <i class="bi bi-trash-fill"></i> Eliminar
        </button>
      </td>
    `;

    // Event listeners para los botones
    fila
      .querySelector('[data-action="editar"]')
      .addEventListener("click", (e) => {
        const btn = e.currentTarget;
        inputId.value = btn.dataset.id;
        inputNombre.value = btn.dataset.nombre;
        document.getElementById("modalGeneroLabel").innerHTML =
          '<i class="bi bi-pencil-fill"></i> Editar Género';
        modalGenero.show();
      });

    fila
      .querySelector('[data-action="eliminar"]')
      .addEventListener("click", (e) => {
        const btn = e.currentTarget;
        idAEliminar = btn.dataset.id;
        nombreAEliminar = btn.dataset.nombre;
        modalEliminarNombre.innerHTML = `<strong class="text-primary">${nombreAEliminar}</strong>`;
        modalEliminar.show();
      });

    tabla.appendChild(fila);
  }

  /**
   * Muestra mensaje de éxito
   */
  function mostrarExito(mensaje) {
    mostrarAlerta(mensaje, "success");
  }

  /**
   * Muestra mensaje de error
   */
  function mostrarError(mensaje) {
    mostrarAlerta(mensaje, "danger");
  }

  /**
   * Muestra una alerta temporal
   */
  function mostrarAlerta(mensaje, tipo) {
    let contenedor = document.getElementById("alertas-container");
    if (!contenedor) {
      contenedor = document.createElement("div");
      contenedor.id = "alertas-container";
      contenedor.style.position = "fixed";
      contenedor.style.top = "80px";
      contenedor.style.right = "20px";
      contenedor.style.zIndex = "9999";
      contenedor.style.maxWidth = "400px";
      document.body.appendChild(contenedor);
    }

    const alerta = document.createElement("div");
    alerta.className = `alert alert-${tipo} alert-dismissible fade show`;
    alerta.role = "alert";
    alerta.innerHTML = `
      ${mensaje}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    contenedor.appendChild(alerta);

    // Auto-cerrar después de 5 segundos
    setTimeout(() => {
      alerta.classList.remove("show");
      setTimeout(() => alerta.remove(), 150);
    }, 5000);
  }
});
