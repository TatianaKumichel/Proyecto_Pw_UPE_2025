window.onload = function () {
  var btnAgregar = document.getElementById("btnAgregarGenero");
  var formGenero = document.getElementById("formGenero");
  var btnConfirmarEliminar = document.getElementById(
    "btnConfirmarEliminarGenero"
  );
  var divErrores = document.getElementById("divErroresGenerales");

  // Abrir modal nuevo género
  btnAgregar.addEventListener("click", function () {
    abrirModalGenero();
  });

  // Guardar género (insert o update)
  formGenero.addEventListener("submit", function (e) {
    e.preventDefault();
    guardarGenero();
  });

  // Confirmar eliminación
  btnConfirmarEliminar.addEventListener("click", function () {
    eliminarGenero();
  });

  cargarGeneros();
};

// --- FUNCIONES ---

function mostrarError(element, mensaje) {
  element.classList.add("is-invalid");
  element.nextElementSibling.textContent = mensaje;
}

function limpiarError(element) {
  element.classList.remove("is-invalid");
  element.nextElementSibling.textContent = "";
}

function mostrarErroresGenerales(errores, divErrores) {
  if (errores) {
    divErrores.textContent = errores;
    divErrores.classList.remove("d-none");
  } else {
    divErrores.textContent = "";
    divErrores.classList.add("d-none");
  }
}

// Cargar géneros desde getGenero.php
function cargarGeneros() {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "inc/getGenero.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      try {
        var data = JSON.parse(xhr.responseText);
        var tbody = document.getElementById("tablaGeneros");
        tbody.innerHTML = "";
        for (var i = 0; i < data.length; i++) {
          var fila = document.createElement("tr");

          var tdId = document.createElement("td");
          tdId.textContent = data[i].id_genero;

          var tdNombre = document.createElement("td");
          tdNombre.textContent = data[i].nombre;

          var tdAcciones = document.createElement("td");
          var divAcciones = document.createElement("div");
          divAcciones.className = "d-flex justify-content-center gap-1";

          var btnEditar = document.createElement("button");
          btnEditar.className = "btn btn-outline-warning btn-sm";
          btnEditar.title = "Editar";
          btnEditar.innerHTML = '<i class="bi bi-pencil-square"></i>';
          btnEditar.addEventListener(
            "click",
            (function (id, nombre) {
              return function () {
                abrirModalGenero(id, nombre);
              };
            })(data[i].id_genero, data[i].nombre)
          );

          var btnEliminar = document.createElement("button");
          btnEliminar.className = "btn btn-outline-danger btn-sm";
          btnEliminar.title = "Eliminar";
          btnEliminar.innerHTML = '<i class="bi bi-trash"></i>';
          btnEliminar.addEventListener(
            "click",
            (function (id) {
              return function () {
                abrirModalEliminarGenero(id);
              };
            })(data[i].id_genero)
          );

          divAcciones.appendChild(btnEditar);
          divAcciones.appendChild(btnEliminar);
          tdAcciones.appendChild(divAcciones);

          fila.appendChild(tdId);
          fila.appendChild(tdNombre);
          fila.appendChild(tdAcciones);

          tbody.appendChild(fila);
        }
      } catch (e) {
        mostrarErroresGenerales(
          "Error cargando géneros",
          document.getElementById("divErroresGenerales")
        );
      }
    }
  };
  xhr.send();
}

// Abrir modal agregar/editar
function abrirModalGenero(id, nombre) {
  var modalEl = document.getElementById("modalGenero");
  var modal = new bootstrap.Modal(modalEl);
  var inputNombre = document.getElementById("nombreGeneroModal");
  var inputId = document.getElementById("idGeneroModal");
  limpiarError(inputNombre);

  if (id) {
    document.getElementById("modalGeneroTitle").textContent = "Editar Género";
    inputNombre.value = nombre;
    inputId.value = id;
  } else {
    document.getElementById("modalGeneroTitle").textContent = "Nuevo Género";
    inputNombre.value = "";
    inputId.value = "";
  }

  modal.show();
}

// Abrir modal eliminar
var generoAEliminar = null;
function abrirModalEliminarGenero(id) {
  generoAEliminar = id;
  var modal = new bootstrap.Modal(
    document.getElementById("modalConfirmarEliminar")
  );
  modal.show();
}

// Guardar género
function guardarGenero() {
  var inputNombre = document.getElementById("nombreGeneroModal");
  var inputId = document.getElementById("idGeneroModal");
  var nombre = inputNombre.value.trim();
  var id = inputId.value;

  if (nombre === "") {
    mostrarError(inputNombre, "Nombre obligatorio");
    return;
  }
  limpiarError(inputNombre);

  var xhr = new XMLHttpRequest();
  var url = id ? "inc/updateGenero.php" : "inc/insertGenero.php";
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      try {
        var res = JSON.parse(xhr.responseText);
        if (res.ok) {
          var modal = bootstrap.Modal.getInstance(
            document.getElementById("modalGenero")
          );
          modal.hide();
          cargarGeneros();
          mostrarErroresGenerales(
            "",
            document.getElementById("divErroresGenerales")
          );
        } else {
          mostrarErroresGenerales(
            res.error,
            document.getElementById("divErroresGenerales")
          );
        }
      } catch (e) {
        mostrarErroresGenerales(
          "Respuesta inválida del servidor",
          document.getElementById("divErroresGenerales")
        );
      }
    }
  };
  var params = "nombre=" + encodeURIComponent(nombre);
  if (id) params += "&id_genero=" + encodeURIComponent(id);
  xhr.send(params);
}

// Eliminar género
function eliminarGenero() {
  if (!generoAEliminar) return;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "inc/delGenero.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      try {
        var res = JSON.parse(xhr.responseText);
        if (res.ok) {
          var modal = bootstrap.Modal.getInstance(
            document.getElementById("modalConfirmarEliminar")
          );
          modal.hide();
          cargarGeneros();
          generoAEliminar = null;
          mostrarErroresGenerales(
            "",
            document.getElementById("divErroresGenerales")
          );
        } else {
          mostrarErroresGenerales(
            res.error,
            document.getElementById("divErroresGenerales")
          );
        }
      } catch (e) {
        mostrarErroresGenerales(
          "Respuesta inválida del servidor",
          document.getElementById("divErroresGenerales")
        );
      }
    }
  };
  xhr.send("id_genero=" + encodeURIComponent(generoAEliminar));
}
