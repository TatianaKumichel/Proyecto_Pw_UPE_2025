<!DOCTYPE html>
<html lang="es">
<head>
  <?php
    require "./inc/head.php";
  ?>

  <link rel="stylesheet" href="./css/faqPublico.css" />
  <script defer src="./js/faqPublico.js"></script>
</head>

<body class="d-flex flex-column min-vh-100">
  <header>
    <?php
      require "./inc/menu.php";
    ?>
  </header>

  <div id="faq-container">
    <h2 class="titulo ">Preguntas Frecuentes</h2>
    <div id ="faq-list">
      <!-- Aca se carga las preguntas -->
    </div>
  </div>

  <footer class="bg-dark text-white mt-auto pt-3 pb-2">
    <?php
      require "./inc/footer.php";
    ?>
  </footer>

</body>
</html>
