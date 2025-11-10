/**
 * Gestión de Moderadores - Admin
 * Permite asignar y quitar el rol de moderador a usuarios
 */

document.addEventListener("DOMContentLoaded", () => {
  const tabla = document.getElementById("tablaModeradores");

  // Referencias al modal
  const modalConfirmacion = new bootstrap.Modal(
    document.getElementById("modalConfirmacion")
  );
  const modalMensaje = document.getElementById("modalConfirmacionMensaje");
  const btnConfirmar = document.getElementById("btnConfirmarAccion");

  // Variable para almacenar la acción pendiente
  let accionPendiente = null;

  // Cargar usuarios al iniciar
  cargarUsuarios();

  /**
   * Carga todos los usuarios desde el backend
   */
  async function cargarUsuarios() {
    try {
      const response = await fetch("./bd/gestion-moderadores/getUsuarios.php");
      const data = await response.json();

      if (data.success) {
        mostrarUsuarios(data.data);
      } else {
        mostrarError("Error al cargar usuarios: " + data.message);
      }
    } catch (error) {
      mostrarError("Error de conexión: " + error.message);
    }
  }

  /**
   * Muestra los usuarios en la tabla
   */
  function mostrarUsuarios(usuarios) {
    tabla.innerHTML = "";

    if (usuarios.length === 0) {
      tabla.innerHTML =
        '<tr><td colspan="5" class="text-center">No hay usuarios registrados</td></tr>';
      return;
    }

    usuarios.forEach((usuario) => {
      const fila = document.createElement("tr");
      fila.dataset.idUsuario = usuario.id_usuario;

      // Determinar el estado del botón según si es moderador
      const btnModeradorClass = usuario.es_moderador
        ? "btn-success"
        : "btn-secondary";
      const btnModeradorText = usuario.es_moderador
        ? "Moderador ON"
        : "Moderador OFF";
      const btnModeradorIcon = usuario.es_moderador
        ? "bi-shield-check"
        : "bi-shield-x";

      // No mostrar botón si es admin (los admins no se gestionan aquí)
      const btnModerador = usuario.es_admin
        ? '<span class="badge bg-danger">Administrador</span>'
        : `<button class="btn btn-sm ${btnModeradorClass} btn-toggle-moderador" 
                   data-id="${usuario.id_usuario}" 
                   data-es-moderador="${usuario.es_moderador}">
             <i class="bi ${btnModeradorIcon}"></i> ${btnModeradorText}
           </button>`;

      fila.innerHTML = `
        <td>${usuario.id_usuario}</td>
        <td>${usuario.username}</td>
        <td>${usuario.email}</td>
        <td><span class="badge bg-info">${usuario.roles}</span></td>
        <td>${btnModerador}</td>
      `;

      tabla.appendChild(fila);
    });

    // Agregar event listeners a los botones
    document.querySelectorAll(".btn-toggle-moderador").forEach((btn) => {
      btn.addEventListener("click", toggleRolModerador);
    });
  }

  /**
   * Alterna el rol de moderador (asignar o quitar)
   */
  function toggleRolModerador(e) {
    const btn = e.currentTarget;
    const idUsuario = btn.dataset.id;
    const esModerador = btn.dataset.esModerador === "true";

    // Configurar mensaje del modal
    const accion = esModerador ? "quitar" : "asignar";
    const usuario = btn
      .closest("tr")
      .querySelector("td:nth-child(2)").textContent;
    modalMensaje.innerHTML = `
      <p>¿Estás seguro de <strong>${accion}</strong> el rol de moderador a:</p>
      <p class="text-center"><strong class="text-primary">${usuario}</strong></p>
      <p class="text-muted small">Esta acción ${
        esModerador ? "removerá" : "otorgará"
      } los permisos de moderador.</p>
    `;

    // Guardar la acción pendiente
    accionPendiente = { btn, idUsuario, esModerador };

    // Mostrar modal
    modalConfirmacion.show();
  }

  /**
   * Ejecuta la acción confirmada
   */
  async function ejecutarAccion() {
    if (!accionPendiente) return;

    const { btn, idUsuario, esModerador } = accionPendiente;

    // Cerrar modal
    modalConfirmacion.hide();

    // Deshabilitar botón mientras se procesa
    btn.disabled = true;
    const textoOriginal = btn.innerHTML;
    btn.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Procesando...';

    try {
      const endpoint = esModerador
        ? "./bd/gestion-moderadores/quitarRolModerador.php"
        : "./bd/gestion-moderadores/asignarRolModerador.php";

      const response = await fetch(endpoint, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id_usuario: idUsuario }),
      });

      const data = await response.json();

      if (data.success) {
        mostrarExito(data.message);
        // Recargar la lista de usuarios
        cargarUsuarios();
      } else {
        mostrarError(data.message);
        btn.disabled = false;
        btn.innerHTML = textoOriginal;
      }
    } catch (error) {
      mostrarError("Error de conexión: " + error.message);
      btn.disabled = false;
      btn.innerHTML = textoOriginal;
    }

    // Limpiar acción pendiente
    accionPendiente = null;
  }

  // Event listener para el botón de confirmar del modal
  btnConfirmar.addEventListener("click", ejecutarAccion);

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
    // Buscar o crear contenedor de alertas
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
