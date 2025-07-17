<?php
 $id = $_GET['id'];
    if(!filter_var($id, FILTER_VALIDATE_INT)) {
        header('Location: /');
    }
 // IMPORTAR LA BASE DE DATOS
 require 'includes/config/database.php'; 
 $db = conectarDB();
 // CONSULTA A LA BASE DE DATOS  
 $query = "SELECT * FROM propiedades WHERE id = ${id}";

 // OBTENER RESULTADOS
 $resultado = mysqli_query($db, $query);
 if(!$resultado->num_rows ) {
    header('Location: /');
}
 $propiedad = mysqli_fetch_assoc($resultado);
require 'includes/funciones.php';
incluirTemplate('header');
// Header
?>

    <main class="contenedor seccion contenido-centrado">
        <h1><?php  echo $propiedad['titulo']; ?></h1>
            <img loading="lazy" src="/imagenes/<?php  echo $propiedad ['imagen']; ?>" alt="imagen de la propiedad">


        <div class="resumen-propiedad">
            <p class="precio"><?php  echo $propiedad ['precio']; ?></p>
            <ul class="iconos-caracteristicas">
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                    <p><?php  echo $propiedad['wc']; ?></p>
                </li>
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                    <p><?php  echo $propiedad['estacionamiento']; ?></p>
                </li>
                <li>
                    <img class="icono"  loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono habitaciones">
                    <p><?php  echo $propiedad['habitaciones']; ?></p>
                </li>
            </ul>
                <?php  echo $propiedad['descripcion']; ?>
        </div>
    </main>
    <?php 
    //cerrar conexion
    mysqli_close($db);
    incluirTemplate('footer');
    // Footerss
    ?>