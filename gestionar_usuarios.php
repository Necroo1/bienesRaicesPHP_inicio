<?php
// Acá le mandas el archivo que vimos recien, el filter_access
require 'includes/verificar_admin.php'; 

//Acá no deberías poder llegar si no sos admin, si intentas acceder a esta página sin ser admin, te va a redirigir al login o a una página de error 
//como dice el filter_access.php
?>
    <main class="contenedor seccion">
        <h1>Panel de Administración</h1>
        <p>Kease, <?php echo $_SESSION['usuario']; ?>. Vos sos <?php echo $_SESSION['role_name']; ?>?</p>
        <a href="/admin/crear_propiedad.php" class="boton boton-verde">Crear Nueva Propiedad</a>
        <a href="/admin/gestionar_usuarios.php" class="boton boton-azul">Gestionar Usuarios</a>
    </main>

<?php
incluirTemplate('footer');
?>