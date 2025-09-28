// Botones
const botonEditarNombre = document.getElementById("btnEditarNombre");
const botonCambiarContrasena = document.getElementById("btnCambiarContrasena");

// Formularios
const formularioEditarNombre = document.getElementById("formEditarNombre");
const formularioCambiarContrasena = document.getElementById(
  "formCambiarContrasena"
);

// Campos
const campoNombre = document.getElementById("campoNombre");
const campoContrasena = document.getElementById("campoContrasena");

// Errores
const errorNombre = document.getElementById("errorNombre");
const errorContrasena = document.getElementById("errorContrasena");

// Datos mostrados
const mostrarNombre = document.getElementById("mostrarNombre");

// Mostrar/Ocultar formulario de nombre
botonEditarNombre.addEventListener("click", () => {
  formularioEditarNombre.classList.toggle("d-none");
});

// Mostrar/Ocultar formulario de contraseña
botonCambiarContrasena.addEventListener("click", () => {
  formularioCambiarContrasena.classList.toggle("d-none");
});

// Validación de nombre (solo letras y no vacío)
formularioEditarNombre.addEventListener("submit", (evento) => {
  evento.preventDefault();
  const nombre = campoNombre.value.trim();
  const regexNombre = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;

  if (nombre === "" || !regexNombre.test(nombre)) {
    errorNombre.textContent =
      "El nombre debe contener solo letras y no estar vacío.";
    errorNombre.classList.remove("d-none");
  } else {
    errorNombre.classList.add("d-none");
    mostrarNombre.textContent = nombre; // Actualiza en pantalla
    alert("Nombre actualizado correctamente ✅");
    formularioEditarNombre.classList.add("d-none");
    campoNombre.value = "";
  }
});

// Validación de contraseña (letras, números y símbolos, no vacía)
formularioCambiarContrasena.addEventListener("submit", (evento) => {
  evento.preventDefault();
  const contrasena = campoContrasena.value.trim();
  const regexContrasena = /^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+$/;

  if (contrasena === "" || !regexContrasena.test(contrasena)) {
    errorContrasena.textContent =
      "La contraseña debe contener letras, números o símbolos y no estar vacía.";
    errorContrasena.classList.remove("d-none");
  } else {
    errorContrasena.classList.add("d-none");
    alert("Contraseña actualizada correctamente ✅");
    formularioCambiarContrasena.classList.add("d-none");
    campoContrasena.value = "";
  }
});
