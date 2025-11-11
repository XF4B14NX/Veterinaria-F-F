<?php
// 1. Incluimos el guardián de admin y la conexión
include 'verificar_sesion_admin.php';
include 'conexiones.php';

// 2. Verificamos que la acción (agendar o cancelar) venga por la URL
if (!isset($_GET['accion'])) {
    header("Location: ../mant_citas.php");
    exit();
}

$accion = $_GET['accion'];

// 3. LÓGICA PARA AGENDAR (RF-007)
if ($accion == 'agendar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 3.1. Obtenemos los datos del formulario (de mant_citas.php)
    $mascota_id = $_POST['mascota_id'];
    $servicio_id = $_POST['servicio_id'];
    $fecha_hora = $_POST['fecha_hora'];
    
    // 3.2. Preparamos la consulta para insertar la cita
    // Nota: Como es el admin, no necesitamos verificar si la mascota
    // le pertenece, se asume que el recepcionista sabe lo que hace.
    $sql_insertar = "INSERT INTO citas (mascota_id, servicio_id, fecha_hora, estado) 
                     VALUES (?, ?, ?, 'Programada')";
    $stmt_insertar = mysqli_prepare($conexion, $sql_insertar);
    mysqli_stmt_bind_param($stmt_insertar, "iis", $mascota_id, $servicio_id, $fecha_hora);
    
    // 3.3. Ejecutamos y redirigimos
    if (mysqli_stmt_execute($stmt_insertar)) {
        echo "<script>
                alert('¡Cita agendada exitosamente por el personal!');
                window.location.href = '../mant_citas.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al agendar la cita.');
                window.location.href = '../mant_citas.php';
              </script>";
    }
    mysqli_stmt_close($stmt_insertar);
}

// 4. LÓGICA PARA CANCELAR (RF-007)
else if ($accion == 'cancelar' && isset($_GET['id'])) {
    
    $cita_id = $_GET['id'];

    // 4.1. Preparamos la consulta para "cancelar" (actualizar estado)
    // Como es admin, puede cancelar cualquier cita.
    $sql_cancelar = "UPDATE citas SET estado = 'Cancelada' WHERE cita_id = ?";
    $stmt_cancelar = mysqli_prepare($conexion, $sql_cancelar);
    mysqli_stmt_bind_param($stmt_cancelar, "i", $cita_id);
    
    // 4.2. Ejecutamos y redirigimos
    if (mysqli_stmt_execute($stmt_cancelar)) {
        echo "<script>
                alert('Cita cancelada exitosamente por el personal.');
                window.location.href = '../mant_citas.php';
              </script>";
    } else {
         echo "<script>
                alert('Error al cancelar la cita.');
                window.location.href = '../mant_citas.php';
              </script>";
    }
    mysqli_stmt_close($stmt_cancelar);
}

// 5. Si la acción no es válida, redirigimos
else {
    header("Location: ../mant_citas.php");
    exit();
}

mysqli_close($conexion);
?>