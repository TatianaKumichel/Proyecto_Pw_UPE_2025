window.onload = function () {

    const btnNuevaFaq = document.getElementById("btnNuevaFaq");
    const formNuevaFAQ = document.getElementById("formNuevaFAQ");


    btnNuevaFaq.addEventListener("click", function () {
        LimpiarErrores();
        MostrarModalCreacion();
    })
    formNuevaFAQ.addEventListener("submit", function (evento) {

        ValidarCreacionFaq(evento);
    })
}


function MostrarModalCreacion() {

    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalNuevaFAQ"));
    modal.show();

}
function CerrarModalCreacion() {
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalNuevaFAQ"));
    modal.hide();

}

function ValidarCreacionFaq(evento) {

    evento.preventDefault();

    let Okpregunta = ValidarPregunta();
    let OkRespuesta = ValidarRespuesta();
    if (Okpregunta && OkRespuesta) {
        const pregunta = document.getElementById("pregunta").value;
        const respuesta = document.getElementById("respuesta").value;
        AgregarFAQ(pregunta, respuesta);

        CerrarModalCreacion();
        LimpiarForm();
        //aca se enviaria en form , para que se agregue a bd y de ahi sean tomados para mostrarlos 
    }

}

function ValidarPregunta() {
    const pregunta = document.getElementById("pregunta");
    let ErrorPregunta = document.getElementById("ErrorPregunta");

    if (pregunta.value.trim() === "") {

        pregunta.classList.add("is-invalid");
        pregunta.classList.remove("is-valid");
        ErrorPregunta.innerHTML = "Debe ingresar una pregunta";
    } else if (!pregunta.value.startsWith("¿") || !pregunta.value.endsWith("?")) {

        pregunta.classList.add("is-invalid");
        pregunta.classList.remove("is-valid");
        ErrorPregunta.innerHTML = "La pregunta debe comenzar con '¿' y terminar con '?'";

    } else {
        pregunta.classList.remove("is-invalid");
        pregunta.classList.add("is-valid");
        ErrorPregunta.innerHTML = "";
        return true;
    }
}


function ValidarRespuesta() {
    const respuesta = document.getElementById("respuesta");
    let ErrorRespuesta = document.getElementById("ErrorRespuesta");
    if (respuesta.value.trim() == "") {
        respuesta.classList.add("is-invalid");
        respuesta.classList.remove("is-valid");
        ErrorRespuesta.innerHTML = "Debe ingresar una respuesta";

    } else if (respuesta.value.trim().length < 5) {

        respuesta.classList.add("is-invalid");
        respuesta.classList.remove("is-valid");
        ErrorRespuesta.innerHTML = "La respuesta debe tener al menos 5 caracteres";

    } else {
        respuesta.classList.remove("is-invalid");
        respuesta.classList.add("is-valid");
        ErrorRespuesta.innerHTML = "";
        return true;
    }

}

function AgregarFAQ(pregunta, respuesta) {
    const contenedor = document.getElementById("contenedor-faqs");

    const nuevoCard = document.createElement("div");
    nuevoCard.className = "card faq-card mb-4 shadow-sm";

    nuevoCard.innerHTML = `
    <div class="card-body">
      <h5 class="faq-pregunta card-title">
        <i class="bi bi-question-circle me-1 text-primary"></i>${pregunta}
      </h5>
      <p class="faq-respuesta card-text text-muted">${respuesta}</p>
      <div class="d-flex gap-2">
        <button class="btn btn-secondary btn-sm " data-bs-toggle="modal" data-bs-target="#modalEditarFAQ">
          <i class="bi bi-pencil-square"></i> Editar
        </button>
        <button class="btn btn-danger btn-sm " data-bs-toggle="modal" data-bs-target="#modalEliminar">
          <i class="bi bi-trash"></i> Eliminar
        </button>
      </div>
    </div>
  `;

    contenedor.appendChild(nuevoCard);
}
function LimpiarForm() {

    document.getElementById("formNuevaFAQ").reset();
    LimpiarErrores();
}

function LimpiarErrores() {
    document.getElementById("pregunta").classList.remove("is-valid", "is-invalid");
    document.getElementById("respuesta").classList.remove("is-valid", "is-invalid");
}