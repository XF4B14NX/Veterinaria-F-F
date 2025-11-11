<?php
// 1. ¡Incluimos al "guardia"!
include 'php/verificar_sesion_cliente.php';

// 2. Incluimos la conexión a la BD
include 'php/conexiones.php';

// 3. Pre-cargamos las mascotas y servicios
$mascotas = [];
$servicios = [];

// Cargar Mascotas del usuario (RF-003)
$sql_mascotas = "SELECT mascota_id, nombre, especie FROM mascotas WHERE propietario_id = ?";
$stmt_mascotas = mysqli_prepare($conexion, $sql_mascotas);
mysqli_stmt_bind_param($stmt_mascotas, "i", $id_usuario_logueado);
mysqli_stmt_execute($stmt_mascotas);
$resultado_mascotas = mysqli_stmt_get_result($stmt_mascotas);
while ($fila = mysqli_fetch_assoc($resultado_mascotas)) {
    $mascotas[] = $fila;
}
mysqli_stmt_close($stmt_mascotas);

// Cargar Servicios (RF-014)
$sql_servicios = "SELECT servicio_id, nombre, precio FROM servicios";
$resultado_servicios = mysqli_query($conexion, $sql_servicios);
while ($fila = mysqli_fetch_assoc($resultado_servicios)) {
    $servicios[] = $fila;
}
mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita - F&F</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        /* (Estilos base de perfil_cliente.php) */
        body { font-family: 'Roboto', sans-serif; margin: 0; background-color: #f0f2f5; color: #333333; }
        nav { background-color: #ffffff; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 0 2rem; display: flex; justify-content: space-between; align-items: center; height: 60px; }
        .nav-left { display: flex; align-items: center; }
        .nav-logo { font-size: 24px; font-weight: 700; color: #8fc1ea; text-decoration: none; margin-right: 2rem; }
        .nav-links a { font-size: 16px; font-weight: 500; color: #555555; text-decoration: none; padding: 20px 1rem; }
        .nav-links a.active { color: #2196F3; border-bottom: 3px solid #2196F3; }
        .nav-links a:hover { color: #2196F3; }
        .nav-right a { font-size: 14px; color: #666666; text-decoration: none; }
        .container { max-width: 700px; margin: 30px auto; padding: 0 1rem; }
        h2 { font-size: 28px; font-weight: 500; color: #333333; margin-bottom: 20px; }
        .card { background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); margin-bottom: 25px; }
        /* Estilos de Formulario */
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #555; font-weight: 500; font-size: 16px; }
        select, input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            font-family: 'Roboto', sans-serif;
            color: #333;
            background-color: #f9f9f9;
            box-sizing: border-box; /* Añadido para consistencia */
        }
        select:disabled {
            background-color: #e9ecef;
            color: #6c757d;
        }
        button { 
            width: 100%; padding: 12px; background-color: #3c74a3; color: white; border: none; 
            border-radius: 4px; font-size: 16px; cursor: pointer; font-weight: 500;
        }
        button:hover { background-color: #8fc1ea; }
    </style>
</head>
<body>

    <nav>
        <div class="nav-left">
            <a href="perfil_cliente.php" class="nav-logo">F&F</a>
            <div class="nav-links">
                <a href="perfil_cliente.php">Mi Perfil</a>
                <a href="mis_mascotas.php">Mis Mascotas</a>
                <a href="agendar_cita.php" class="active">Agendar Cita</a>
            </div>
        </div>
        <div class="nav-right">
            <a href="php/logout.php">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <h2>Agendar Nueva Cita (RF-001)</h2>
        
        <div class="card">
            <form action="php/procesar_cita.php?accion=agendar" method="POST">
                
                <div class="form-group">
                    <label for="mascota">1. Selecciona tu mascota</label>
                    <select id="mascota" name="mascota_id" required>
                        <option value="">-- Elige una mascota --</option>
                        <?php
                        foreach ($mascotas as $mascota) {
                            echo '<option value="' . $mascota['mascota_id'] . '">' . htmlspecialchars($mascota['nombre']) . ' (' . htmlspecialchars($mascota['especie']) . ')</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="servicio">2. Selecciona el servicio</label>
                    <select id="servicio" name="servicio_id" required>
                        <option value="">-- Elige un servicio --</option>
                        <?php
                        foreach ($servicios as $servicio) {
                            echo '<option value="' . $servicio['servicio_id'] . '">' . htmlspecialchars($servicio['nombre']) . ' ($' . number_format($servicio['precio'], 0, ',', '.') . ')</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha">3. Selecciona la fecha</label>
                    <input 
                        type="text" 
                        id="fecha" 
                        name="fecha" 
                        placeholder="Elige una fecha..."
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="hora">4. Selecciona la hora</label>
                    <select id="hora" name="hora" required disabled>
                        <option value="">-- Primero elige una fecha --</option>
                    </select>
                </div>

                <button type="submit">Confirmar Cita</button>
            </form>
        </div>
    </div> 
    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // 1. Opciones base del calendario
        const calendario = flatpickr("#fecha", {
            dateFormat: "Y-m-d", // Formato que entiende MySQL
            minDate: "today",
            // Deshabilitar Domingos (Día 0)
            disable: [
                function(date) {
                    return (date.getDay() === 0);
                }
            ],
            // 2. ACCIÓN: Cuando el usuario cambia la fecha
            onChange: function(selectedDates, dateStr, instance) {
                const selectorHora = document.getElementById('hora');
                selectorHora.disabled = true;
                selectorHora.innerHTML = '<option value="">Cargando horas...</option>';
                
                // Si se seleccionó una fecha válida
                if (dateStr) {
                    // 3. Preguntamos al servidor qué horas están ocupadas
                    fetch('php/get_booked_slots.php?date=' + dateStr)
                        .then(response => response.json())
                        .then(bookedSlots => {
                            // 4. Generamos las horas disponibles
                            generarHorarios(selectedDates[0], bookedSlots);
                        });
                }
            }
        });

        // 5. Función que genera los horarios
        function generarHorarios(fechaSeleccionada, horasOcupadas) {
            const selectorHora = document.getElementById('hora');
            selectorHora.innerHTML = ''; // Limpiamos el selector
            
            const dia = fechaSeleccionada.getDay(); // 0=Domingo, 6=Sábado
            
            // 6. Definimos las reglas de negocio
            let horaInicio, horaFin;
            if (dia === 6) { // Sábado
                horaInicio = 8; // 8:00 AM
                horaFin = 14;   // 2:00 PM (la última cita es 14:30)
            } else { // Lunes a Viernes
                horaInicio = 8; // 8:00 AM
                horaFin = 17;   // 5:00 PM (la última cita es 17:30)
            }
            
            let hayHorasDisponibles = false;

            // 7. Creamos los bloques de 30 minutos
            for (let h = horaInicio; h <= horaFin; h++) {
                // Bloque de :00 (ej: 08:00)
                let horaBloque1 = h.toString().padStart(2, '0') + ':00';
                // Verificamos si la hora NO está en la lista de ocupadas
                if (!horasOcupadas.includes(horaBloque1)) {
                    selectorHora.add(new Option(horaBloque1, horaBloque1));
                    hayHorasDisponibles = true;
                }
                
                // Bloque de :30 (ej: 08:30)
                // (No añadir 17:30 si la hora fin es 17, o 14:30 si es 14)
                if (h < horaFin) {
                    let horaBloque2 = h.toString().padStart(2, '0') + ':30';
                    if (!horasOcupadas.includes(horaBloque2)) {
                        selectorHora.add(new Option(horaBloque2, horaBloque2));
                        hayHorasDisponibles = true;
                    }
                }
            }
            
            // 8. Mostramos el resultado
            if (hayHorasDisponibles) {
                selectorHora.disabled = false;
            } else {
                selectorHora.innerHTML = '<option value="">No hay horas disponibles este día</option>';
            }
        }
    </script>
</body>
</html>