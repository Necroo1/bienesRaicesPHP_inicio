<?php
    require 'includes/config/database.php';
    $db = conectarDB();

    $errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar el email y password
    $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (!$email) {
        $errores[] = "El email es obligatorio o no es válido";
    }
    if (!$password) {
        $errores[] = "El password es obligatorio";
    }

    if (empty($errores)) {
        $query = "SELECT u.*, r.name AS role_name 
                  FROM users AS u 
                  JOIN roles AS r ON u.role_id = r.id 
                  WHERE u.email = '${email}'";
        
        $resultado = mysqli_query($db, $query);

        if ($resultado->num_rows) {
            $usuario = mysqli_fetch_assoc($resultado);

            $auth = password_verify($password, $usuario['password']);

            if ($auth) {
                session_start();
                
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['login'] = true;
                $_SESSION['role_name'] = $usuario['role_name']; 

                switch ($_SESSION['role_name']) {
                    case 'administrador':
                        header('Location: /admin/dashboard'); 
                        break;
                    case 'empleado':
                        header('Location: /empleado/dashboard'); 
                        break;
                    case 'usuario':
                        header('Location: /usuario/perfil'); 
                        break;
                    default:
                        header('Location: /'); 
                        break;
                }
                exit; 

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
    ?>