<?php

// 1. Definimos los 4 parámetros de conexión
$servidor = "localhost";\
$usuario = "root";\
$password = "";\
$nombre_bd = "veterinaria_db";\

// 2. Creamos la conexión usando mysqli_connect
$conexion = mysqli_connect($servidor, $usuario, $password, $nombre_bd);

// 3. Verificamos si la conexión falló
if (!$conexion) {
    // Si falla, detenemos la página y mostramos el error
    die("Error de conexión: " . mysqli_connect_error());
}

// Si llegamos aquí, la conexión fue exitosa.
// (Opcional: podemos establecer el set de caracteres a UTF-8 para evitar problemas con tildes)
mysqli_set_charset($conexion, "utf8");

?>