function mostrarErroresGenerales(errores, divErrores) {
  if (errores.general) {
    divErrores.innerText = errores.general;
    divErrores.classList.remove("d-none");
  } else {
    divErrores.classList.add("d-none");
    divErrores.innerText = "";
  }
}

async function cargarFavoritos(divErrores, contenedor, modal) {
  var errores = {};
  divErrores.classList.add("d-none");
  divErrores.innerHTML = "";

  try {
    var response = await fetch("./inc/getFavoritos.php", { method: "GET" });
    if (!response.ok) {
      errores["conexion"] = "Error al consultar los favoritos.";
      mostrarErrores(divErrores, errores);
      return;
    }

    var data = await response.json();
    if (!Array.isArray(data)) {
      errores["formato"] = "Error al consultar los favoritos.";
      mostrarErrores(divErrores, errores);
      return;
    }

    contenedor.innerHTML = "";

    for (var i = 0; i < data.length; i++) {
      var juego = data[i];

      var divCol = document.createElement("div");
      divCol.className = "col";

      var divCard = document.createElement("div");
      divCard.className = "card h-100";

      var img = document.createElement("img");
      img.src = juego.imagen_portada;
      img.className = "card-img-top";
      img.alt = juego.titulo;

      var divBody = document.createElement("div");
      divBody.className = "card-body d-flex flex-column";

      var h5 = document.createElement("h5");
      h5.className = "card-title";
      h5.textContent = juego.titulo;

      var p = document.createElement("p");
      p.className = "card-text";
      p.textContent = juego.descripcion;

      var boton = document.createElement("button");
      boton.className = "btn btn-outline-danger mt-auto btn-favorito";
      boton.innerHTML = '<i class="bi bi-heart-fill"></i> Quitar de favoritos';
      // Id del juego
      boton.setAttribute("data-id-juego", juego.id_juego);

      // Evento confirmacion
      boton.addEventListener("click", function () {
        var idJuego = this.getAttribute("data-id-juego");
        confirmarEliminacion(idJuego, modal, divErrores, contenedor);
      });

      divBody.appendChild(h5);
      divBody.appendChild(p);
      divBody.appendChild(boton);

      divCard.appendChild(img);
      divCard.appendChild(divBody);

      divCol.appendChild(divCard);
      contenedor.appendChild(divCol);
    }
  } catch (e) {
    errores["excepcion"] = "Error inesperado: " + e.message;
    mostrarErrores(divErrores, errores);
  }
}

// Modal confirmacion
function confirmarEliminacion(idJuego, modal, divErrores, contenedor) {
  var btnConfirmar = document.getElementById("btnConfirmarEliminar");

  // Id juego
  btnConfirmar.dataset.idJuego = idJuego;

  var bsModal = new bootstrap.Modal(modal);
  bsModal.show();
}

window.onload = function () {
  var divErrores = document.getElementById("divErroresGenerales");
  var contenedor = document.getElementById("listaFavoritos");
  var modal = document.getElementById("modalConfirmar");
  var btnConfirmar = document.getElementById("btnConfirmarEliminar");

  btnConfirmar.addEventListener("click", async function () {
    var idJuego = this.dataset.idJuego;
    if (idJuego) {
      await eliminarFavorito(idJuego, modal, divErrores, contenedor);
    }
  });

  cargarFavoritos(divErrores, contenedor, modal);
};

async function eliminarFavorito(id, modal, divErrores, contenedor) {
  var errores = {};
  divErrores.classList.add("d-none");
  divErrores.innerHTML = "";
  let data = {
    id: id,
  };
  try {
    var response = await fetch("./inc/delFavoritos.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    if (!response.ok) {
      errores["conexion"] = "Error al eliminar el favorito.";
      mostrarErrores(divErrores, errores);
      return;
    }

    var result = await response.json();
    console.log(result);

    if (result.ok) {
      var bsModal = bootstrap.Modal.getInstance(modal);
      bsModal.hide();
      await cargarFavoritos(divErrores, contenedor, modal);
    } else {
      errores["negocio"] = result.error || "Error al eliminar.";
      mostrarErrores(divErrores, errores);
    }
  } catch (e) {
    errores["excepcion"] = "Error inesperado: " + e.message;
    mostrarErrores(divErrores, errores);
  }
}

function mostrarErrores(divErrores, errores) {
  divErrores.classList.remove("d-none");
  divErrores.innerHTML = "";
  for (var clave in errores) {
    var p = document.createElement("p");
    p.textContent = errores[clave];
    divErrores.appendChild(p);
  }
}
