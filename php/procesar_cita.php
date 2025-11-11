<?php
// 1. Incluimos guardia y conexión
include 'verificar_sesion_cliente.php';
include 'conexiones.php';

// 2. Verificamos la "acción" que se quiere realizar (desde la URL)
if (isset($_GET['accion'])) {

    // ACCIÓN: AGENDAR (RF-001)
    if ($_GET['accion'] == 'agendar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // 3. Obtenemos los datos del formulario de agendar_cita.php
        $mascota_id = $_POST['mascota_id'];
        $servicio_id = $_POST['servicio_id'];
        
        // --- CAMBIO AQUÍ ---
        // Recibimos fecha y hora por separado
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        
        // Verificamos que la hora no esté vacía (por si el JS falla)
        if (empty($fecha) || empty($hora)) {
             echo "<script>
                    alert('Error: Debes seleccionar una fecha y una hora válidas.');
                    window.location.href = '../agendar_cita.php';
                  </script>";
             exit();
        }
        
        // Los unimos en un formato DATETIME para la BD (ej: "2025-11-12 10:30:00")
        $fecha_hora = $fecha . ' ' . $hora . ':00';
        // --- FIN DEL CAMBIO ---
        
        
        // 4. Verificamos que la mascota le pertenezca al usuario logueado
        $sql_verificar = "SELECT propietario_id FROM mascotas WHERE mascota_id = ? AND propietario_id = ?";
        $stmt_verificar = mysqli_prepare($conexion, $sql_verificar);
        mysqli_stmt_bind_param($stmt_verificar, "ii", $mascota_id, $id_usuario_logueado);
        mysqli_stmt_execute($stmt_verificar);
        $resultado_verificar = mysqli_stmt_get_result($stmt_verificar);

        if (mysqli_num_rows($resultado_verificar) == 1) {
            // 5. La mascota es del usuario. Procedemos a insertar la cita.
            $sql_insertar = "INSERT INTO citas (mascota_id, servicio_id, fecha_hora, estado) 
                             VALUES (?, ?, ?, 'Programada')";
            $stmt_insertar = mysqli_prepare($conexion, $sql_insertar);
            mysqli_stmt_bind_param($stmt_insertar, "iis", $mascota_id, $servicio_id, $fecha_hora);
            
            if (mysqli_stmt_execute($stmt_insertar)) {
                echo "<script>
                        alert('¡Cita agendada exitosamente!');
                        window.location.href = '../perfil_cliente.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Error al agendar la cita. Es posible que esa hora ya no esté disponible.');
                        window.location.href = '../agendar_cita.php';
                      </script>";
            }
            mysqli_stmt_close($stmt_insertar);
        } else {
            // 6. Error de seguridad: Intento de agendar con mascota ajena.
            echo "<script>
                    alert('Error: No tienes permisos sobre esa mascota.');
                    window.location.href = '../perfil_cliente.php';
                  </script>";
        }
        mysqli_stmt_close($stmt_verificar);
    }

    // ACCIÓN: CANCELAR (RF-006)
    if ($_GET['accion'] == 'cancelar' && isset($_GET['id'])) {
        
        $cita_id = $_GET['id'];

        // (Esta parte de cancelar no necesita cambios)
        $sql_verificar = "SELECT c.cita_id FROM citas c
                          JOIN mascotas m ON c.mascota_id = m.mascota_id
                          WHERE c.cita_id = ? AND m.propietario_id = ?";
        
        $stmt_verificar = mysqli_prepare($conexion, $sql_verificar);
        mysqli_stmt_bind_param($stmt_verificar, "ii", $cita_id, $id_usuario_logueado);
        mysqli_stmt_execute($stmt_verificar);
        $resultado_verificar = mysqli_stmt_get_result($stmt_verificar);

        if (mysqli_num_rows($resultado_verificar) == 1) {
            $sql_cancelar = "UPDATE citas SET estado = 'Cancelada' WHERE cita_id = ?";
            $stmt_cancelar = mysqli_prepare($conexion, $sql_cancelar);
            mysqli_stmt_bind_param($stmt_cancelar, "i", $cita_id);
            
            if (mysqli_stmt_execute($stmt_cancelar)) {
                echo "<script>
                        alert('Cita cancelada exitosamente.');
                        window.location.href = '../perfil_cliente.php';
                      </script>";
            } else {
                 echo "<script>
                        alert('Error al cancelar la cita.');
                        window.location.href = '../perfil_cliente.php';
                      </script>";
            }
            mysqli_stmt_close($stmt_cancelar);
        } else {
            echo "<script>
                    alert('Error: No tienes permisos sobre esta cita.');
                    window.location.href = '../perfil_cliente.php';
                  </script>";
        }
        mysqli_stmt_close($stmt_verificar);
    }

} else {
    header("Location: ../perfil_cliente.php");
    exit();
}

mysqli_close($conexion);
?>