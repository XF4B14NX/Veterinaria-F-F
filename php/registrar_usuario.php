<?php
// 1. Incluimos el "corazón" de nuestro backend: la conexión
include 'conexiones.php';

/*
 * 2. Verificamos que los datos se hayan enviado por POST
 * (method="POST" es la forma correcta de enviar formularios)
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Obtenemos los datos del formulario (de crear_usuario.php)
    // Usamos 'mysqli_real_escape_string' por seguridad básica
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $rut = mysqli_real_escape_string($conexion, $_POST['rut']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password = $_POST['password']; // No escapamos la contraseña todavía

    // 4. ¡CRÍTICO! Encriptar la contraseña (JAMÁS guardes contraseñas en texto plano)
    // Esto cumple con la Factibilidad Legal
    $password_encriptada = password_hash($password, PASSWORD_DEFAULT);

    // 5. Preparamos la consulta SQL (usando la tabla 'propietarios' de tu Modelo)
    // Esto es un "Prepared Statement", la forma más segura de evitar inyecciones SQL
    $sql = "INSERT INTO propietarios (nombre, rut, correo, numero, usuario, password) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conexion, $sql);

    // 6. Vinculamos los datos a la consulta
    // Las "ssssss" significan que estamos enviando 6 variables de tipo String (texto)
    mysqli_stmt_bind_param($stmt, "ssssss", $nombre, $rut, $correo, $telefono, $usuario, $password_encriptada);

    // 7. Ejecutamos la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Si fue exitoso, avisamos y redirigimos al Login
        echo "<script>
                alert('¡Usuario creado exitosamente!');
                window.location.href = 'login.php';
              </script>";
    } else {
        // Si falló (ej. el correo o usuario ya existe, si los configuras como UNIQUE)
        echo "<script>
                alert('Error al crear el usuario. Es posible que el correo o usuario ya exista.');
                window.location.href = 'crear_usuario.php';
              </script>";
    }

    // 8. Cerramos la conexión
    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}
?>