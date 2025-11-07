<?php
// Iniciamos la sesión
session_start();

// Verificamos si la variable de sesión 'user_id' NO existe
// O si el 'rol' ES 'cliente' (un cliente no puede entrar al admin)
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] == 'cliente') {
    
    // Si no ha iniciado sesión o es un cliente,
    // lo enviamos de vuelta a la página de login.
    header("Location: login.php");
    exit(); // Detenemos la ejecución de la página
}

// Guardamos los datos de la sesión en variables
$id_personal_logueado = $_SESSION['user_id'];
$nombre_personal_logueado = $_SESSION['user_nombre'];
$rol_personal_logueado = $_SESSION['rol']; // Ej. "Administrador"
?>