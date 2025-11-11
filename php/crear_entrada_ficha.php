<?php
// 1. Incluimos el guardián de admin y la conexión
// ¡Es un admin/personal quien crea la ficha, no el cliente!
include 'verificar_sesion_admin.php';
include 'conexiones.php';

// 2. Verificamos que los datos se envíen por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Obtenemos los datos del formulario (RF-012)
    $mascota_id = $_POST['mascota_id'];
    $fecha = $_POST['fecha'];
    $nombre_procedimiento = mysqli_real_escape_string($conexion, $_POST['nombre_procedimiento']);
    $observaciones = mysqli_real_escape_string($conexion, $_POST['observaciones']);
    
    // Obtenemos el ID de la clínica del personal logueado
    // (Asumimos que el 'personal' tiene un campo 'clinica_id' en la BD)
    // NOTA: si la tabla que creamos'personal' no tiene 'clinica_id',
    // puedes poner un valor fijo, ej: $clinica_id = 1;
    $sql_clinica = "SELECT clinica_id FROM personal WHERE personal_id = ?";
    $stmt_clinica = mysqli_prepare($conexion, $sql_clinica);
    mysqli_stmt_bind_param($stmt_clinica, "i", $id_personal_logueado);
    mysqli_stmt_execute($stmt_clinica);
    $resultado_clinica = mysqli_stmt_get_result($stmt_clinica);
    $clinica_id = mysqli_fetch_assoc($resultado_clinica)['clinica_id'];
    mysqli_stmt_close($stmt_clinica);
    
    // 4. Preparamos la consulta para INSERTAR en 'historial_vacunas'
    // (Según tu BD, esta tabla guarda todo el historial, no solo vacunas)
    $sql = "INSERT INTO historial_vacunas (mascota_id, clinica_id, fecha, nombre_vacuna, observaciones) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conexion, $sql);
    
    // 5. Vinculamos los datos (integer, integer, string, string, string)
    mysqli_stmt_bind_param($stmt, "iisss", $mascota_id, $clinica_id, $fecha, $nombre_procedimiento, $observaciones);

    // 6. Ejecutamos la consulta
    if (mysqli_stmt_execute($stmt)) {
        // ¡Éxito! Avisamos y redirigimos de vuelta a la ficha de la mascota
        echo "<script>
                alert('¡Entrada de ficha clínica guardada!');
                window.location.href = '../mant_fichas_clinicas.php?mascota_id=" . $mascota_id . "';
              </script>";
    } else {
        // Si falló
        echo "<script>
                alert('Error al guardar la entrada.');
                window.location.href = '../mant_fichas_clinicas.php?mascota_id=" . $mascota_id . "';
              </script>";
    }

    // 7. Cerramos todo
    mysqli_stmt_close($stmt);
    mysqli_close($conexion);

} else {
    // Si alguien entra al script sin usar POST, lo echamos.
    header("Location: ../listadoclientes.php");
    exit();
}
?>