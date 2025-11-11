<?php
// 1. Incluimos guardia y conexión
include 'verificar_sesion_cliente.php';
include 'conexiones.php';

// 2. Verificamos que los datos se envíen por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Obtenemos los datos del formulario (RF-002)
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    
    // 4. Preparamos la consulta para ACTUALIZAR los datos del usuario logueado
    $sql = "UPDATE propietarios SET correo = ?, numero = ? WHERE propietario_id = ?";
    
    $stmt = mysqli_prepare($conexion, $sql);
    
    // 5. Vinculamos los datos (string, string, integer)
    mysqli_stmt_bind_param($stmt, "ssi", $correo, $telefono, $id_usuario_logueado);

    // 6. Ejecutamos la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Si fue exitoso, avisamos y redirigimos de vuelta al Perfil
        echo "<script>
                alert('¡Información actualizada exitosamente!');
                window.location.href = '../perfil_cliente.php';
              </script>";
    } else {
        // Si falló
        echo "<script>
                alert('Error al actualizar la información.');
                window.location.href = '../perfil_cliente.php';
              </script>";
    }

    // 7. Cerramos todo
    mysqli_stmt_close($stmt);
    mysqli_close($conexion);

} else {
    // Si alguien entra al script sin usar POST, lo echamos.
    header("Location: ../perfil_cliente.php");
    exit();
}
?>