<?php 
//importar la conexión 
require 'includes/config/database.php';
$db = conectarDB();


//mail y password
$email = "correo@correo.com";
$password = "123456";
$passwordHash = password_hash($password, PASSWORD_BCRYPT);
//query
$query = "insert into usuarios (email, password) values ('${email}', '${passwordHash}')";
// echo $query;

//agregar a la base de datos
mysqli_query($db, $query);