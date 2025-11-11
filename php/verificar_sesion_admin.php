<?php
// Iniciamos la sesión
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] == 'cliente') {
    
    header("Location: /veterinaria-f-f/Login.php");
    exit(); 
}

// Guardamos los datos de la sesión en variables
$id_personal_logueado = $_SESSION['user_id'];
$nombre_personal_logueado = $_SESSION['user_nombre'];
$rol_personal_logueado = $_SESSION['rol'];
?>