// Obtener todas las preguntas
const preguntas = document.querySelectorAll(".faq-pregunta");

preguntas.forEach((pregunta) => {
  pregunta.addEventListener("click", () => {
    const respuesta = pregunta.nextElementSibling; // la respuesta está justo después de la pregunta
    respuesta.classList.toggle("oculto"); // muestra u oculta la respuesta
  });
});
