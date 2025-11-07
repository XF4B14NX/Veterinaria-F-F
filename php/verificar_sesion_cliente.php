<?php
// Iniciamos la sesión en CADA página que queramos proteger
session_start();

// Verificamos si la variable de sesión 'user_id' NO existe
// O si el 'rol' no es 'cliente'
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'cliente') {
    
    // Si no ha iniciado sesión o no es un cliente,
    // lo enviamos de vuelta a la página de login.
    // ¡CAMBIO CRÍTICO! La ruta correcta es ../login.php
    header("Location: ../login.php");
    exit(); // Detenemos la ejecución de la página
}

// Guardamos los datos de la sesión en variables para usarlas fácil
$id_usuario_logueado = $_SESSION['user_id'];
$nombre_usuario_logueado = $_SESSION['user_nombre'];
?>