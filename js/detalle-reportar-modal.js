/**
 * Mostrar formulario de reporte
 */
function mostrarFormularioReporte(id) {
  // Obtener elementos del modal
  const modal = new bootstrap.Modal(
    document.getElementById("modalReportarComentario")
  );
  const textarea = document.getElementById("motivoReporte");
  const contador = document.getElementById("contadorReporte");
  const btnConfirmar = document.getElementById("btnConfirmarReporte");

  // Limpiar textarea
  textarea.value = "";
  contador.textContent = "0";

  // Remover event listener anterior si existe
  const oldListener = textarea.getAttribute("data-listener");
  if (oldListener) {
    textarea.removeEventListener("input", window[oldListener]);
  }

  // Crear función para actualizar contador
  const updateCounter = () => {
    contador.textContent = textarea.value.length;
  };

  // Guardar referencia y agregar event listener
  window.updateCounterReporte = updateCounter;
  textarea.setAttribute("data-listener", "updateCounterReporte");
  textarea.addEventListener("input", updateCounter);

  // Mostrar modal
  modal.show();

  // Remover event listeners anteriores del botón confirmar
  const nuevoBtn = btnConfirmar.cloneNode(true);
  btnConfirmar.parentNode.replaceChild(nuevoBtn, btnConfirmar);

  // Agregar nuevo event listener
  nuevoBtn.addEventListener("click", () => {
    const motivo = textarea.value.trim();

    if (!motivo) {
      mostrarNotificacion("Debes indicar un motivo para el reporte", "warning");
      return;
    }

    if (motivo.length < 10) {
      mostrarNotificacion(
        "El motivo debe tener al menos 10 caracteres",
        "warning"
      );
      return;
    }

    modal.hide();
    reportarComentario(id, motivo);
  });
}
