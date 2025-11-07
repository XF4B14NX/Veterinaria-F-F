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
        $fecha_hora = $_POST['fecha_hora'];
        
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
                        alert('Error al agendar la cita. Inténtelo de nuevo.');
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

        // 3.Verificamos que la cita le pertenezca al usuario logueado
        // (Verificamos que el 'propietario_id' de la mascota de la cita sea el del usuario)
        $sql_verificar = "SELECT c.cita_id FROM citas c
                          JOIN mascotas m ON c.mascota_id = m.mascota_id
                          WHERE c.cita_id = ? AND m.propietario_id = ?";
        
        $stmt_verificar = mysqli_prepare($conexion, $sql_verificar);
        mysqli_stmt_bind_param($stmt_verificar, "ii", $cita_id, $id_usuario_logueado);
        mysqli_stmt_execute($stmt_verificar);
        $resultado_verificar = mysqli_stmt_get_result($stmt_verificar);

        if (mysqli_num_rows($resultado_verificar) == 1) {
            // 4. La cita es del usuario. Procedemos a "cancelar" (actualizar estado)
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
            // 5. Error de seguridad: Intento de cancelar cita ajena.
            echo "<script>
                    alert('Error: No tienes permisos sobre esta cita.');
                    window.location.href = '../perfil_cliente.php';
                  </script>";
        }
        mysqli_stmt_close($stmt_verificar);
    }

} else {
    // Si alguien entra al script sin una acción, lo echamos.
    header("Location: ../perfil_cliente.php");
    exit();
}

mysqli_close($conexion);
?>