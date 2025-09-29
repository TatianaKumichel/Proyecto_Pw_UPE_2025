// admin-juegos.js

//  ELEMENTOS PRINCIPALES 
const btnAgregarJuego = document.getElementById('btnAgregarJuego');
const formJuego = document.getElementById('formJuego');
const cancelarJuego = document.getElementById('cancelarJuego');
const tablaJuegos = document.getElementById('tabla-juegos');

// Inputs del formulario
const imagenJuego = document.getElementById('imagenJuego');
const nombreJuego = document.getElementById('nombreJuego');
const descripcionJuego = document.getElementById('descripcionJuego');
const plataformaJuego = document.getElementById('plataformaJuego');
const generoJuego = document.getElementById('generoJuego');
const empresaJuego = document.getElementById('empresaJuego');
const fechaJuego = document.getElementById('fechaJuego');

let filaEditando = null; // Para saber si estamos editando

// FUNCIONES AUXILIARES 
function crearFilaJuego(juego) {
  const tr = document.createElement('tr');

  tr.innerHTML = `
    <td class="game-img"><img src="${juego.imagen}" alt="Juego" class="img-thumbnail"></td>
    <td>${juego.nombre}</td>
    <td>${juego.descripcion}</td>
    <td>${juego.plataforma}</td>
    <td>${juego.genero}</td>
    <td>${juego.empresa}</td>
    <td>${juego.fecha}</td>
    <td>
      <button class="btn btn-outline-warning btn-sm me-1 btn-editar">
        <i class="bi bi-pencil-square"></i>
      </button>
      <button class="btn btn-outline-danger btn-sm btn-eliminar">
        <i class="bi bi-trash"></i>
      </button>
      <button class="btn btn-outline-success btn-sm btn-publicar">
                <i class="bi bi-check-circle"></i>
              </button>
    </td>
  `;

  // Botón eliminar
  tr.querySelector('.btn-eliminar').addEventListener('click', () => {
    tr.remove();
    if (filaEditando === tr) filaEditando = null;
  });

  // Botón editar
  tr.querySelector('.btn-editar').addEventListener('click', () => {
    formJuego.classList.remove('d-none');
    filaEditando = tr;

    // Llenamos el formulario con los datos de la fila
    nombreJuego.value = tr.children[1].textContent;
    descripcionJuego.value = tr.children[2].textContent;
    plataformaJuego.value = tr.children[3].textContent;
    generoJuego.value = tr.children[4].textContent;
    empresaJuego.value = tr.children[5].textContent;
    fechaJuego.value = tr.children[6].textContent;

    // Imagen: no se puede rellenar input file, usamos variable temporal
    imagenJuego.value = '';

  });
  return tr;
}

// FUNCIONES PARA GUARDAR 
function guardarJuego(e) {
  e.preventDefault();

  let imagen = 'https://via.placeholder.com/80';
  if (imagenJuego.files && imagenJuego.files[0]) {
    imagen = URL.createObjectURL(imagenJuego.files[0]);
  } else if (filaEditando) {
    // Mantener imagen anterior si no se cambió
    imagen = filaEditando.querySelector('td:first-child img').src;
  }

  const juego = {
    imagen: imagen,
    nombre: nombreJuego.value,
    descripcion: descripcionJuego.value,
    plataforma: plataformaJuego.value,
    genero: generoJuego.value,
    empresa: empresaJuego.value,
    fecha: fechaJuego.value
  };

  if (filaEditando) {
    // Actualizar fila existente
    filaEditando.querySelector('td:first-child img').src = juego.imagen;
    filaEditando.querySelector('td:nth-child(2)').textContent = juego.nombre;
    filaEditando.querySelector('td:nth-child(3)').textContent = juego.descripcion;
    filaEditando.querySelector('td:nth-child(4)').textContent = juego.plataforma;
    filaEditando.querySelector('td:nth-child(5)').textContent = juego.genero;
    filaEditando.querySelector('td:nth-child(6)').textContent = juego.empresa;
    filaEditando.querySelector('td:nth-child(7)').textContent = juego.fecha;

    filaEditando = null;
  } else {
    // Crear nueva fila
    const nuevaFila = crearFilaJuego(juego);
    tablaJuegos.appendChild(nuevaFila);
  }

  formJuego.reset();
  formJuego.classList.add('d-none');
}

//  EVENTOS 
btnAgregarJuego.addEventListener('click', () => {
  formJuego.classList.toggle('d-none');
  formJuego.reset();
  filaEditando = null;
});

formJuego.addEventListener('submit', guardarJuego);

cancelarJuego.addEventListener('click', () => {
  formJuego.reset();
  formJuego.classList.add('d-none');
  filaEditando = null;
});
