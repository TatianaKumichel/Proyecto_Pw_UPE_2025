/**
 * Login, Recuperar Contraseña y Registro
 */

// Formulario de login
document.getElementById("formLogin")?.addEventListener("submit", async (e) => {
  e.preventDefault();

  const username = document.getElementById("loginUsername").value.trim();
  const password = document.getElementById("loginPassword").value;
  const errorDiv = document.getElementById("loginError");
  const submitBtn = e.target.querySelector('button[type="submit"]');

  errorDiv.classList.add("d-none");
  submitBtn.disabled = true;
  submitBtn.innerHTML =
    '<span class="spinner-border spinner-border-sm me-2"></span>Ingresando...';

  try {
    const response = await fetch("./inc/login.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ username, password }),
    });

    const data = await response.json();

    if (data.success) {
      window.location.href = data.redirect || "./index.php";
    } else {
      errorDiv.textContent = data.message || "Error al iniciar sesión";
      errorDiv.classList.remove("d-none");
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Ingresar';
    }
  } catch (error) {
    console.error("Error:", error);
    errorDiv.textContent = "Error de conexión. Por favor, intenta nuevamente.";
    errorDiv.classList.remove("d-none");
    submitBtn.disabled = false;
    submitBtn.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Ingresar';
  }
});

// Formulario de registro
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

    // Validación de contraseña
    const regexContrasena = /^(?=.*[A-Za-zÁÉÍÓÚáéíóúÑñ])(?=.*\d)(?=.*[^A-Za-z0-9]).{6,}$/;
    const erroresClave = [];
    if (!password) {
      erroresClave.push("La contraseña es requerida.");
    } else if (password.length < 6) {
      erroresClave.push("La contraseña debe tener al menos 6 caracteres.");
    } else if (!regexContrasena.test(password)) {
      erroresClave.push("La contraseña debe contener al menos una letra, un número y un símbolo especial.");
    }
    if (erroresClave.length > 0) {
      errorDiv.innerHTML = erroresClave.join("<br>");
      errorDiv.classList.remove("d-none");
      return;
    }

    errorDiv.classList.add("d-none");
    successDiv.classList.add("d-none");
    submitBtn.disabled = true;
    submitBtn.innerHTML =
      '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...';

    try {
      const response = await fetch("./inc/registro.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ username, email, password }),
      });

      const data = await response.json();

      if (data.success) {
        successDiv.textContent =
          data.message || "Usuario registrado exitosamente";
        successDiv.classList.remove("d-none");
        e.target.reset();
        setTimeout(() => {
          const registroModal = bootstrap.Modal.getInstance(
            document.getElementById("registroModal")
          );
          registroModal.hide();

          const loginModal = new bootstrap.Modal(
            document.getElementById("loginModal")
          );
          loginModal.show();

          document.getElementById("loginUsername").value = username;
        }, 2000);
      } else {
        if (data.errors) {
          // Errores de validación
          const erroresTexto = Object.values(data.errors).join("<br>");
          errorDiv.innerHTML = erroresTexto;
        } else {
          errorDiv.textContent = data.message || "Error al registrar usuario";
        }
        errorDiv.classList.remove("d-none");
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-person-plus"></i> Crear Cuenta';
      }
    } catch (error) {
      console.error("Error:", error);
      errorDiv.textContent =
        "Error de conexión. Por favor, intenta nuevamente.";
      errorDiv.classList.remove("d-none");
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="bi bi-person-plus"></i> Crear Cuenta';
    }
  });

// Inicializar formularios
document
  .getElementById("loginModal")
  ?.addEventListener("hidden.bs.modal", function () {
    document.getElementById("formLogin")?.reset();
    document.getElementById("loginError")?.classList.add("d-none");
    document.getElementById("loginSuccess")?.classList.add("d-none");
  });

document
  .getElementById("registroModal")
  ?.addEventListener("hidden.bs.modal", function () {
    document.getElementById("formRegistro")?.reset();
    document.getElementById("registroError")?.classList.add("d-none");
    document.getElementById("registroSuccess")?.classList.add("d-none");
  });

// ----------------------------------------------------------------
// Recuperar Contraseña 
// ----------------------------------------------------------------

