<?php
session_start();
$_SESSION = array();
session_destroy();

// ¡CAMBIO AQUÍ! Ruta absoluta
header("Location: /veterinaria-f-f/Login.php");
exit();
?>