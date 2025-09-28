// --- ELEMENTOS PRINCIPALES ---
const btnAgregarPlataforma = document.getElementById('btnAgregarPlataforma');
const formPlataforma = document.getElementById('formPlataforma');
const cancelarPlataforma = document.getElementById('cancelarPlataforma');
const tablaPlataformas = document.getElementById('tablaPlataformas');
const nombrePlataforma = document.getElementById('nombrePlataforma');

let nextId = tablaPlataformas.children.length + 1;
let filaEditando = null;

// --- FUNCIONES AUXILIARES ---
function crearFilaPlataforma(plataforma) {
  const tr = document.createElement('tr');

  tr.innerHTML = `
    <td>${plataforma.id}</td>
    <td>${plataforma.nombre}</td>
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

  asignarEventosFila(tr);
  return tr;
}

// --- ASIGNAR EVENTOS A UNA FILA ---
function asignarEventosFila(tr) {
  tr.querySelector('.btn-eliminar').addEventListener('click', () => {
    tr.remove();
    if (filaEditando === tr) filaEditando = null;
  });

  tr.querySelector('.btn-editar').addEventListener('click', () => {
    formPlataforma.classList.remove('d-none');
    filaEditando = tr;
    nombrePlataforma.value = tr.querySelector('td:nth-child(2)').textContent;
  });

  tr.querySelector('.btn-publicar').addEventListener('click', () => {
    tr.querySelector('td:nth-child(2)').classList.toggle('fw-bold');
  });
}

// --- INICIALIZAR FILAS EXISTENTES ---
Array.from(tablaPlataformas.children).forEach(tr => {
  asignarEventosFila(tr);
});

// --- EVENTOS ---
btnAgregarPlataforma.addEventListener('click', () => {
  formPlataforma.classList.toggle('d-none');
  formPlataforma.reset();
  filaEditando = null;
});

formPlataforma.addEventListener('submit', (e) => {
  e.preventDefault();

  if (filaEditando) {
    // Editar fila existente
    filaEditando.querySelector('td:nth-child(2)').textContent = nombrePlataforma.value;
  } else {
    // Agregar nueva plataforma
    const nuevaPlataforma = {id: nextId++, nombre: nombrePlataforma.value};
    const fila = crearFilaPlataforma(nuevaPlataforma);
    tablaPlataformas.appendChild(fila);
  }

  formPlataforma.reset();
  formPlataforma.classList.add('d-none');
  filaEditando = null;
});

cancelarPlataforma.addEventListener('click', () => {
  formPlataforma.reset();
  formPlataforma.classList.add('d-none');
  filaEditando = null;
});
