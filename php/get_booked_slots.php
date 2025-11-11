<?php
// 1. Incluimos guardia y conexión
include 'verificar_sesion_cliente.php';
include 'conexiones.php';

// 2. Preparamos un array para guardar las horas ocupadas
$booked_slots = [];

// 3. Verificamos que nos hayan pasado una fecha
if (isset($_GET['date'])) {
    $selected_date = $_GET['date'];

    // 4. Buscamos en la BD las citas para ESE día que estén 'Programada'
    // Usamos DATE() para ignorar la parte de la hora en la comparación
    $sql = "SELECT fecha_hora FROM citas WHERE DATE(fecha_hora) = ? AND estado = 'Programada'";
    
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $selected_date);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // 5. Guardamos solo la HORA y MINUTO (ej: "09:30", "14:00")
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $booked_slots[] = date('H:i', strtotime($fila['fecha_hora']));
    }
    mysqli_stmt_close($stmt);
}

// 6. Devolvemos la lista de horas ocupadas como un JSON
mysqli_close($conexion);
header('Content-Type: application/json');
echo json_encode($booked_slots);
exit();
?>