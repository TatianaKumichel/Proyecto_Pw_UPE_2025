window.onload = function () {
    const filtro = document.getElementById("filtroEstado");


    filtro.addEventListener("change", function () {
        FiltrarCards(filtro.value.toLowerCase());
    });


    FiltrarCards("todos");
}

// obtiene el estado del badge de una card
function ObtenerEstado(card) {
    const badge = card.querySelector(".badge");
    return badge.textContent.trim().toLowerCase();
}

// muestra/oculta las cards segun  el estado
function FiltrarCards(estadoSeleccionado) {
    const cards = document.querySelectorAll(".card");

    cards.forEach(card => {
        const estadoCard = ObtenerEstado(card);

        if (estadoSeleccionado === "todos" || estadoCard === estadoSeleccionado) {
            MostrarCard(card);
        } else {
            OcultarCard(card);
        }
    });
}


function MostrarCard(card) {
    card.style.display = "";
}

function OcultarCard(card) {
    card.style.display = "none";
}

