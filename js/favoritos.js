window.onload = function () {
  var btnFavoritos = document.querySelectorAll(".btn-favorito");

  btnFavoritos.forEach(function (btn) {
    btn.addEventListener("click", function () {
      // Quitar favorito: eliminar el card de la lista
      var card = this.closest(".col");
      card.remove();
    });
  });
};
