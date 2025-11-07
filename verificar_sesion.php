<?php
// Iniciamos la sesión en CADA página que queramos proteger
session_start();

// Verificamos si la variable de sesión 'user_id' NO existe
// O si el 'rol' no es el que esperamos (en este caso, 'cliente')
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'cliente') {
    
    // Si no ha iniciado sesión o no es un cliente,
    // lo "pateamos" de vuelta a la página de login.
    header("Location: login.php");
    exit(); // Detenemos la ejecución de la página
}

// Si el script llega hasta aquí, significa que el usuario SÍ está logueado
// y es un cliente. Podemos guardar sus datos en variables
// para usarlas más fácil en el HTML.
$id_usuario_logueado = $_SESSION['user_id'];
$nombre_usuario_logueado = $_SESSION['user_nombre'];
?>