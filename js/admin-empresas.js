// --- ELEMENTOS PRINCIPALES ---
const btnAgregarEmpresa = document.getElementById('btnAgregarEmpresa');
const formEmpresa = document.getElementById('formEmpresa');
const cancelarEmpresa = document.getElementById('cancelarEmpresa');
const tablaEmpresas = document.getElementById('tablaEmpresas');
const nombreEmpresa = document.getElementById('nombreEmpresa');

let nextId = tablaEmpresas.children.length + 1;
let filaEditando = null;

// --- FUNCIONES AUXILIARES ---
function crearFilaEmpresa(empresa) {
  const tr = document.createElement('tr');

  tr.innerHTML = `
    <td>${empresa.id}</td>
    <td>${empresa.nombre}</td>
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
    formEmpresa.classList.remove('d-none');
    filaEditando = tr;
    nombreEmpresa.value = tr.querySelector('td:nth-child(2)').textContent;
  });

  tr.querySelector('.btn-publicar').addEventListener('click', () => {
    tr.querySelector('td:nth-child(2)').classList.toggle('fw-bold');
  });
}

// --- INICIALIZAR FILAS EXISTENTES ---
Array.from(tablaEmpresas.children).forEach(tr => {
  asignarEventosFila(tr);
});

// --- EVENTOS ---
btnAgregarEmpresa.addEventListener('click', () => {
  formEmpresa.classList.toggle('d-none');
  formEmpresa.reset();
  filaEditando = null;
});

formEmpresa.addEventListener('submit', (e) => {
  e.preventDefault();

  if (filaEditando) {
    filaEditando.querySelector('td:nth-child(2)').textContent = nombreEmpresa.value;
  } else {
    const nuevaEmpresa = {id: nextId++, nombre: nombreEmpresa.value};
    const fila = crearFilaEmpresa(nuevaEmpresa);
    tablaEmpresas.appendChild(fila);
  }

  formEmpresa.reset();
  formEmpresa.classList.add('d-none');
  filaEditando = null;
});

cancelarEmpresa.addEventListener('click', () => {
  formEmpresa.reset();
  formEmpresa.classList.add('d-none');
  filaEditando = null;
});
