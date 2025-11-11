<?php
/*
Paso 1: Escribe la contraseña que QUIERES usar.
(Puedes cambiar '1234' por 'admin' si quieres probar con ese usuario)
*/
$mi_contraseña = '1234'; 

/* Paso 2: Generamos el hash.
*/
$hash_correcto = password_hash($mi_contraseña, PASSWORD_DEFAULT);

/* Paso 3: Mostramos el hash en pantalla.
*/
echo "El hash correcto para la contraseña <b>'".$mi_contraseña."'</b> es: <br><br>";
echo "<hr>";
echo "<b>" . $hash_correcto . "</b>";
echo "<hr>";
echo "<br>COPIA la línea de arriba (la que empieza con $2y$10$) y pégala en la columna 'password' de tu usuario en phpMyAdmin.";
?>