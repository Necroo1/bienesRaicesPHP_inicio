<?php

//¿No hay sesion iniciada? Inicia sesión Casper
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Vamos a chequear si está logueado
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // Si no está logueado,hay que mandar al login o a alguna pagina para mostrar un error.
    header('Location: /logueate?carajo');
    exit(); 
}

// 2. Verificar el rol del usuario
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'administrador') {
    // Si el rol no es 'administrador', mandalo pa su casa
    header('Location: /hasta_aca_llegaste_milei'); 
    exit(); 
}

// Si llega hasta acá, significa que está todo liso.

?>