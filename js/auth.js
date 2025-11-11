/**
 * Manejo de autenticación - Login y Registro
 */

// Manejar formulario de login
document.getElementById("formLogin")?.addEventListener("submit", async (e) => {
  e.preventDefault();

  const username = document.getElementById("loginUsername").value.trim();
  const password = document.getElementById("loginPassword").value;
  const errorDiv = document.getElementById("loginError");
  const submitBtn = e.target.querySelector('button[type="submit"]');

  // Ocultar errores previos
  errorDiv.classList.add("d-none");

  // Deshabilitar botón durante la petición
  submitBtn.disabled = true;
  submitBtn.innerHTML =
    '<span class="spinner-border spinner-border-sm me-2"></span>Ingresando...';

  try {
    const response = await fetch("./login.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ username, password }),
    });

    const data = await response.json();

    if (data.success) {
      // Login exitoso - redirigir
      window.location.href = data.redirect || "./index.php";
    } else {
      // Mostrar error
      errorDiv.textContent = data.message || "Error al iniciar sesión";
      errorDiv.classList.remove("d-none");

      // Rehabilitar botón
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Ingresar';
    }
  } catch (error) {
    console.error("Error:", error);
    errorDiv.textContent = "Error de conexión. Por favor, intenta nuevamente.";
    errorDiv.classList.remove("d-none");

    // Rehabilitar botón
    submitBtn.disabled = false;
    submitBtn.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Ingresar';
  }
});

// Manejar formulario de registro
document
  .getElementById("formRegistro")
  ?.addEventListener("submit", async (e) => {
    e.preventDefault();

    const username = document.getElementById("regUsername").value.trim();
    const email = document.getElementById("regEmail").value.trim();
    const password = document.getElementById("regPassword").value;
    const errorDiv = document.getElementById("registroError");
    const successDiv = document.getElementById("registroSuccess");
    const submitBtn = e.target.querySelector('button[type="submit"]');

    // Ocultar mensajes previos
    errorDiv.classList.add("d-none");
    successDiv.classList.add("d-none");

    // Deshabilitar botón durante la petición
    submitBtn.disabled = true;
    submitBtn.innerHTML =
      '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...';

    try {
      const response = await fetch("./registro.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ username, email, password }),
      });

      const data = await response.json();

      if (data.success) {
        // Registro exitoso
        successDiv.textContent =
          data.message || "Usuario registrado exitosamente";
        successDiv.classList.remove("d-none");

        // Limpiar formulario
        e.target.reset();

        // Esperar 2 segundos y cambiar al modal de login
        setTimeout(() => {
          const registroModal = bootstrap.Modal.getInstance(
            document.getElementById("registroModal")
          );
          registroModal.hide();

          const loginModal = new bootstrap.Modal(
            document.getElementById("loginModal")
          );
          loginModal.show();

          // Prellenar el email en el login
          document.getElementById("loginUsername").value = username;
        }, 2000);
      } else {
        // Mostrar error
        if (data.errors) {
          // Errores de validación
          const erroresTexto = Object.values(data.errors).join("<br>");
          errorDiv.innerHTML = erroresTexto;
        } else {
          errorDiv.textContent = data.message || "Error al registrar usuario";
        }
        errorDiv.classList.remove("d-none");

        // Rehabilitar botón
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-person-plus"></i> Crear Cuenta';
      }
    } catch (error) {
      console.error("Error:", error);
      errorDiv.textContent =
        "Error de conexión. Por favor, intenta nuevamente.";
      errorDiv.classList.remove("d-none");

      // Rehabilitar botón
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="bi bi-person-plus"></i> Crear Cuenta';
    }
  });

// Limpiar formularios cuando se cierran los modales
document
  .getElementById("loginModal")
  ?.addEventListener("hidden.bs.modal", function () {
    document.getElementById("formLogin")?.reset();
    document.getElementById("loginError")?.classList.add("d-none");
  });

document
  .getElementById("registroModal")
  ?.addEventListener("hidden.bs.modal", function () {
    document.getElementById("formRegistro")?.reset();
    document.getElementById("registroError")?.classList.add("d-none");
    document.getElementById("registroSuccess")?.classList.add("d-none");
  });
