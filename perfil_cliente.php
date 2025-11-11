<?php
// 1. ¡Incluimos al "guardia"!
include 'php/verificar_sesion_cliente.php';

// 2. Incluimos la conexión a la BD
include 'php/conexiones.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - F&F</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
             font-family: 'Roboto', sans-serif; margin: 0; background-color: #f0f2f5; color: #333333; }
        nav {
             background-color: #ffffff; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 0 2rem; display: flex; justify-content: space-between; align-items: center; height: 60px; }
        .nav-left {
             display: flex; align-items: center; }
        .nav-logo {
             font-size: 24px; font-weight: 700; color: #8fc1ea; text-decoration: none; margin-right: 2rem; }
        .nav-links a {
             font-size: 16px; font-weight: 500; color: #555555; text-decoration: none; padding: 20px 1rem; }
        .nav-links a.active {
             color: #2196F3; border-bottom: 3px solid #2196F3; }
        .nav-links a:hover {
             color: #2196F3; }
        .nav-right a {
             font-size: 14px; color: #666666; text-decoration: none; }
        .container {
             max-width: 1000px; margin: 30px auto; padding: 0 1rem; }
        h2 { 
            font-size: 28px; font-weight: 500; color: #333333; margin-bottom: 20px; }
        .card {
             background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); margin-bottom: 25px; }
        .info-header {
             display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .info-header h3 {
             font-size: 20px; font-weight: 500; margin: 0; }
        .card p {
             font-size: 16px; color: #555555; line-height: 1.6; }
        .card p strong {
             color: #333333; font-weight: 500; }
        button {
             padding: 10px 20px; background-color: #4c93af; color: #ffffff; border: none; border-radius: 4px; font-size: 15px; font-weight: 500; cursor: pointer; }
        button:hover {
             background-color: #8fc1ea; }
        .grid-container {
             display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        .card ul {
             list-style: none; padding: 0; margin: 0; }
        .mascota-item {
             display: flex; justify-content: space-between; align-items: center; padding: 15px; background-color: #f9f9f9; border-radius: 4px; }
        .mascota-item:not(:last-child) {
             margin-bottom: 10px; }
        .mascota-item span {
             font-weight: 500; }
        .mascota-item a {
             color: #2196F3; text-decoration: none; font-weight: 500; font-size: 14px; }
        .cita-item {
             background-color: #f9f9f9; padding: 15px; border-radius: 4px; }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #ffffff;
            margin: 15% auto;
            padding: 30px;
            border: 1px solid #888;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close-button:hover { color: #333; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #555; font-weight: 500; font-size: 16px; }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            font-family: 'Roboto', sans-serif;
            color: #333;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <nav>
        <div class="nav-left">
            <a href="perfil_cliente.php" class="nav-logo">F&F</a>
            <div class="nav-links">
                <a href="perfil_cliente.php" class="active">Mi Perfil</a>
                <a href="mis_mascotas.php">Mis Mascotas</a>
                <a href="agendar_cita.php">Agendar Cita</a>
            </div>
        </div>
        <div class="nav-right">
            <a href="php/logout.php">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        
        <h2>Mi Perfil (¡Hola, <?php echo htmlspecialchars($nombre_usuario_logueado); ?>!)</h2>
        
        <div class="card">
            <div class="info-header">
                <h3>Información del Dueño (RF-002)</h3>
                <button id="btnEditarPerfil">Editar mi información</button>
            </div>
            <?php
            // --- LECTURA DATOS DUEÑO (RF-002) ---
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
            ?>
        </div>

        <div class="grid-container">
            <div class="card">
                <h3>Mis Mascotas (RF-003)</h3>
                <ul>
                    <?php
                    // --- LECTURA MASCOTAS (RF-003) ---
                    $sql_mascotas = "SELECT mascota_id, nombre, especie, raza FROM mascotas WHERE propietario_id = ?";
                    $stmt_mascotas = mysqli_prepare($conexion, $sql_mascotas);
                    mysqli_stmt_bind_param($stmt_mascotas, "i", $id_usuario_logueado);
                    mysqli_stmt_execute($stmt_mascotas);
                    $resultado_mascotas = mysqli_stmt_get_result($stmt_mascotas);

                    if (mysqli_num_rows($resultado_mascotas) > 0) {
                        while ($mascota = mysqli_fetch_assoc($resultado_mascotas)) {
                            $icono = (strtolower($mascota['especie']) == 'perro') ? '🐶' : '🐱';
                            echo '<li class="mascota-item">';
                            echo '<span>' . $icono . ' ' . htmlspecialchars($mascota['nombre']) . ' (' . htmlspecialchars($mascota['especie']) . ', ' . htmlspecialchars($mascota['raza']) . ')</span>';
                            echo '<a href="mis_mascotas.php?id=' . $mascota['mascota_id'] . '">Ver Ficha (RF-004)</a>';
                            echo '</li>';
                        }
                    } else { echo '<p>No tienes mascotas registradas.</p>'; }
                    mysqli_stmt_close($stmt_mascotas);
                    ?>
                </ul>
            </div>
            
            <div class="card">
                <h3>Mis Próximas Citas (RF-005)</h3>
                <ul>
                    <?php
                    // --- LECTURA CITAS (RF-005) ---
                    $sql_citas = "SELECT c.cita_id, c.fecha_hora, m.nombre as nombre_mascota, s.nombre as nombre_servicio
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
                            echo '<a href="php/procesar_cita.php?accion=cancelar&id=' . $cita['cita_id'] . '" style="color: #f44336; font-size: 14px; text-decoration: none;" onclick="return confirm(\'¿Estás seguro de que deseas cancelar esta cita?\');">Cancelar Cita</a>';
                            echo '</li>';
                        }
                    } else { echo '<p>No tienes citas programadas.</p>'; }
                    mysqli_stmt_close($stmt_citas);
                    mysqli_close($conexion);
                    ?>
                </ul>
            </div>
        </div>
    </div> 

    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <span class="close-button" id="closeModal">&times;</span>
            <h3>Editar mi Información (RF-002)</h3>
            <br>
            <form action="php/actualizar_perfil_cliente.php" method="POST">
                <div class="form-group">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($dueño['correo']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($dueño['numero']); ?>" required>
                </div>
                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <script>
        // Obtenemos los elementos
        var modal = document.getElementById("modalEditar");
        var btn = document.getElementById("btnEditarPerfil");
        var span = document.getElementById("closeModal");

        // Cuando el usuario hace clic en el botón, abre el modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // Cuando el usuario hace clic en (x), cierra el modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Cuando el usuario hace clic fuera del modal, lo cierra
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>
</html>