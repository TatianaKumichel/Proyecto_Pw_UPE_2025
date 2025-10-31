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

// 🟩 Actualizar nombre con validación y array de errores
formularioEditarNombre.addEventListener("submit", async (evento) => {
  evento.preventDefault();
  const nombre = campoNombre.value.trim();
  const regexNombre = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;

  // Creo array de errores
  const errores = [];

  if (nombre === "") {
    errores.push("El nombre no puede estar vacío.");
  } else if (!regexNombre.test(nombre)) {
    errores.push("El nombre solo puede contener letras y espacios.");
  }

  if (errores.length > 0) {
    errorNombre.textContent = errores.join(" ");
    errorNombre.classList.remove("d-none");
    return;
  }

  // Si no hay errores
  errorNombre.classList.add("d-none");

  try {
    const respuesta = await fetch("actualizarUsuario.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        accion: "actualizar_nombre",
        id: usuarioId,
        nombre: nombre,
      }),
    });

    const data = await respuesta.json();
    if (data.exito) {
      mostrarNombre.textContent = nombre;
      formularioEditarNombre.classList.add("d-none");
      campoNombre.value = "";
    } else {
      errorNombre.textContent =
        data.mensaje || "Error al actualizar el nombre.";
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
  const regexContrasena = /^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+$/;

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
    const respuesta = await fetch("actualizarContraseña.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        accion: "actualizar_contrasena",
        id: usuarioId,
        contrasena: contrasena,
      }),
    });

    const data = await respuesta.json();
    if (data.exito) {
      formularioCambiarContrasena.classList.add("d-none");
      campoContrasena.value = "";
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
