window.onload = function () {

  const tablaJuegos = document.getElementById("tabla-juegos");
  const formJuegoModal = document.getElementById("formJuegoModal");
  const btnAgregarJuego = document.getElementById("btnAgregarJuego");
  
  // Modal Instance
  const juegoModalEl = document.getElementById("juegoModal");
  const juegoModal = new bootstrap.Modal(juegoModalEl);

  // Selects
  const selectPlataformas = $("#selectPlataformas");
  const selectGeneros = $("#selectGeneros");
  const selectEmpresa = $("#selectEmpresa");

  // Contenedores de Imágenes
  const previewPortada = document.getElementById("previewPortada");
  const containerImagenesExistentes = document.getElementById("containerImagenesExistentes");
  const previewImagenesExtra = document.getElementById("previewImagenesExtra");

  // Inputs File
  const inputImagenJuego = document.getElementById("imagenJuego");
  const inputImagenesExtra = document.getElementById("imagenesExtra");


  // =============================
  // CONFIGURACIÓN INICIAL
  // =============================

  // Inicializar Select2 con dropdownParent para que funcione en Modal
  function initSelect2() {
    const options = { 
      width: "100%", 
      dropdownParent: $("#juegoModal") // CRUCIAL para Modals
    };

    selectPlataformas.select2({ ...options, placeholder: "Seleccionar plataformas", allowClear: true });
    selectGeneros.select2({ ...options, placeholder: "Seleccionar géneros", allowClear: true });
    selectEmpresa.select2({ ...options, placeholder: "Seleccionar empresa", allowClear: true });
  }

  initSelect2();
  cargarPlataformas();
  cargarGeneros();
  cargarEmpresas();
  cargarJuegos();


  // =============================
  // LIMPIAR FORMULARIO
  // =============================

  function resetForm() {
    formJuegoModal.reset();
    delete formJuegoModal.dataset.mode;
    delete formJuegoModal.dataset.id;

    // Limpieza de selects
    selectPlataformas.val(null).trigger("change");
    selectGeneros.val(null).trigger("change");
    selectEmpresa.val(null).trigger("change");

    // Limpieza imágenes
    previewPortada.innerHTML = '<span class="text-muted align-self-center small">Vista previa</span>';
    containerImagenesExistentes.innerHTML = "";
    previewImagenesExtra.innerHTML = "";

    // Reset de control transaccional
    formJuegoModal._imagenesAEliminar = [];
    formJuegoModal._portadaEliminada = false;

    // Limpiar errores visuales
    document.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
    document.querySelectorAll(".invalid-feedback").forEach(el => el.innerText = "");
  }


  // =============================
  // EVENTOS
  // =============================

  btnAgregarJuego.addEventListener("click", () => {
    resetForm();
    formJuegoModal.dataset.mode = "create";
    document.getElementById("juegoModalLabel").innerText = "Nuevo Juego";
    juegoModal.show();
  });

  // Preview Portada al seleccionar archivo
  inputImagenJuego.addEventListener("change", function() {
    previewImage(this, previewPortada);
  });

  // Preview Imágenes Extra al seleccionar archivos
  inputImagenesExtra.addEventListener("change", function() {
    previewImagesMultiple(this, previewImagenesExtra);
  });


  // =============================
  // GUARDAR JUEGO
  // =============================

  formJuegoModal.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Validar portada obligatoria en CREATE
    if (formJuegoModal.dataset.mode === "create") {
      if (!inputImagenJuego.files || inputImagenJuego.files.length === 0) {
        mostrarErrorCampo(inputImagenJuego, "Debes seleccionar una imagen de portada.");
        return;
      }
    }

    const fd = new FormData(formJuegoModal);
    const isEdit = formJuegoModal.dataset.mode === "edit";
    
    fd.append("action", isEdit ? "update" : "create");
    if (isEdit) fd.append("id", formJuegoModal.dataset.id);

    // Arrays del formulario (Select2)
    fd.append("plataformas", JSON.stringify(selectPlataformas.val() || []));
    fd.append("generos", JSON.stringify(selectGeneros.val() || []));
    fd.append("empresa", selectEmpresa.val());

    // Imágenes a eliminar
    if (formJuegoModal._portadaEliminada) {
      fd.append("eliminarPortada", "1");
    }
    if (formJuegoModal._imagenesAEliminar && formJuegoModal._imagenesAEliminar.length > 0) {
      fd.append("imagenesAEliminar", JSON.stringify(formJuegoModal._imagenesAEliminar));
    }

    try {
      const res = await fetch("./bd/gestion-juegos/guardar-juego.php", { method: "POST", body: fd });
      const data = await res.json();

      if (!data.success) {
        if (data.errors) {
          mostrarErrores(data.errors);
        } else {
          mostrarModal("Error", data.error || "Error al guardar el juego.");
        }
        return;
      }

      // ÉXITO
      juegoModal.hide();
      mostrarModal("Éxito", data.message);
      cargarJuegos();

    } catch (err) {
      console.error(err);
      mostrarModal("Error", "Ocurrió un error inesperado.");
    }
  });


  // =============================
  // FUNCIONES DE CARGA DE DATOS
  // =============================

  async function cargarPlataformas() {
    const res = await fetch("./bd/gestion-juegos/obtener-plataformas.php");
    const data = await res.json();
    selectPlataformas.empty();
    data.data.forEach(p => selectPlataformas.append(new Option(p.nombre, p.id_plataforma)));
  }

  async function cargarGeneros() {
    const res = await fetch("./bd/gestion-juegos/obtener-genero.php");
    const data = await res.json();
    selectGeneros.empty();
    data.data.forEach(g => selectGeneros.append(new Option(g.nombre, g.id_genero)));
  }

  async function cargarEmpresas() {
    const res = await fetch("./bd/gestion-juegos/obtener-empresas.php");
    const data = await res.json();
    selectEmpresa.empty();
    data.data.forEach(e => selectEmpresa.append(new Option(e.nombre, e.id_empresa)));
  }


  // =============================
  // CARGAR TABLA JUEGOS
  // =============================

  async function cargarJuegos() {
    const res = await fetch("./bd/gestion-juegos/obtener-juegos.php");
    const data = await res.json();

    tablaJuegos.innerHTML = "";

    if (!data.success || data.data.length === 0) {
      tablaJuegos.innerHTML = "<tr><td colspan='8'>No hay juegos registrados</td></tr>";
      return;
    }

    data.data.forEach(juego => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td><div class="img-container"><img src="${juego.imagen_portada || './img/placeholder.png'}" class="img-thumb"></div></td>
        <td>${juego.titulo}</td>
        <td>${juego.descripcion}</td>
        <td>${juego.plataformas.map(p => p.nombre).join(", ")}</td>
        <td>${juego.generos.map(g => g.nombre).join(", ")}</td>
        <td>${juego.empresa}</td>
        <td>${juego.fecha_lanzamiento || "-"}</td>
        <td>
          <div class="d-flex justify-content-center gap-1">
            <button class="btn btn-warning btn-sm btn-editar" data-id="${juego.id_juego}">
              <i class="bi bi-pencil-square"></i>
            </button>
            <button class="btn btn-danger btn-sm btn-eliminar" data-id="${juego.id_juego}">
              <i class="bi bi-trash"></i>
            </button>
            <button class="btn btn-sm btn-publicar ${juego.publicado == 1 ? "btn-success" : "btn-danger"}"
                    data-id="${juego.id_juego}" data-publicado="${juego.publicado}">
               ${juego.publicado == 1 ? "Publicado" : "Publicar"}
            </button>
          </div>
        </td>
      `;
      tablaJuegos.appendChild(tr);
    });

    // Listeners dinámicos
    document.querySelectorAll(".btn-editar").forEach(btn =>
      btn.addEventListener("click", () => editarJuego(btn.dataset.id))
    );
    document.querySelectorAll(".btn-eliminar").forEach(btn =>
      btn.addEventListener("click", () => eliminarJuego(btn.dataset.id))
    );
    document.querySelectorAll(".btn-publicar").forEach(btn =>
      btn.addEventListener("click", () => cambiarPublicacion(btn))
    );
  }


  // =============================
  // EDITAR JUEGO
  // =============================

  async function editarJuego(id) {
    resetForm();

    // Obtener datos del juego (reutilizamos obtener-juegos.php, idealmente sería obtener-juego-id.php)
    const res = await fetch("./bd/gestion-juegos/obtener-juegos.php");
    const data = await res.json();
    const juego = data.data.find(j => j.id_juego == id);

    if (!juego) {
      mostrarModal("Error", "Juego no encontrado");
      return;
    }

    // Configurar Modal
    formJuegoModal.dataset.mode = "edit";
    formJuegoModal.dataset.id = id;
    document.getElementById("juegoModalLabel").innerText = "Editar Juego: " + juego.titulo;

    // Llenar campos
    document.getElementById("nombreJuego").value = juego.titulo;
    document.getElementById("descripcionJuego").value = juego.descripcion;
    document.getElementById("fechaJuego").value = juego.fecha_lanzamiento;

    // Llenar Selects
    selectPlataformas.val(juego.plataformas.map(p => p.id_plataforma)).trigger("change");
    selectGeneros.val(juego.generos.map(g => g.id_genero)).trigger("change");
    selectEmpresa.val(juego.id_empresa).trigger("change");

    // Mostrar Portada Actual
    if (juego.imagen_portada) {
      previewPortada.innerHTML = `
        <div class="position-relative d-inline-block">
          <img src="${juego.imagen_portada}" class="img-thumbnail" style="max-height: 100px;">
          <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 px-1" 
                  onclick="eliminarPortadaActual(this)">&times;</button>
        </div>
      `;
    }

    // Mostrar Imágenes Extra Actuales
    if (juego.imagenes_extra) {
      juego.imagenes_extra.forEach(img => {
        const div = document.createElement("div");
        div.className = "position-relative d-inline-block";
        div.innerHTML = `
          <img src="${img.url_imagen}" class="img-thumbnail" style="max-height: 80px;">
          <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 px-1" 
                  data-id="${img.id_imagen}">&times;</button>
        `;
        
        // Listener para eliminar
        div.querySelector("button").onclick = async (e) => {
          e.preventDefault();
          const ok = await confirmarAccion("¿Eliminar esta imagen?");
          if (ok) {
            div.remove();
            formJuegoModal._imagenesAEliminar.push(img.id_imagen);
          }
        };

        containerImagenesExistentes.appendChild(div);
      });
    }

    juegoModal.show();
  }

  // Función global para eliminar portada (llamada desde HTML string)
  window.eliminarPortadaActual = async function(btn) {
    const ok = await confirmarAccion("¿Eliminar la imagen de portada?");
    if (ok) {
      btn.parentElement.remove();
      formJuegoModal._portadaEliminada = true;
      previewPortada.innerHTML = '<span class="text-muted align-self-center small">Portada eliminada</span>';
    }
  };


  // =============================
  // UTILIDADES
  // =============================

  function mostrarErrores(errors) {
    // Limpiar errores previos
    document.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
    document.querySelectorAll(".invalid-feedback").forEach(el => el.innerText = "");

    // Mapeo de errores
    const map = {
      'titulo': 'nombreJuego',
      'descripcion': 'descripcionJuego',
      'fecha': 'fechaJuego',
      'empresa': 'selectEmpresa',
      'genero': 'selectGeneros',
      'plataforma': 'selectPlataformas',
      'imagen': 'imagenJuego',
      'imagenesExtra': 'imagenesExtra'
    };

    for (const key in errors) {
      const id = map[key] || key;
      const input = document.getElementById(id);
      
      if (input) {
        input.classList.add("is-invalid");
        // Encontrar el div invalid-feedback hermano o cercano
        let feedback = input.nextElementSibling;
        
        // Ajuste para Select2 (el input select está oculto, el feedback está después del container de select2?)
        // Bootstrap pone invalid-feedback después del elemento .form-control. 
        // Con Select2, el select original tiene la clase, pero visualmente se muestra el container.
        // El feedback debería mostrarse si el select tiene is-invalid.
        
        if (input.tagName === "SELECT" && $(input).data('select2')) {
            // Para Select2, a veces hay que forzar el display del feedback o poner la clase al container
            // Simplificación: buscar el .invalid-feedback en el padre
            feedback = input.parentElement.querySelector(".invalid-feedback");
        }

        if (feedback) feedback.innerText = errors[key];
      } else {
        // Error general
        mostrarModal("Error", errors[key]);
      }
    }
  }

  function mostrarErrorCampo(input, msg) {
    input.classList.add("is-invalid");
    const feedback = input.nextElementSibling;
    if (feedback) feedback.innerText = msg;
  }

  function previewImage(input, container) {
    container.innerHTML = "";
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        container.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 100px;">`;
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  function previewImagesMultiple(input, container) {
    container.innerHTML = "";
    if (input.files) {
      Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.createElement("img");
          img.src = e.target.result;
          img.className = "img-thumbnail";
          img.style.maxHeight = "80px";
          container.appendChild(img);
        }
        reader.readAsDataURL(file);
      });
    }
  }

  function mostrarModal(titulo, mensaje) {
    document.getElementById("msgModalTitle").innerText = titulo;
    document.getElementById("msgModalBody").innerHTML = mensaje;
    new bootstrap.Modal(document.getElementById("msgModal")).show();
  }

  function confirmarAccion(mensaje) {
    return new Promise((resolve) => {
      document.querySelector("#confirmModal .modal-body").innerHTML = mensaje;
      const modalEl = document.getElementById("confirmModal");
      const modal = new bootstrap.Modal(modalEl);
      modal.show();

      const btnConfirmar = document.getElementById("confirmDeleteBtn");
      
      // Limpiar listeners previos para evitar duplicados
      const newBtn = btnConfirmar.cloneNode(true);
      btnConfirmar.parentNode.replaceChild(newBtn, btnConfirmar);

      newBtn.addEventListener("click", () => {
        modal.hide();
        resolve(true);
      });

      modalEl.addEventListener('hidden.bs.modal', () => {
        resolve(false);
      }, { once: true });
    });
  }

  // Eliminar Juego
  async function eliminarJuego(id) {
    const ok = await confirmarAccion("¿Seguro que deseas eliminar este juego?");
    if (!ok) return;

    const res = await fetch("./bd/gestion-juegos/eliminar-juego.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id })
    });
    const data = await res.json();

    if (data.success) {
      mostrarModal("Eliminado", data.message);
      cargarJuegos();
    } else {
      mostrarModal("Error", "Error al eliminar el juego.");
    }
  }

  // Publicar / Ocultar
  async function cambiarPublicacion(btn) {
    const id = btn.dataset.id;
    const estadoActual = Number(btn.dataset.publicado);
    const nuevoEstado = estadoActual === 1 ? 0 : 1;
    const mensaje = nuevoEstado === 1 ? "¿Publicar este juego?" : "¿Ocultar este juego?";

    const ok = await confirmarAccion(mensaje);
    if (!ok) return;

    const res = await fetch("./bd/gestion-juegos/toggle-publicar.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, estado: nuevoEstado })
    });
    const data = await res.json();

    if (!data.success) {
      mostrarModal("Error", data.error);
      return;
    }
    cargarJuegos();
  }

};
