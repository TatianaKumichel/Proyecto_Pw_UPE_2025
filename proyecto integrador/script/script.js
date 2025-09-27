window.onload = function () {
    const menuUsuario = document.getElementById("menuUsuario");
    const perfilIcono = document.querySelector(".perfil-icono");
    const formLogin = document.getElementById("formLogin");
    const formRegistro = document.getElementById("formRegistro");
    const formRecuperar = document.getElementById("formRecuperar");
    const categorias = document.querySelectorAll('.dropdown-item[data-categoria]');

    // esto es un objeto de un estado de usuario logeado o deslogeado
    const estado = {
        usuarioLogeado: false,
        nombreUsuario: "",
        fotoPerfilLogeado: "./imagenes/usuario_logeado.jpg",
        fotoPerfilDeslogeado: "./imagenes/user_gray.png"
    };

    // al entrar a la pagina se muestra el perfil logeado o no acorde
    // a si se logeo o no. Podria tambien mostrarse mas elementos
    // si quien se logea es un admin/moderador...
    actualizarMenu(estado, menuUsuario, perfilIcono);

    // primero se verifica que exista en el DOM el formulario de login
    // o el de registro y entonces se llama al evento de submit
    // para que haga la logica del login o registro
    if (formLogin) {
        formLogin.addEventListener("submit", formLoginSubmit);
    }
    if (formRegistro) {
        formRegistro.addEventListener("submit", formRegistroSubmit);
    }
    // similar al anterior, solo que en este caso no se necesita que
    // haya un cambio entre perfiles (visitante, usuario)
    if (formRecuperar) {
        formRecuperar.addEventListener("submit", formRecuperarClaveSubmit);
    }
    function formLoginSubmit(e){
        manejarLogin(e, estado, menuUsuario, perfilIcono);
    }
    function formRegistroSubmit(e){
        manejarRegistro(e, estado, menuUsuario, perfilIcono);
    }
    function formRecuperarClaveSubmit(e){
        recuperarClave(e);
    }
    // llama a funcion de filtrar juegos por categoria
    // dataset es usado en html5 para guardar informacion
    // en un elemento (atributos de datos)
    categorias.forEach(function(cat) {
        cat.addEventListener('click', function(e) {
            e.preventDefault();
            categoriaClick(this.dataset.categoria);
        });
    });
};

function categoriaClick(e) {
    const categoria = this.dataset.categoria;
    filtrarJuegos(categoria);
}
// funcion para filtrar juegos por categoria
function onCategoriaClick(categoria) {
    const juegos = document.querySelectorAll('.col-12.col-sm-6.col-md-4'); 
    juegos.forEach(function(juego) {
        if (categoria === 'todas' || juego.dataset.categoria === categoria) {
            juego.style.display = 'block';
        } else {
            juego.style.display = 'none';
        }
    });
}

// --- Funcion para actualizar el menu/index segun el usuario ---
// genera un dropdown en el html cuando se hace clic en el icono del usuario
// si esta logeado muestra un dropdown, sino muestra el otro
// el nombre que muestra es el valor de la variable del objeto estado
// especificamente nombreUsuario
function actualizarMenu(estado, menuUsuario, perfilIcono) {
    const cerrarSesionBoton = document.getElementById("cerrarSesion");
    if (estado.usuarioLogeado) {
        // con .src indico que la ruta de la imagen que quiero referenciar
        // es la de la imagen del usuario...
        perfilIcono.src = estado.fotoPerfilLogeado;
        menuUsuario.innerHTML = `
            <li class="dropdown-header"> Hola, ${estado.nombreUsuario}</li>
            <li><a class="dropdown-item" href="cuenta.html">Gestionar cuenta</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><button class="dropdown-item text-danger" id="cerrarSesion">Cerrar sesión</button></li>
        `;
        // logica de cierre de sesion, que pasa de usuario logeado a deslogeado
        if(cerrarSesionBoton){
            cerrarSesionBoton.addEventListener("click", cerrarSesionSubmit);
        }
    } else {
        // con .src indico que la ruta de la imagen que quiero referenciar es
        // la del icono gris
        perfilIcono.src = estado.fotoPerfilDeslogeado;
        menuUsuario.innerHTML = `
            <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar sesión</button></li>
            <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#registroModal">Registrarse</button></li>
        `;
    }
    function cerrarSesionSubmit(){
        cerrarSesion(estado, menuUsuario, perfilIcono);
    }
}

// logica de cierre de sesion, pasa a estado de usuario logeado a deslogeado
function cerrarSesion(estado, menuUsuario, perfilIcono) {
    estado.usuarioLogeado = false;
    estado.nombreUsuario = "";
    actualizarMenu(estado, menuUsuario, perfilIcono);
}

