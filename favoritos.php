<?php
// Proteger la página - solo usuarios logueados
require_once './inc/auth.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    require "./inc/head.php";
    ?>
    <link rel="stylesheet" href="css/favoritos.css" />
    <script src="./js/favoritos.js" defer></script>
</head>

<body>
    <header>
        <?php
        require "./inc/menu.php";
        ?>
    </header>

    <main class="container my-4">
        <h2 class="mb-4">Favoritos</h2>

        <!-- Errores generales -->
        <div id="divErroresGenerales" class="alert alert-danger d-none" role="alert"></div>

        <!-- Listado de favoritos -->
        <div id="listaFavoritos" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"></div>
    </main>

    <footer class="bg-dark text-white mt-4 pt-3 pb-2">
        <?php
        require "./inc/footer.php";
        ?>
    </footer>

    <!-- Modal -->
    <div class="modal fade" id="modalConfirmar" tabindex="-1" aria-labelledby="modalConfirmarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmarLabel">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i> Confirmar acción
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que querés quitar este juego de tus favoritos?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">
                        <i class="bi bi-trash-fill"></i> Quitar
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>