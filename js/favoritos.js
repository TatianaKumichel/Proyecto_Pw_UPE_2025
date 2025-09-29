window.onload = function () {
  var btnFavoritos = document.querySelectorAll(".btn-favorito");

  btnFavoritos.forEach(function (btn) {
    btn.addEventListener("click", function () {
      var card = this.closest(".col");
      card.remove();
    });
  });
};
