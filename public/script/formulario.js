document.getElementById("btn__registrarse").addEventListener("click", registro);
document.getElementById("btn__iniciar-sesion").addEventListener("click", iniciar_sesion);

window.addEventListener("resize", ancho_pagina);

// Variables
var contenedor = document.querySelector(".contenedor__login-register");
var formulario_login = document.querySelector(".formulario__login");
var formulario_register = document.querySelector(".formulario__register");

var caja_trasera_login = document.querySelector(".caja__trasera-login");
var caja_trasera_register = document.querySelector(".caja__trasera-register");

function iniciar_sesion() {
    if (window.innerWidth > 850) {
        formulario_register.style.display = "none";
        contenedor.style.left = "10px";
        formulario_login.style.display = "block";
        caja_trasera_register.style.display = "block";
        caja_trasera_login.style.display = "none";
    } else {
        formulario_register.style.display = "none";
        contenedor.style.left = "0px";
        formulario_login.style.display = "block";
        caja_trasera_register.style.opacity = "1";
        caja_trasera_login.style.opacity = "0";
    }
}

function registro() {
    if (window.innerWidth > 850) {
        formulario_register.style.display = "block";
        contenedor.style.left = "410px";
        formulario_login.style.display = "none";
        caja_trasera_register.style.opacity = "0";
        caja_trasera_login.style.opacity = "1";
    } else {
        formulario_register.style.display = "block";
        contenedor.style.left = "0px";
        formulario_login.style.display = "none";
        caja_trasera_register.style.display = "none";
        caja_trasera_login.style.display = "block";
        caja_trasera_login.style.opacity = "1";
    }
}

function ancho_pagina() {
    if (window.innerWidth > 850) {
        caja_trasera_login.style.display = "block";
        caja_trasera_register.style.display = "block";
    } else {
        caja_trasera_register.style.display = "block";
        caja_trasera_register.style.opacity = "1";
        caja_trasera_login.style.display = "none";
        formulario_login.style.display = "block";
        formulario_register.style.display = "none";
        contenedor.style.left = "0px";
    }
}

ancho_pagina();

history.pushState(null, "", location.href);
window.onpopstate = function () {
    history.pushState(null, "", location.href);
};