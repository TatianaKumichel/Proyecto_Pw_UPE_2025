window.onload = function () {
    CargarFAQs();
    const btnNuevaFaq = document.getElementById("btnNuevaFaq");
    const formFAQ = document.getElementById("formNuevaFAQ");
    const btnConfirmarEliminacion = document.getElementById("btnConfirmarEliminar");

    btnNuevaFaq.addEventListener("click", function () {
        LimpiarForm();
        formFAQ.dataset.mode = "create";

        document.querySelector("#modalNuevaFAQ .modal-title").innerText = "Nueva FAQ";
        document.getElementById("btnCrearFaq").innerText = "Guardar";

        MostrarModal();
    });

    document.addEventListener("click", async function (event) {
        const botonEdit = event.target.closest(".btnEditarFaq");
        LimpiarErrores();
        if (botonEdit) {
            const id = botonEdit.getAttribute("data-id");

            // modifico el modal para editar
            formNuevaFAQ.dataset.mode = "edit";
            formNuevaFAQ.dataset.id = id;

            await CargarFAQPorId(id);

            document.querySelector("#modalNuevaFAQ .modal-title").innerText = "Editar FAQ";
            document.getElementById("btnCrearFaq").innerText = "Guardar Cambios";
            document.getElementById("descripcionForm").innerText = "Edita la pregunta frecuente"
            MostrarModal();
        }
    });

    document.addEventListener("click", function (event) {
        const botonElim = event.target.closest(".btnEliminarFaq");

        if (botonElim) {
            const idFaq = botonElim.getAttribute("data-id");
            const modalEliminacion = document.getElementById("modalEliminarFAQ");
            modalEliminacion.dataset.idFaq = idFaq;
        }
    });

    btnConfirmarEliminacion.addEventListener("click", function () {
        const modalEliminacion = document.getElementById("modalEliminarFAQ");
        const idFAQ = modalEliminacion.dataset.idFaq;

        if (idFAQ) {
            EliminarFAQ(idFAQ);
        }
    });



    formFAQ.addEventListener("submit", function (evento) {
        ValidarGuardarFAQ(evento);
    });



}


function MostrarModal() {

    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalNuevaFAQ"));
    modal.show();

}
function CerrarModal() {
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalNuevaFAQ"));
    modal.hide();

}











function ValidarGuardarFAQ(event) {
    event.preventDefault();

    let ok1 = ValidarPregunta();
    let ok2 = ValidarRespuesta();

    if (ok1 && ok2) { GuardarFAQ(); }
}


function CargarFAQs() {

    fetch("./bd/gestion-faqs/obtener-FAQS.php")
        .then(response => response.json())
        .then(data => {
            MostrarFAQs(data);
        })
        .catch(error => console.error("Error al cargar FAQs:", error));
    MostrarError("Ocurrió un error al cargar las preguntas frecuentes. Verifique su conexión o inténtelo más tarde.");
}

async function CargarFAQPorId(id) {
    try {
        let res = await fetch(`./bd/gestion-faqs/obtener-FAQ.php?id_faq=${id}`);
        let response = await res.json();

        if (!response.success) {
            console.error("Error:", response.message);
            return;
        }

        let data = response.data;
        document.getElementById("pregunta").value = data.pregunta;
        document.getElementById("respuesta").value = data.respuesta;

    } catch (err) {
        console.error("Error ", err);
    }
}
async function GuardarFAQ() {
    const form = document.getElementById("formNuevaFAQ");
    const formData = new FormData();
    LimpiarErrores();
    formData.append("pregunta", document.getElementById("pregunta").value.trim());
    formData.append("respuesta", document.getElementById("respuesta").value.trim());

    if (form.dataset.mode === "edit") {
        formData.append("id", form.dataset.id);
    }

    try {
        const response = await fetch("./bd/gestion-faqs/guardar-FAQ.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (result.success) {  // se guardo

            CerrarModal();
            CargarFAQs();
            MostrarExito("¡Éxito!", result.message);
        } else if (result.errors) {
            // errores abajo de los input
            if (result.errors.pregunta) {
                const pregunta = document.getElementById("pregunta");
                pregunta.classList.add("is-invalid");
                document.getElementById("ErrorPregunta").innerText = result.errors.pregunta;
            }

            if (result.errors.respuesta) {
                const respuesta = document.getElementById("respuesta");
                respuesta.classList.add("is-invalid");
                document.getElementById("ErrorRespuesta").innerText = result.errors.respuesta;
            }

            if (result.errors.general) {
                MostrarError(result.errors.general);
            }
        }

    } catch (err) {
        console.error("Error:", err);
        MostrarError("Error inesperado al guardar la FAQ.");
    }
}


