<?php
require 'app.php';

//Declaro una funcion para incluir los templates y declaro inicio como false para que en caso de que este no se le pase un valor, por defecto sea false
// y no se le pase un valor por defecto a la variable $inicio, ya que si no se le pasa un valor por defecto, la variable $inicio no existe y da error
function incluirTemplate( $nombre, $inicio = false ) {
    include TEMPLATES_URL. "/{$nombre}.php";
}

function estaAutenticado (): bool {
    session_start();
    $auth = $_SESSION['login'];
    if($auth) {
        return true;
    } 
    return false;
    
}