<?php
    // Conectar a la base de datos
    require 'includes/config/database.php';
    $db = conectarDB();

    //Autenticar el usuario
    $errores = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar el email y password
    $email = mysqli_real_escape_string($db,  filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) );
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Consultar si el usuario existe
    $query = "SELECT * FROM usuarios WHERE email = '${email}'";
    $resultado = mysqli_query($db, $query);
    if (!$email){
        $errores[] = "El email es obligatorio o no es valido";
    }
    if (!$password){
        $errores[] = "El password es obligatorio";
    }
    if (empty($errores)) {
        // Verificar si el usuario existe
        $query = "SELECT * FROM usuarios WHERE email = '${email}'";
        $resultado = mysqli_query($db, $query);
        if($resultado->num_rows) {
            $usuario = mysqli_fetch_assoc($resultado);
            // Verificar si el password es correcto
            $auth = password_verify($password, $usuario['password']);
            if ($auth) {
                // Iniciar la sesión
                session_start();
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['login'] = true;
                header('Location:/admin');
            } else {
                $errores[] = "El password es incorrecto";
            }
        } else {
            $errores[] = "El usuario no existe";

        }
    
    }


 }
// Header
require 'includes/funciones.php';
incluirTemplate('header');

?>
    <main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesion</h1>
        <?php foreach ($errores as $error) : ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>
        <form method="POST" class="formulario">
            <fieldset>
                <legend>Email y Password</legend>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Tu Email">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Tu Password">
                <input type="submit" value="Iniciar Sesion" class="boton boton-verde">
            </fieldset>
        </form>
    </main>
    <?php 
    incluirTemplate('footer');
    // Footerss
    ?>