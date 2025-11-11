<?php
// Iniciamos la sesión
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'cliente') {
    
    // ¡CAMBIO AQUÍ! Ruta absoluta
    header("Location: /veterinaria-f-f/Login.php");
    exit(); 
}

// Guardamos los datos de la sesión en variables
$id_usuario_logueado = $_SESSION['user_id'];
$nombre_usuario_logueado = $_SESSION['user_nombre'];
?>