// Paso 1: Verificar Email
document.getElementById("formRecuperarPaso1")?.addEventListener("submit", async (e) => {
  e.preventDefault();

  const email = document.getElementById("recuperarEmail").value.trim();
  const errorDiv = document.getElementById("recuperarError1");
  const submitBtn = e.target.querySelector('button[type="submit"]');

  errorDiv.classList.add("d-none");
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verificando...';

  try {
    const response = await fetch("./inc/procesar_recuperar.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "verificar_email", email: email }),
    });

    const data = await response.json();

    if (data.success) {
      // Pedir nueva contraseña
      document.getElementById("formRecuperarPaso1").classList.add("d-none");
      document.getElementById("formRecuperarPaso2").classList.remove("d-none");
      document.getElementById("recuperarEmailConfirmado").value = email;
    } else {
      errorDiv.textContent = data.message || "Error al verificar email.";
      errorDiv.classList.remove("d-none");
    }
  } catch (error) {
    console.error("Error:", error);
    errorDiv.textContent = "Error de conexión.";
    errorDiv.classList.remove("d-none");
  } finally {
    submitBtn.disabled = false;
    submitBtn.innerHTML = "Verificar Email";
  }
});

// Paso 2: Actualizar Contraseña
document.getElementById("formRecuperarPaso2")?.addEventListener("submit", async (e) => {
  e.preventDefault();

  const email = document.getElementById("recuperarEmailConfirmado").value;
  const password = document.getElementById("recuperarPassword").value;
  const passwordConfirm = document.getElementById("recuperarPasswordConfirm").value;
  const errorDiv = document.getElementById("recuperarError2");
  const submitBtn = e.target.querySelector('button[type="submit"]');

  errorDiv.classList.add("d-none");
  // Validación de contraseña
  const regexContrasena = /^(?=.*[A-Za-zÁÉÍÓÚáéíóúÑñ])(?=.*\d)(?=.*[^A-Za-z0-9]).{6,}$/;
  const erroresClave = [];
  if (!password) {
    erroresClave.push("La contraseña es requerida.");
  } else if (password.length < 6) {
    erroresClave.push("La contraseña debe tener al menos 6 caracteres.");
  } else if (!regexContrasena.test(password)) {
    erroresClave.push("La contraseña debe contener al menos una letra, un número y un símbolo especial.");
  }
  if (erroresClave.length > 0) {
    errorDiv.innerHTML = erroresClave.join("<br>");
    errorDiv.classList.remove("d-none");
    return;
  }
  if (password !== passwordConfirm) {
    errorDiv.textContent = "Las contraseñas no coinciden.";
    errorDiv.classList.remove("d-none");
    return;
  }

  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...';

  try {
    const response = await fetch("./inc/procesar_recuperar.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        action: "actualizar_pass",
        email: email,
        password: password,
        passwordConfirm: passwordConfirm,
      }),
    });

    const data = await response.json();

    if (data.success) {
      const recuperarModal = bootstrap.Modal.getInstance(document.getElementById("recuperarModal"));
      recuperarModal.hide();
      
      const loginModal = new bootstrap.Modal(document.getElementById("loginModal"));
      loginModal.show();

      const loginSuccessDiv = document.getElementById("loginSuccess");
      if (loginSuccessDiv) {
        loginSuccessDiv.textContent = "Contraseña actualizada correctamente.";
        loginSuccessDiv.classList.remove("d-none");
      }
      
      // Resetear formularios
      document.getElementById("formRecuperarPaso1").reset();
      document.getElementById("formRecuperarPaso2").reset();
      document.getElementById("formRecuperarPaso1").classList.remove("d-none");
      document.getElementById("formRecuperarPaso2").classList.add("d-none");

    } else {
      errorDiv.textContent = data.message || "Error al actualizar contraseña.";
      errorDiv.classList.remove("d-none");
    }
  } catch (error) {
    console.error("Error:", error);
    errorDiv.textContent = "Error de conexión.";
    errorDiv.classList.remove("d-none");
  } finally {
    submitBtn.disabled = false;
    submitBtn.innerHTML = "Actualizar Contraseña";
  }
});

// Resetear estado del modal al cerrarlo
document.getElementById("recuperarModal")?.addEventListener("hidden.bs.modal", function () {
    document.getElementById("formRecuperarPaso1").reset();
    document.getElementById("formRecuperarPaso2").reset();
    document.getElementById("formRecuperarPaso1").classList.remove("d-none");
    document.getElementById("formRecuperarPaso2").classList.add("d-none");
    document.getElementById("recuperarError1").classList.add("d-none");
    document.getElementById("recuperarError2").classList.add("d-none");
});
