<?php
include 'conexiones.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $rut = mysqli_real_escape_string($conexion, $_POST['rut']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password = $_POST['password'];

    $password_encriptada = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO propietarios (nombre, rut, correo, numero, usuario, password) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $nombre, $rut, $correo, $telefono, $usuario, $password_encriptada);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
                alert('¡Usuario creado exitosamente!');
                window.location.href = '/veterinaria-f-f/Login.php'; /* <-- CAMBIO AQUÍ */
              </script>";
    } else {
        echo "<script>
                alert('Error al crear el usuario. Es posible que el correo o usuario ya exista.');
                window.location.href = '/veterinaria-f-f/crear_usuario.php'; /* <-- CAMBIO AQUÍ */
              </script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}
?>