<?php
require __DIR__ . '/../includes/funciones.php';
$auth = estaAutenticado();
if (!$auth) {
    header('Location: /');
}

//base de datos
require '../../includes/config/database.php';
$db = conectarDB(); 

//consulta para vendedores
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

// arrays con mensaje de errores
$errores = [];

// variables para almacenar los datos del formulario
    $titulo ='';
    $precio = '';
    $descripcion ='';
    $habitaciones = '';
    $wc = '';
    $estacionamiento = '';
    $id_vendedores= '';

// ejecuta el codigo despues de que el usuario envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario
    $titulo = mysqli_real_escape_string($db,$_POST['titulo']);
    $precio = mysqli_real_escape_string($db, $_POST['precio']);
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
    $habitaciones = mysqli_real_escape_string($db, $_POST['habitaciones']);
    $wc = mysqli_real_escape_string($db, $_POST['wc']);
    $estacionamiento = mysqli_real_escape_string($db, $_POST['estacionamiento']);
    $id_vendedores = mysqli_real_escape_string($db, $_POST['vendedor']);
    $creado = date('Y/m/d');

    // Asignar files a una variable
    $imagen = $_FILES['imagen'];


    // Validar que no haya campos vacíos
    if(!$titulo) {
        $errores[] = "Debes añadir un título";
    }
    if(!$precio) {
        $errores[] = "El precio es obligatorio";
    } 
    if(strlen($descripcion) < 50) {
        $errores[] = "La descripción es obligatoria y debe tener al menos 50 caracteres";
    }
    if(!$habitaciones) {
        $errores[] = "El número de habitaciones es obligatorio";
    }
    if(!$wc) {
        $errores[] = "El número de baños es obligatorio";
    }
    if(!$estacionamiento) {
        $errores[] = "El número de estacionamientos es obligatorio";
    }
    if(!$id_vendedores) {
        $errores[] = "Elige un vendedor";
    }
    //validacion para imagen
    if(!$imagen['name'] || $imagen['error']) {
        $errores[] = "La imagen es obligatoria";
    }
    //validar el tamaño de la imagen
    $medida = 40 * 1000 * 1000; // 40mb
    if($imagen['size']> $medida) {
        $errores[] = "La imagen es muy pesada";
    }

    // revisar que el array de errores esté vacío
    if(empty($errores)) {
        //subir la imagen
        // Crear una carpeta para subir las imagenes
        $carpetaImagenes = '../../imagenes/';
        if(!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }
        // Crear un nombre único para la imagen
        $nombreImagen = md5(uniqid(rand(), true)). ".jpg";
        //la imagen en memoria se mueve a la carpeta de imagenes
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);

        // Insertar en la base de datos
        $query = "INSERT INTO propiedades (titulo, precio, imagen,descripcion, habitaciones, wc, estacionamiento, creado ,id_vendedores) 
        VALUES ('$titulo', '$precio','$nombreImagen','$descripcion', '$habitaciones', '$wc', '$estacionamiento','$creado', '$id_vendedores')";
        // Ejecutar la query
        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            // Redireccionar al usuario
            header('Location: /admin?resultado=1');
        } 
     }
}
incluirTemplate('header');
// Header
?>
    <main class="contenedor seccion">
        <h1>Crear</h1>
        <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
        <?php endforeach;?> 
        <a href="/admin" class="boton boton-verde">Volver</a>
        <!-- enctype es para subida de archivos en un formulariio -->
        <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Información General</legend>
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad" value="<?php echo $titulo; ?>">
            <label for="precio">Precio</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">
            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" placeholder="Descripción Propiedad"><?php echo $descripcion; ?></textarea>
        </fieldset>
        <fieldset>
            <legend>Informacion Propiedad</legend>
            <label for="habitaciones">Habitaciones</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones; ?>">
            <label for="wc">Baños</label>
            <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc; ?>">
            <label for="estacionamiento">Estacionamiento</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento; ?>">
        </fieldset>
        <fieldset>
            <legend>Vendedor</legend>
            <select name="vendedor">
                <option value="">-- Seleccione --</option>
                //operador tenario sirve para validar si el id del vendedor es igual al id de la base de datos
                //si es igual se selecciona el vendedor, si no se deja vacio
                <?php while($vendedor = mysqli_fetch_assoc($resultado)): ?>
                <option <?php echo $id_vendedores === $vendedor['id'] ? 
                'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>">
                <?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?>
            </option>

                <?php endwhile; ?>
            </select>
        </fieldset>
        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </form>
    </main>
    <?php 
    incluirTemplate('footer');
    // Footerss
    ?>