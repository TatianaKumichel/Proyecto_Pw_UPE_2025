// --- ELEMENTOS PRINCIPALES ---
const btnAgregarGenero = document.getElementById('btnAgregarGenero');
const formGenero = document.getElementById('formGenero');
const cancelarGenero = document.getElementById('cancelarGenero');
const tablaGeneros = document.getElementById('tablaGeneros');
const nombreGenero = document.getElementById('nombreGenero');

let nextId = tablaGeneros.children.length + 1;
let filaEditando = null;

// --- FUNCIONES AUXILIARES ---
function crearFilaGenero(genero) {
  const tr = document.createElement('tr');

  tr.innerHTML = `
    <td>${genero.id}</td>
    <td>${genero.nombre}</td>
    <td>
      <button class="btn btn-outline-warning btn-sm me-1 btn-editar">
        <i class="bi bi-pencil-square"></i>
      </button>
      <button class="btn btn-outline-danger btn-sm me-1 btn-eliminar">
        <i class="bi bi-trash"></i>
      </button>
      <button class="btn btn-outline-success btn-sm btn-publicar">
        <i class="bi bi-check-circle"></i>
      </button>
    </td>
  `;

  asignarEventosFila(tr, genero);
  return tr;
}

// --- ASIGNAR EVENTOS A UNA FILA ---
function asignarEventosFila(tr, genero) {
  tr.querySelector('.btn-eliminar').addEventListener('click', () => {
    tr.remove();
    if (filaEditando === tr) filaEditando = null;
  });

  tr.querySelector('.btn-editar').addEventListener('click', () => {
    formGenero.classList.remove('d-none');
    filaEditando = tr;
    nombreGenero.value = tr.querySelector('td:nth-child(2)').textContent;
  });

  tr.querySelector('.btn-publicar').addEventListener('click', () => {
    tr.querySelector('td:nth-child(2)').classList.toggle('fw-bold');
  });
}

// --- INICIALIZAR FILAS EXISTENTES ---
Array.from(tablaGeneros.children).forEach(tr => {
  asignarEventosFila(tr, {nombre: tr.querySelector('td:nth-child(2)').textContent});
});

// --- EVENTOS ---
btnAgregarGenero.addEventListener('click', () => {
  formGenero.classList.toggle('d-none');
  formGenero.reset();
  filaEditando = null;
});

formGenero.addEventListener('submit', (e) => {
  e.preventDefault();
  
  if (filaEditando) {
    // Editar fila existente
    filaEditando.querySelector('td:nth-child(2)').textContent = nombreGenero.value;
  } else {
    // Agregar nuevo género
    const nuevoGenero = {id: nextId++, nombre: nombreGenero.value};
    const fila = crearFilaGenero(nuevoGenero);
    tablaGeneros.appendChild(fila);
  }

  formGenero.reset();
  formGenero.classList.add('d-none');
  filaEditando = null;
});

cancelarGenero.addEventListener('click', () => {
  formGenero.reset();
  formGenero.classList.add('d-none');
  filaEditando = null;
});
