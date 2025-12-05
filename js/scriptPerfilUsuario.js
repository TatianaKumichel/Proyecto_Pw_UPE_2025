const botonEditarNombre = document.getElementById("btnEditarNombre");
const botonCambiarContrasena = document.getElementById("btnCambiarContrasena");

const formularioEditarNombre = document.getElementById("formEditarNombre");
const formularioCambiarContrasena = document.getElementById(
  "formCambiarContrasena"
);

const campoNombre = document.getElementById("campoNombre");
const campoContrasena = document.getElementById("campoContrasena");

const errorNombre = document.getElementById("errorNombre");
const errorContrasena = document.getElementById("errorContrasena");

const mostrarNombre = document.getElementById("mostrarNombre");
const usuarioDatos = document.getElementById("usuarioDatos");
const usuarioId = usuarioDatos.dataset.id;

// Mostrar/Ocultar formularios
botonEditarNombre.addEventListener("click", () => {
  formularioEditarNombre.classList.toggle("d-none");
  errorNombre.textContent = "";
});

botonCambiarContrasena.addEventListener("click", () => {
  formularioCambiarContrasena.classList.toggle("d-none");
  errorContrasena.textContent = "";
});

// Actualizar nombre con validación y array de errores
formularioEditarNombre.addEventListener("submit", async (evento) => {
  evento.preventDefault();
  const nombre = campoNombre.value.trim();
  const regexNombre =
    /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s.,;:!@#$%^&*()_\-+=¿?¡!'"\/\\]+$/;

  // Creo array de errores
  const errores = [];

  if (nombre === "") {
    errores.push("El nombre no puede estar vacío.");
  } else if (nombre.length < 3) {
    errores.push("El nombre debe tener al menos 3 caracteres.");
  } else if (!regexNombre.test(nombre)) {
    errores.push("El nombre contiene caracteres no permitidos.");
  }

  if (errores.length > 0) {
    errorNombre.textContent = errores.join(" ");
    errorNombre.classList.remove("d-none");
    return;
  }

  // Si no hay errores
  errorNombre.classList.add("d-none");

  try {
    const respuesta = await fetch("inc/actualizarUsuario.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        accion: "actualizar_nombre",
        id: usuarioId,
        nombre: nombre,
      }),
    });

    const data = await respuesta.json();
    if (data.success) {
      mostrarNombre.textContent = nombre;
      formularioEditarNombre.classList.add("d-none");
      campoNombre.value = "";

      const modal = new bootstrap.Modal(document.getElementById("modalExito"));
      modal.show();
    } else {
      // Muestro error especifico del backend
      if (data.errors && data.errors.nombre) {
        errorNombre.textContent = data.errors.nombre;
      } else if (data.mensaje) {
        errorNombre.textContent = data.mensaje;
      } else {
        errorNombre.textContent = "Ingrese un nombre válido.";
      }

      errorNombre.classList.remove("d-none");
    }
  } catch (error) {
    console.error("Error al actualizar nombre:", error);
    errorNombre.textContent = "Hubo un error de conexión con el servidor.";
    errorNombre.classList.remove("d-none");
  }
});

// Actualizar contraseña con validación y array de errores
formularioCambiarContrasena.addEventListener("submit", async (evento) => {
  evento.preventDefault();
  const contrasena = campoContrasena.value.trim();
  const regexContrasena =
    /^(?=.*[A-Za-zÁÉÍÓÚáéíóúÑñ])(?=.*\d)(?=.*[^A-Za-z0-9]).{6,}$/;

  const errores = [];

  if (contrasena === "") {
    errores.push("La contraseña no puede estar vacía.");
  } else if (contrasena.length < 6) {
    errores.push("Debe tener al menos 6 caracteres.");
  } else if (!regexContrasena.test(contrasena)) {
    errores.push("Solo se permiten letras, números y símbolos válidos.");
  }

  if (errores.length > 0) {
    errorContrasena.textContent = errores.join(" ");
    errorContrasena.classList.remove("d-none");
    return;
  }

  errorContrasena.classList.add("d-none");

  try {
    const respuesta = await fetch("inc/actualizarContraseña.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        accion: "actualizar_contrasena",
        id: usuarioId,
        contrasena: contrasena,
      }),
    });

    const data = await respuesta.json();
    if (data.success) {
      formularioCambiarContrasena.classList.add("d-none");
      campoContrasena.value = "";

      const modal = new bootstrap.Modal(document.getElementById("modalExito"));
      modal.show();
    } else {
      errorContrasena.textContent =
        data.mensaje || "Error al actualizar la contraseña.";
      errorContrasena.classList.remove("d-none");
    }
  } catch (error) {
    console.error("Error al actualizar contraseña:", error);
    errorContrasena.textContent = "Hubo un error de conexión con el servidor.";
    errorContrasena.classList.remove("d-none");
  }
});
