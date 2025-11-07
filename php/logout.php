<?php
// 1. Iniciar la sesión
session_start();

// 2. Destruir todas las variables de sesión
$_SESSION = array();

// 3. Destruir la sesión
session_destroy();

// 4. Redirigir al usuario a la página de login
header("Location: login.php");
exit();
?>