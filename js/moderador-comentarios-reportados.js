document.addEventListener("DOMContentLoaded", () => {

    const selectFiltro = document.getElementById("filtroEstado");
    const contenedor = document.getElementById("reportesContainer");

    selectFiltro.addEventListener("change", cargarReportes);

    cargarReportes(); // carga inicial




});

function cargarReportes() {
    fetch("./bd/gestion-reportes/getReportes.php")
        .then(r => r.json())
        .then(data => {
            renderizar(data);
        });
}

function renderizar(reportes) {
    const selectFiltro = document.getElementById("filtroEstado");
    const contenedor = document.getElementById("reportesContainer");
    const filtro = selectFiltro.value;
    contenedor.innerHTML = "";

    const filtrados = reportes.filter(r => {
        if (filtro === "todos") return true;
        return r.estado === filtro;
    });

    if (filtrados.length === 0) {
        contenedor.innerHTML = `<div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1"></i>
            <p class="mt-3 fs-5">No hay reportes en este estado</p>
        </div>`;
        return;
    }

    filtrados.forEach((rep) => {
        const badgeClass = rep.estado === "pendiente" ? "badge-pendiente" : "badge-resuelto";
        const nombreJuego = rep.juego_nombre ?? "Juego desconocido";
        const fechaNormal = new Date(rep.fecha_reporte).toLocaleDateString("es-AR", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit"
        });

        const tiempo = tiempoTranscurrido(rep.fecha_reporte);


        const card = `
           <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">
                
             
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                    <h6 class="mb-0">Reporte #${rep.id_reporte}</h6>
                    <span class="badge ${badgeClass} ms-2">${rep.estado}</span>
                </div>

                            
                         <small class="text-muted"> ${rep.estado === "pendiente"
                ? `${tiempo}`
                : `${fechaNormal}`}
                         </small>
                  </div>

                    <p class="mt-2 mb-1 text-muted">
                        Reportado por: <strong>${rep.usuario_reporta}</strong> · Motivo:
                        <strong>${rep.motivo}</strong>
                    </p>

                    <div class="comment-bloque mt-3 mb-2">
                        <strong>${rep.usuario_comentario}</strong>
                        <p class="mb-1">${rep.comentario_texto}</p>
                        <small class="text-muted">en ${nombreJuego}</small>
                    </div>

                    ${rep.estado === "pendiente"
                ? `
                        <div class="d-flex gap-2 mt-3">
                           <button class="btn btn-danger btn-sm btn-eliminar"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEliminar"
                                data-idcomentario="${rep.id_comentario}"
                                data-idreporte="${rep.id_reporte}">
                                <i class="bi bi-trash me-1"></i> Eliminar Comentario
                            </button>
                            <button class="btn btn-outline-dark btn-sm btn-restringir"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalRestringir"
                                    data-idusuario="${rep.id_usuario_comentario}"
                                    data-idreporte="${rep.id_reporte}">
                                <i class="bi bi-person-x-fill"></i> Restringir Usuario
                            </button>
                           

                            <button class="btn btn-outline-secondary btn-sm btn-descartar"
                                    data-id="${rep.id_reporte}">
                                <i class="bi bi-x-circle me-1"></i> Descartar Reporte
                            </button>
                        </div>
                        `
                : ""
            }

                </div>
            </div>
            `;

        contenedor.innerHTML += card;
    });

    activarBotones();
}


function activarBotones() {

    // DESCARTAR REPORTE
    document.querySelectorAll(".btn-descartar").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;

            const formData = new FormData();
            formData.append("id_reporte", id);

            fetch("bd/gestion-reportes/descartar_reporte.php", {
                method: "POST",
                body: formData
            })
                .then(r => r.json())
                .then(data => {
                    if (data.ok) {
                        cargarReportes();
                    } else {
                        console.error(data);
                    }
                });
        });
    });

    let idComentarioAEliminar = null;
    let idReporteAEliminar = null;

    document.querySelectorAll(".btn-eliminar").forEach(btn => {
        btn.addEventListener("click", () => {
            idComentarioAEliminar = btn.dataset.idcomentario;
            idReporteAEliminar = btn.dataset.idreporte;
        });
    });

    //Eliminar
    document.getElementById("confirmaEliminar").addEventListener("click", () => {
        const fd = new FormData();
        fd.append("id_comentario", idComentarioAEliminar);
        fd.append("id_reporte", idReporteAEliminar);

        fetch("bd/gestion-reportes/eliminar_comentario.php", {
            method: "POST",
            body: fd
        }).then(r => r.json()).then(() => cargarReportes());
    });


    let idUsuarioARestringir = null;
    let idReporteRestringir = null;

    document.querySelectorAll(".btn-restringir").forEach(btn => {
        btn.addEventListener("click", () => {
            idUsuarioARestringir = btn.dataset.idusuario;
            idReporteRestringir = btn.dataset.idreporte;
        });
    });

    // Restringir 
    document.getElementById("confirmaRestringir").addEventListener("click", () => {

        const fd = new FormData();
        fd.append("id_usuario", idUsuarioARestringir);
        fd.append("id_reporte", idReporteRestringir);

        fetch("bd/gestion-reportes/restringir_usuario.php", {
            method: "POST",
            body: fd
        })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    cargarReportes();
                } else {
                    console.error("Error restricción:", data);
                }
            });
    });

}

function tiempoTranscurrido(fechaISO) {
    const fecha = new Date(fechaISO);
    const ahora = new Date();
    const diffMs = ahora - fecha;

    const seg = Math.floor(diffMs / 1000);
    const min = Math.floor(seg / 60);
    const hrs = Math.floor(min / 60);
    const dias = Math.floor(hrs / 24);

    if (dias > 0) return `hace ${dias} día${dias > 1 ? "s" : ""}`;
    if (hrs > 0) return `hace ${hrs} hora${hrs > 1 ? "s" : ""}`;
    if (min > 0) return `hace ${min} minuto${min > 1 ? "s" : ""}`;
    return "hace un momento";
}
