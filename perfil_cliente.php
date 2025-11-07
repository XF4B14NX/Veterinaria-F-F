<?php
// 1. ¡Incluimos al "guardia"!
include 'verificar_sesion_cliente.php';
// 2. Incluimos la conexión a la BD
include 'conexiones.php';
// 3. Ya tenemos $id_usuario_logueado y $nombre_usuario_logueado
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - F&F</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* (Tu CSS de versionclientes.html va aquí) */
        body { font-family: 'Roboto', sans-serif; margin: 0; background-color: #f0f2f5; color: #333333; }
        nav { background-color: #ffffff; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 0 2rem; display: flex; justify-content: space-between; align-items: center; height: 60px; }
        .nav-left { display: flex; align-items: center; }
        .nav-logo { font-size: 24px; font-weight: 700; color: #8fc1ea; text-decoration: none; margin-right: 2rem; }
        .nav-links a { font-size: 16px; font-weight: 500; color: #555555; text-decoration: none; padding: 20px 1rem; }
        .nav-links a.active { color: #2196F3; border-bottom: 3px solid #2196F3; }
        .nav-links a:hover { color: #2196F3; }
        .nav-right a { font-size: 14px; color: #666666; text-decoration: none; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 1rem; }
        h2 { font-size: 28px; font-weight: 500; color: #333333; margin-bottom: 20px; }
        .card { background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); margin-bottom: 25px; }
        .info-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .info-header h3 { font-size: 20px; font-weight: 500; margin: 0; }
        .card p { font-size: 16px; color: #555555; line-height: 1.6; }
        .card p strong { color: #333333; font-weight: 500; }
        button { padding: 10px 20px; background-color: #4c93af; color: #ffffff; border: none; border-radius: 4px; font-size: 15px; font-weight: 500; cursor: pointer; }
        button:hover { background-color: #8fc1ea; }
        .grid-container { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        .card ul { list-style: none; padding: 0; margin: 0; }
        .mascota-item { display: flex; justify-content: space-between; align-items: center; padding: 15px; background-color: #f9f9f9; border-radius: 4px; }
        .mascota-item:not(:last-child) { margin-bottom: 10px; }
        .mascota-item span { font-weight: 500; }
        .mascota-item a { color: #2196F3; text-decoration: none; font-weight: 500; font-size: 14px; }
        .cita-item { background-color: #f9f9f9; padding: 15px; border-radius: 4px; }
    </style>
</head>
<body>
    <nav>
        <div class="nav-left">
            <a href="#" class="nav-logo">F&F</a>
            <div class="nav-links">
                <a href="perfil_cliente.php" class="active">Mi Perfil</a>
                <a href="#">Mis Mascotas</a>
                <a href="#">Agendar Cita</a>
            </div>
        </div>
        <div class="nav-right">
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <h2>Mi Perfil (¡Hola, <?php echo htmlspecialchars($nombre_usuario_logueado); ?>!)</h2>
        
        <div class="card">
            <div class="info-header">
                <h3>Información del Dueño (RF-002)</h3>
                <button>Editar mi información</button>
            </div>
            <?php
            // --- INICIO LECTURA DATOS DUEÑO (RF-002) ---
            $sql_dueño = "SELECT nombre, correo, numero FROM propietarios WHERE propietario_id = ?";
            $stmt_dueño = mysqli_prepare($conexion, $sql_dueño);
            mysqli_stmt_bind_param($stmt_dueño, "i", $id_usuario_logueado); 
            mysqli_stmt_execute($stmt_dueño);
            $resultado_dueño = mysqli_stmt_get_result($stmt_dueño);
            $dueño = mysqli_fetch_assoc($resultado_dueño);
            if ($dueño) {
                echo "<p><strong>Nombre:</strong> " . htmlspecialchars($dueño['nombre']) . "</p>";
                echo "<p><strong>Correo:</strong> " . htmlspecialchars($dueño['correo']) . "</p>";
                echo "<p><strong>Teléfono:</strong> " . htmlspecialchars($dueño['numero']) . "</p>";
            }
            mysqli_stmt_close($stmt_dueño);
            // --- FIN LECTURA DATOS DUEÑO (RF-002) ---
            ?>
        </div>

        <div class="grid-container">
            <div class="card">
                <h3>Mis Mascotas (RF-003)</h3>
                <ul>
                    <?php
                    // --- INICIO LECTURA MASCOTAS (RF-003) ---
                    $sql_mascotas = "SELECT nombre, especie, raza FROM mascotas WHERE propietario_id = ?";
                    $stmt_mascotas = mysqli_prepare($conexion, $sql_mascotas);
                    mysqli_stmt_bind_param($stmt_mascotas, "i", $id_usuario_logueado);
                    mysqli_stmt_execute($stmt_mascotas);
                    $resultado_mascotas = mysqli_stmt_get_result($stmt_mascotas);

                    if (mysqli_num_rows($resultado_mascotas) > 0) {
                        while ($mascota = mysqli_fetch_assoc($resultado_mascotas)) {
                            $icono = (strtolower($mascota['especie']) == 'perro') ? '🐶' : '🐱';
                            echo '<li class="mascota-item">';
                            echo '<span>' . $icono . ' ' . htmlspecialchars($mascota['nombre']) . ' (' . htmlspecialchars($mascota['especie']) . ', ' . htmlspecialchars($mascota['raza']) . ')</span>';
                            echo '<a href="#">Ver Ficha (RF-004)</a>';
                            echo '</li>';
                        }
                    } else { echo '<p>No tienes mascotas registradas.</p>'; }
                    mysqli_stmt_close($stmt_mascotas);
                    // --- FIN LECTURA MASCOTAS (RF-003) ---
                    ?>
                </ul>
            </div>
            
            <div class="card">
                <h3>Mis Próximas Citas (RF-005)</h3>
                <ul>
                    <?php
                    // --- INICIO LECTURA CITAS (RF-005) ---
                    $sql_citas = "SELECT c.fecha_hora, m.nombre as nombre_mascota, s.nombre as nombre_servicio
                                  FROM citas c
                                  JOIN mascotas m ON c.mascota_id = m.mascota_id
                                  JOIN servicios s ON c.servicio_id = s.servicio_id
                                  WHERE m.propietario_id = ? AND c.fecha_hora >= NOW()
                                  ORDER BY c.fecha_hora ASC";
                    $stmt_citas = mysqli_prepare($conexion, $sql_citas);
                    mysqli_stmt_bind_param($stmt_citas, "i", $id_usuario_logueado);
                    mysqli_stmt_execute($stmt_citas);
                    $resultado_citas = mysqli_stmt_get_result($stmt_citas);

                    if (mysqli_num_rows($resultado_citas) > 0) {
                        while ($cita = mysqli_fetch_assoc($resultado_citas)) {
                            $fecha_formateada = date("d/m/Y \a \l\a\s H:i", strtotime($cita['fecha_hora']));
                            echo '<li class="cita-item">';
                            echo '<p><strong>Mascota:</strong> ' . htmlspecialchars($cita['nombre_mascota']) . '</p>';
                            echo '<p><strong>Servicio:</strong> ' . htmlspecialchars($cita['nombre_servicio']) . '</p>';
                            echo '<p><strong>Fecha:</strong> ' . $fecha_formateada . '</p>';
                            echo '</li>';
                        }
                    } else { echo '<p>No tienes citas programadas.</p>'; }
                    mysqli_stmt_close($stmt_citas);
                    // --- FIN LECTURA CITAS (RF-005) ---
                    ?>
                </ul>
            </div>
        </div>
    </div> 
</body>
</html>