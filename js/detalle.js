/* ------------------------------------------------------------
 No usaremos variables globales, pero para la simulación de rol sin backend:
 true = usuario registrado, false = visitante
 esto será totalmente reemplazado cuando haya backend
 */
var usuarioRegistrado = true;

window.onload = function () {
  // Configuaracion usuario registrado.
  // se cambiara cuando haya backend
  if (!usuarioRegistrado) {
    // Ocultar favorito
    const btnFavorito = document.getElementById("btn-favorito");
    btnFavorito.classList.add("invisible");

    // Ocultar calificación
    var calificacionTitulo = document.getElementById("tituloCalificacion");
    calificacionTitulo.classList.add("invisible");
    const calificacion = document.getElementById("calificacion");
    calificacion.classList.add("invisible");

    // Ocultar formularios de comentarios
    const formComentario = document.getElementById("form-comentario");
    formComentario.classList.add("invisible");

    // Ocultar botones de reportar en comentarios existentes
    var btnReportar = document.querySelectorAll(".card .btn-warning");
    btnReportar.forEach(function (btn) {
      btn.classList.add("invisible");
    });
  }

  // FAVORITO
  var btnFavorito = document.getElementById("btn-favorito");
  var iconoFavorito = btnFavorito.querySelector("i");

  btnFavorito.addEventListener("click", function () {
    if (iconoFavorito.classList.contains("bi-heart")) {
      // Marcar como favorito
      iconoFavorito.classList.remove("bi-heart");
      iconoFavorito.classList.add("bi-heart-fill");
      btnFavorito.classList.remove("btn-outline-danger");
      btnFavorito.classList.add("btn-danger");
      btnFavorito.textContent = " Favorito";
      btnFavorito.prepend(iconoFavorito);
    } else {
      // Quitar favorito
      iconoFavorito.classList.remove("bi-heart-fill");
      iconoFavorito.classList.add("bi-heart");
      btnFavorito.classList.remove("btn-danger");
      btnFavorito.classList.add("btn-outline-danger");
      btnFavorito.textContent = " Marcar como favorito";
      btnFavorito.prepend(iconoFavorito);
    }
  });

  // CALIFICACIÓN
  var estrellas = document.querySelectorAll("#calificacion .estrella");
  var valorSeleccionado = 0; // guarda la calificación actual

  for (var i = 0; i < estrellas.length; i++) {
    estrellas[i].addEventListener("click", function () {
      var valor = parseInt(this.getAttribute("data-valor"));

      if (valor === valorSeleccionado) {
        // Si vuelve a hacer clic en la misma estrella, descalificar
        valorSeleccionado = 0;
      } else {
        valorSeleccionado = valor;
      }

      // Reset
      for (var j = 0; j < estrellas.length; j++) {
        estrellas[j].classList.remove("bi-star-fill", "seleccionada");
        estrellas[j].classList.add("bi-star");
      }

      // Llenar hasta la seleccionada (si > 0)
      for (var k = 0; k < valorSeleccionado; k++) {
        estrellas[k].classList.remove("bi-star");
        estrellas[k].classList.add("bi-star-fill", "seleccionada");
      }
    });
  }

  // -----------------------------------
  // MODALES DE BOOTSTRAP
  // -----------------------------------
  var inputEditar = document.getElementById("inputEditar");
  var btnAceptarEditar = document.getElementById("btnAceptarEditar");
  var comentarioActual = null; // referencia al comentario que se edita

  var btnConfirmarEliminar = document.getElementById("btnConfirmarEliminar");
  var comentarioAEliminar = null;

  // REPORTAR
  function configurarReportar(btn) {
    btn.addEventListener("click", function () {
      var modal = new bootstrap.Modal(document.getElementById("modalReportar"));
      modal.show();
    });
  }

  // EDITAR
  function configurarEditar(btn) {
    btn.addEventListener("click", function () {
      comentarioActual = this.closest(".card").querySelector(".card-text");
      inputEditar.value = comentarioActual.textContent.trim();

      var modal = new bootstrap.Modal(document.getElementById("modalEditar"));
      modal.show();
    });
  }

  btnAceptarEditar.addEventListener("click", function () {
    if (comentarioActual && inputEditar.value.trim() !== "") {
      comentarioActual.textContent = inputEditar.value.trim();
    }
    var modal = bootstrap.Modal.getInstance(
      document.getElementById("modalEditar")
    );
    modal.hide();
  });

  // ELIMINAR
  function configurarEliminar(btn) {
    btn.addEventListener("click", function () {
      comentarioAEliminar = this.closest(".card");

      var modal = new bootstrap.Modal(document.getElementById("modalEliminar"));
      modal.show();
    });
  }

  btnConfirmarEliminar.addEventListener("click", function () {
    if (comentarioAEliminar) {
      comentarioAEliminar.remove();
      comentarioAEliminar = null;
    }
    var modal = bootstrap.Modal.getInstance(
      document.getElementById("modalEliminar")
    );
    modal.hide();
  });

  // CONFIGURAR BOTONES COMENTARIOS
  var btnReportar = document.querySelectorAll(".card .btn-warning");
  var btnEditar = document.querySelectorAll(".card .btn-secondary");
  var btnEliminar = document.querySelectorAll(".card .btn-danger");

  for (var r = 0; r < btnReportar.length; r++) {
    configurarReportar(btnReportar[r]);
  }
  for (var e = 0; e < btnEditar.length; e++) {
    configurarEditar(btnEditar[e]);
  }
  for (var d = 0; d < btnEliminar.length; d++) {
    configurarEliminar(btnEliminar[d]);
  }

  // AGREGAR NUEVO COMENTARIO
  var formComentario = document.getElementById("form-comentario");
  formComentario.addEventListener("submit", function (event) {
    event.preventDefault();
    var textarea = document.getElementById("nuevoComentario");
    var texto = textarea.value.trim();
    if (texto !== "") {
      var seccionComentarios = document.querySelector("section");
      var nuevoCard = document.createElement("div");
      nuevoCard.className = "card mb-3 border-primary";
      nuevoCard.innerHTML =
        '<div class="card-body">' +
        '<h6 class="card-subtitle mb-2 text-muted">Yo - Ahora</h6>' +
        '<p class="card-text">' +
        texto +
        "</p>" +
        '<button class="btn btn-sm btn-secondary">Editar</button> ' +
        '<button class="btn btn-sm btn-danger">Eliminar</button>' +
        "</div>";
      seccionComentarios.insertBefore(nuevoCard, formComentario);
      textarea.value = "";

      // Configurar botones del nuevo comentario
      var btnEdit = nuevoCard.querySelector(".btn-secondary");
      var btnDel = nuevoCard.querySelector(".btn-danger");

      configurarEditar(btnEdit);
      configurarEliminar(btnDel);
    }
  });

  // REPORTAR COMENTARIO
  var btnAceptarReportar = document.getElementById("btnAceptarReportar");
  var textareaReporte = document.getElementById("razonReporte");

  btnAceptarReportar.addEventListener("click", function () {
    if (textareaReporte.value.trim() === "") {
      textareaReporte.classList.add("is-invalid");
    } else {
      textareaReporte.classList.remove("is-invalid");
      // Cierra el modal
      var modal = bootstrap.Modal.getInstance(
        document.getElementById("modalReportar")
      );
      modal.hide();
    }
  });
};