function MostrarFAQs(faqs) {
    const contenedor = document.getElementById("contenedor-faqs");
    contenedor.innerHTML = "";
    if (!faqs || faqs.length === 0) {
        contenedor.innerHTML = `
            <div class="card text-center shadow-sm p-4">
                <div class="card-body">
                    <i class="bi bi-emoji-neutral text-muted fs-1 mb-3"></i>
                    <h5 class="card-title">No hay preguntas frecuentes</h5>
                    <p class="card-text text-muted">
                        Aún no se ha agregado ninguna FAQ. 
                        Podés crear una nueva haciendo clic en <strong>"Nueva FAQ"</strong>.
                    </p>
                </div>
            </div>
        `;
        return;
    }
    faqs.forEach(faq => {
        contenedor.innerHTML += `
        <div class="card faq-card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="faq-pregunta card-title">
                    <i class="bi bi-question-circle me-1 text-primary"></i>${faq.pregunta}
                </h5>
                <p class="faq-respuesta card-text text-muted">${faq.respuesta}</p>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary btn-sm  btnEditarFaq " data-id="${faq.id_faq}">
                        <i class="bi bi-pencil-square"></i> Editar
                    </button>
                    <button class="btn btn-danger btn-sm  btnEliminarFaq" data-bs-toggle="modal" data-bs-target="#modalEliminarFAQ" data-id="${faq.id_faq}">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>`;
    });
}


async function EliminarFAQ(idFAQ) {
    const modal = document.getElementById("modalEliminarFAQ");
    const modalBootstrap = bootstrap.Modal.getOrCreateInstance(modal);

    try {
        const formData = new FormData();
        formData.append("id", idFAQ);

        const response = await fetch("./bd/gestion-faqs/eliminar-FAQ.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            modalBootstrap.hide();
            CargarFAQs();
            MostrarExito("¡Eliminada!", "La pregunta fue eliminada correctamente.");
        } else {
            modalBootstrap.hide();
            MostrarError(result.message || "No se pudo eliminar la FAQ.");
        }

    } catch (err) {
        console.error("Error:", err);
        modalBootstrap.hide();
        MostrarError("Error inesperado al eliminar la FAQ.");
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


function LimpiarForm() {

    document.getElementById("formNuevaFAQ").reset();
    LimpiarErrores();
}

function LimpiarErrores() {
    document.getElementById("pregunta").classList.remove("is-valid", "is-invalid");
    document.getElementById("respuesta").classList.remove("is-valid", "is-invalid");
}



// modales de exito - error 
function MostrarExito(titulo = "Éxito", mensaje = "Operación realizada correctamente") {
    const modalExito = document.getElementById("modalExito");

    document.getElementById("titulo-exito").innerText = titulo;
    modalExito.querySelector(".modal-body p").innerText = mensaje;

    const instancia = bootstrap.Modal.getOrCreateInstance(modalExito);
    instancia.show();
}


function MostrarError(mensaje = "Ocurrió un error inesperado") {
    document.getElementById("mensaje-error").innerText = mensaje;
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalError"));
    modal.show();
}