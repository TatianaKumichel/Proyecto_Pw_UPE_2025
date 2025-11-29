/*document.addEventListener("DOMContentLoaded", () => {
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
});*/

// document.addEventListener("DOMContentLoaded", () => {
//   const faqList = document.getElementById("faq-list");

//   const cargarPreguntas = async () => {
//     try {
//       const response = await fetch("./bd/gestion-faqs/obtenerFaq.php");
//       const data = await response.json();

//       if (data.status === "success") {

//         if (data.data.length === 0) {     /* no hay preguntas cargadas*/
//           faqList.innerHTML = `
//             <div class="faq-empty">
//                 <i class="bi bi-chat-square-dots"></i>
//                 <h3>No hay preguntas frecuentes</h3>
//                 <p>Aún no se cargó ninguna pregunta.</p>
//               </div>
//          `;
//           return;
//         }
//         data.data.forEach((item) => {
//           const preguntaDiv = document.createElement("div");
//           preguntaDiv.classList.add("faq-item");

//           preguntaDiv.innerHTML = `
//             <div class="faq-question">
//               ${item.pregunta}
//               <span class="arrow">▼</span>
//             </div>
//             <div class="faq-answer">
//               ${item.respuesta}
//             </div>
//           `;

//           const pregunta = preguntaDiv.querySelector(".faq-question");

//           pregunta.addEventListener("click", () => {

//             /*manejo que solo haya una respuesta a la vez */
//             document.querySelectorAll(".faq-item").forEach((other) => {
//               if (other !== preguntaDiv) {
//                 other.classList.remove("active");
//               }
//             });

//             preguntaDiv.classList.toggle("active");
//           });

//           faqList.appendChild(preguntaDiv);
//         });

//       } else {
//         faqList.innerHTML = `<p>Error al cargar preguntas: ${data.message}</p>`;
//       }
//     } catch (error) {
//       faqList.innerHTML = `<p>Error de conexión: ${error.message}</p>`;
//     }
//   };

//   cargarPreguntas();
// });

document.addEventListener("DOMContentLoaded", () => {
  const faqAccordion = document.getElementById("faqAccordion");

  const cargarPreguntas = async () => {
    try {
      const response = await fetch("./bd/gestion-faqs/obtenerFaq.php");
      const data = await response.json();

      if (data.status === "success") {

        if (data.data.length === 0) {
          faqAccordion.innerHTML = `
            <div class="text-center border rounded p-5 bg-light">
              <i class="bi bi-chat-square-dots fs-1 mb-3"></i>
              <h4>No hay preguntas frecuentes</h4>
              <p>Aún no se cargó ninguna pregunta.</p>
            </div>
          `;
          return;
        }

        faqAccordion.innerHTML = ""; 

        data.data.forEach((item, index) => {
          const headerId = `faqHeader${index}`;
          const collapseId = `faqCollapse${index}`;

          faqAccordion.innerHTML += `
            <div class="accordion-item faq-card mb-3">

              <h2 class="accordion-header" id="${headerId}">
                <button class="accordion-button collapsed fw-semibold"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#${collapseId}"
                        aria-expanded="false"
                        aria-controls="${collapseId}">
                  ${item.pregunta}
                </button>
              </h2>

              <div id="${collapseId}"
                   class="accordion-collapse collapse"
                   aria-labelledby="${headerId}"
                   data-bs-parent="#faqAccordion">

                <div class="accordion-body">
                  ${item.respuesta}
                </div>
              </div>

            </div>
          `;
        });

      } else {
        faqAccordion.innerHTML = `<p>Error al cargar preguntas: ${data.message}</p>`;
      }
    } catch (error) {
      faqAccordion.innerHTML = `<p>Error de conexión: ${error.message}</p>`;
    }
  };

  cargarPreguntas();
});
