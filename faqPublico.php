<!DOCTYPE html>
<html lang="es">
  <head>
    <?php
    require "./inc/head.php";
    ?>

    <link rel="stylesheet" href="./css/faqPublico.css" />
    <script src="./js/faqPublico.js" defer></script>
  </head>
  <!--d-flex flex-column min-vh-100 bg-light-->
  <body class="d-flex flex-column min-vh-100 bg-light">
    <header>
    <?php
    require "./inc/menu.php";
    ?>
    </header>
    <main class="container my-4 flex-fill">
      <div class="container py-5">
        <h2 class="mb-4 text-center">Preguntas Frecuentes</h2>

        <div class="accordion" id="faqContainer">
          <div class="faq-item mb-3">
            <div
              class="faq-pregunta p-3 bg-primary text-white rounded"
              data-index="0"
            >
              ¿Cómo me registro en la página?
            </div>
            <div class="faq-respuesta p-3 border rounded mt-1 oculto">
              Para registrarte, haz clic en "Registrarse" en la esquina superior
              y completa el formulario con tus datos.
            </div>
          </div>

          <div class="faq-item mb-3">
            <div
              class="faq-pregunta p-3 bg-primary text-white rounded"
              data-index="1"
            >
              ¿Como contacto con soporte?
            </div>
            <div class="faq-respuesta p-3 border rounded mt-1 oculto">
              Envia un mail a soporte@gmail.com
            </div>
          </div>

          <div class="faq-item mb-3">
            <div
              class="faq-pregunta p-3 bg-primary text-white rounded"
              data-index="2"
            >
              ¿Como puedo denunciar un comentario?
            </div>
            <div class="faq-respuesta p-3 border rounded mt-1 oculto">
              Debajo del comentario vas a encontrar la opcion para hacer la
              denuncia.
            </div>
          </div>
        </div>
      </div>
    </main>
    <footer class="bg-dark text-white mt-4 pt-3 pb-2">
    <?php
      require "./inc/footer.php";
    ?>
    </footer>
  </body>
</html>