// con esta funcion se hace el pasaje de usuario logeado a deslogeado
function manejarLogin(e, estado, menuUsuario, perfilIcono) {
    e.preventDefault();

    const correo = document.getElementById("correo");
    const correoValue = correo.value.trim();
    const clave = document.getElementById("clave");
    const claveValue = clave.value.trim();
    const loginError = document.getElementById("loginError");

    // se puede usar esto como base para la validacion teniendo una base de datos...
    if (correoValue && claveValue) {
        estado.usuarioLogeado = true;
        // a modo de ejemplo
        // este metodo (split) divide en un arreglo el string del email
        // entonces toma la primera posicion [0] que es lo que se encuentra
        // previo al @
        estado.nombreUsuario = correo.split("@")[0];
        bootstrap.Modal.getInstance(document.getElementById("loginModal")).hide();
        actualizarMenu(estado, menuUsuario, perfilIcono);

        // esto oculta o muestra un mensaje (en un div) y 
        // reinicia (borra) el contenido del formulario de
        // login
        loginError.style.display = "none";
        loginError.textContent = "";
        document.getElementById("formLogin").reset();
    } else {
        loginError.textContent = "Por favor ingrese usuario y contraseña";
        loginError.style.display = "block";
    }
}

// con esta funcion se realiza la logica del formulario de registro

function manejarRegistro(e, estado, menuUsuario, perfilIcono) {
    e.preventDefault();

    const nombre = document.getElementById("registroNombre");
    const correo = document.getElementById("registroCorreo");
    const clave = document.getElementById("registroClave");
    const confirmacion = document.getElementById("registroConfirmacion");

    const nombreValue = nombre.value.trim();
    const correoValue = correo.value.trim();
    const claveValue = clave.value.trim();
    const confirmacionValue = confirmacion.value.trim();
    // nomas acepta letras
    const regexNombre = /^[A-Za-z]+$/;
    // acepta letras, numeros y ciertos simbolos, sin espacios
    const regexClave = /^[\w!@#$%^&*()\-=[\]{};':"\\|,.<>/?]+$/;
    // antes del @ acepta, tras el @ acepta el dominio, numeros, letras, - o ., tras el . acepta al menos 2 letras (.com)
    const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;


    // flags
    let nombreValido = false;
    let correoValido = false;  
    let claveValida = false;
    let confirmacionValida = false;

    // Validación nombre
    if (nombreValue === "" || !regexNombre.test(nombreValue)) {
        nombre.classList.add("is-invalid");
        nombre.classList.remove("is-valid");
        nombreValido = false;
    } else {
        nombre.classList.add("is-valid");
        nombre.classList.remove("is-invalid");
        nombreValido = true;
    }
    // Validación email
    if (correoValue === "" || !regexEmail.test(correoValue)){
        correo.classList.add("is-invalid");
        correo.classList.remove("is-valid");
    } else {
        correo.classList.add("is-valid");
        correo.classList.remove("is-invalid");
        correoValido = true;
    }
    // Validación clave
    if (claveValue === "" || !regexClave.test(claveValue)) {
        clave.classList.add("is-invalid");
        clave.classList.remove("is-valid");
        claveValida = false;
    } else {
        clave.classList.add("is-valid");
        clave.classList.remove("is-invalid");
        claveValida = true;
    }

    // Validación confirmación
    if (confirmacionValue === "" || claveValue !== confirmacionValue) {
        confirmacion.classList.add("is-invalid");
        confirmacion.classList.remove("is-valid");
        confirmacionValida = false;
    } else {
        confirmacion.classList.add("is-valid");
        confirmacion.classList.remove("is-invalid");
        confirmacionValida = true;
    }

    if (nombreValido && correoValido && claveValida && confirmacionValida) {
        estado.usuarioLogeado = true;
        estado.nombreUsuario = nombreValue;
        bootstrap.Modal.getInstance(document.getElementById("registroModal")).hide();
        actualizarMenu(estado, menuUsuario, perfilIcono);
        document.getElementById("formRegistro").reset();
    }
}

  // logica de la recuperacion de contraseña
function recuperarClave(e) {
    e.preventDefault();
    const correo = document.getElementById("recuperarCorreo");
    const correoValue = correo.value.trim();
    const confirmacionModal = new bootstrap.Modal(document.getElementById("confirmacionModal"));
    const mensaje = document.getElementById("confirmacionMensaje");
    let textoMensaje = "";
    if (correoValue) {
        textoMensaje = `Se ha enviado un enlace de recuperación al correo: ${correoValue}`;
    } else {
        textoMensaje = "Por favor ingrese un correo válido";
    }
    // se pone el mensaje en el modal y se oculta
    mensaje.textContent = textoMensaje;
    bootstrap.Modal.getInstance(document.getElementById("recuperarModal")).hide();
    // se muestra el modal mostrando mensaje de confirmacion de recuperacion de contraseña
    confirmacionModal.show();
    // si el email es valido entonces se reinicia el formulario
    if (correoValue) {
        document.getElementById("formRecuperar").reset();
    }
}