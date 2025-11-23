document.addEventListener("DOMContentLoaded", () => {
  const faqList = document.getElementById("faq-list");

  // Funcion asincrona para cargar preguntas
  const cargarPreguntas = async () => {
    try {
      const response = await fetch("./bd/gestion-faqs/obtenerFaq.php");
      const data = await response.json();

      if (data.status === "success") {
        data.data.forEach((item) => {
          const preguntaDiv = document.createElement("div");
          preguntaDiv.classList.add("faq-item");

          preguntaDiv.innerHTML = `
                        <h3 class="faq-question">${item.pregunta}</h3>
                        <p class="faq-answer">${item.respuesta}</p>
                    `;

          // Acordeon: mostrar/ocultar respuesta al click
          const pregunta = preguntaDiv.querySelector(".faq-question");
          const respuesta = preguntaDiv.querySelector(".faq-answer");
          pregunta.addEventListener("click", () => {
            respuesta.style.display =
              respuesta.style.display === "block" ? "none" : "block";
          });

          faqList.appendChild(preguntaDiv);
        });
      } else {
        faqList.innerHTML = `<p>Error al cargar preguntas: ${data.message}</p>`;
      }
    } catch (error) {
      faqList.innerHTML = `<p>Error de conexión: ${error.message}</p>`;
    }
  };

  // Ejecutar función
  cargarPreguntas();
});
