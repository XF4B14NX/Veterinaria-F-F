<?php
// 1. Incluimos el guardián de admin y la conexión
include 'php/verificar_sesion_admin.php';
include 'php/conexiones.php';

// 2. Pre-cargamos TODOS los clientes, mascotas y servicios para los dropdowns del modal
$clientes = [];
$mascotas = [];
$servicios = [];

// Cargar Clientes
$sql_clientes = "SELECT propietario_id, nombre, rut FROM propietarios ORDER BY nombre ASC";
$resultado_clientes = mysqli_query($conexion, $sql_clientes);
while ($fila = mysqli_fetch_assoc($resultado_clientes)) {
    $clientes[] = $fila;
}

// Cargar Mascotas (con el nombre del dueño)
$sql_mascotas = "SELECT m.mascota_id, m.nombre, p.nombre as nombre_dueño 
                 FROM mascotas m
                 JOIN propietarios p ON m.propietario_id = p.propietario_id
                 ORDER BY p.nombre, m.nombre";
$resultado_mascotas = mysqli_query($conexion, $sql_mascotas);
while ($fila = mysqli_fetch_assoc($resultado_mascotas)) {
    $mascotas[] = $fila;
}

// Cargar Servicios
$sql_servicios = "SELECT servicio_id, nombre FROM servicios ORDER BY nombre ASC";
$resultado_servicios = mysqli_query($conexion, $sql_servicios);
while ($fila = mysqli_fetch_assoc($resultado_servicios)) {
    $servicios[] = $fila;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citas - F&F Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        /* (Pega aquí TODO el CSS de listadoclientes.php) */
        body {
            font-family: 'Roboto', sans-serif; margin: 0; background-color: #f0f2f5; color: #333333; }
        .admin-wrapper {
            display: flex; min-height: 100vh; background-color: #f0f2f5; }
        .sidebar {
            width: 250px; background-color: #34495e; color: #ffffff; display: flex; flex-direction: column; box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1); flex-shrink: 0; }
        .sidebar-logo {
            height: 60px; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 700; color: #8fc1ea; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar-nav {
            flex-grow: 1; padding: 15px 0; }
        .sidebar-nav a {
            display: flex; align-items: center; padding: 12px 20px; color: rgba(255, 255, 255, 0.8); text-decoration: none; font-size: 16px; transition: background-color 0.2s ease, color 0.2s ease; border-left: 4px solid transparent; }
        .sidebar-nav a:hover {
            background-color: #44617a; color: #ffffff; }
        .sidebar-nav a.active {
            background-color: #44617a; color: #ffffff; border-left-color: #2196F3; }
        .sidebar-nav a svg {
            margin-right: 12px; width: 20px; height: 20px; }
        .sidebar-footer {
            padding: 15px 20px; border-top: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar-footer a {
            display: flex; align-items: center; color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 15px; }
        .sidebar-footer a:hover {
            color: #ffffff; }
        .main-content {
            flex-grow: 1; padding: 30px; overflow-y: auto; }
        h2 {
            font-size: 28px; font-weight: 500; color: #333333; margin-bottom: 30px; }
        .card {
            background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); overflow: hidden; margin-bottom: 25px; }
        .table-wrapper {
            overflow-x: auto; }
        table {
            width: 100%; border-collapse: collapse; min-width: 800px; }
        th, td {
            padding: 16px; text-align: left; border-bottom: 1px solid #f0f2f5; font-size: 15px; color: #555555; }
        thead th {
            background-color: #f9f9f9; font-weight: 500; color: #333333; font-size: 14px; text-transform: uppercase; }
        td a {
            font-weight: 500; text-decoration: none; margin-right: 15px; }
        .action-delete {
            color: #f44336; }
        .action-delete:hover {
            text-decoration: underline; }
        .actions-header {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
        .btn-add {
            padding: 12px 22px; background-color: #64b8fc; color: #ffffff; border: none; border-radius: 4px; font-size: 16px; font-weight: 500; cursor: pointer; text-decoration: none; transition: background-color 0.3s ease; }
        .btn-add:hover {
            background-color: #8fc1ea; }
        /* (Estilos para el Modal, copiados de perfil_cliente.php) */
        .modal {
            display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.4); }
        .modal-content {
            background-color: #ffffff; margin: 10% auto; padding: 30px; border-radius: 8px; width: 80%; max-width: 600px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
        .close-button {
            color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close-button:hover { color: #333; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #555; font-weight: 500; font-size: 16px; }
        .form-group input, .form-group select {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; font-family: 'Roboto', sans-serif; color: #333; background-color: #f9f9f9; box-sizing: border-box; }
        button {
             padding: 10px 20px; background-color: #4c93af; color: #ffffff; border: none; border-radius: 4px; font-size: 15px; font-weight: 500; cursor: pointer; }
        button:hover {
             background-color: #8fc1ea; }
    </style>
</head>
<body>

    <div class="admin-wrapper">
        
        <aside class="sidebar">
            <div class="sidebar-logo">F&F Admin</div>
            <nav class="sidebar-nav">
                <a href="mant_citas.php" class="active"><i data-feather="calendar"></i> Mant. Citas</a>
                <a href="listadoclientes.php"><i data-feather="users"></i> Listado Clientes</a>
                <a href="mant_fichas_clinicas.php"><i data-feather="file-text"></i> Mant. Fichas Clínicas</a>
                <a href="#"><i data-feather="briefcase"></i> Mant. Personal</a>
                <a href="#"><i data-feather="settings"></i> Configuración</a>
                <a href="#"><i data-feather="bar-chart-2"></i> Reportes</a>
                <a href="Autores.php"><i data-feather="info"></i> Autor</a>
            </nav>
            <div class="sidebar-footer">
                <a href="php/logout.php"><i data-feather="log-out"></i> Cerrar Sesión</a>
            </div>
        </aside>

        <main class="main-content">
            <h2>Gestión de Citas (RF-007)</h2>

            <div class="actions-header">
                <button id="btnNuevaCita" class="btn-add">Agendar Cita (Telefónica)</button>
            </div>

            <div class="card">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Dueño</th>
                                <th>Mascota</th>
                                <th>Servicio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // 3. Leemos TODAS las citas de la BD (RF-007)
                            $sql_citas = "SELECT c.cita_id, c.fecha_hora, c.estado, 
                                                 p.nombre as nombre_dueño, 
                                                 m.nombre as nombre_mascota, 
                                                 s.nombre as nombre_servicio
                                          FROM citas c
                                          JOIN mascotas m ON c.mascota_id = m.mascota_id
                                          JOIN propietarios p ON m.propietario_id = p.propietario_id
                                          JOIN servicios s ON c.servicio_id = s.servicio_id
                                          ORDER BY c.fecha_hora DESC";
                            
                            $resultado_citas = mysqli_query($conexion, $sql_citas);

                            if (mysqli_num_rows($resultado_citas) > 0) {
                                while ($cita = mysqli_fetch_assoc($resultado_citas)) {
                                    $fecha_formateada = date("d/m/Y H:i", strtotime($cita['fecha_hora']));
                                    echo "<tr>";
                                    echo "<td>" . $fecha_formateada . "</td>";
                                    echo "<td>" . htmlspecialchars($cita['nombre_dueño']) . "</td>";
                                    echo "<td>" . htmlspecialchars($cita['nombre_mascota']) . "</td>";
                                    echo "<td>" . htmlspecialchars($cita['nombre_servicio']) . "</td>";
                                    echo "<td>" . htmlspecialchars($cita['estado']) . "</td>";
                                    echo '<td>';
                                    // 4. Creamos el enlace para cancelar (RF-007)
                                    // Solo mostramos "Cancelar" si la cita no está ya cancelada o completada
                                    if ($cita['estado'] == 'Programada') {
                                        echo '<a href="php/admin_procesar_cita.php?accion=cancelar&id=' . $cita['cita_id'] . '" class="action-delete" onclick="return confirm(\'¿Estás seguro de cancelar esta cita?\');">Cancelar</a>';
                                    } else {
                                        echo '---';
                                    }
                                    echo '</td>';
                                    echo "</tr>";
                                }
                            } else {
                                echo '<tr><td colspan="6">No hay citas registradas en el sistema.</td></tr>';
                            }
                            mysqli_close($conexion);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="modalAgendar" class="modal">
        <div class="modal-content">
            <span class="close-button" id="closeModal">&times;</span>
            <h3>Agendar Cita Telefónica</h3>
            <br>
            <form action="php/admin_procesar_cita.php?accion=agendar" method="POST">
                
                <div class="form-group">
                    <label for="mascota">1. Selecciona la mascota</label>
                    <select id="mascota" name="mascota_id" required>
                        <option value="">-- Elige una mascota (Dueño) --</option>
                        <?php
                        foreach ($mascotas as $mascota) {
                            echo '<option value="' . $mascota['mascota_id'] . '">' . htmlspecialchars($mascota['nombre']) . ' (' . htmlspecialchars($mascota['nombre_dueño']) . ')</option>';
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
                            echo '<option value="' . $servicio['servicio_id'] . '">' . htmlspecialchars($servicio['nombre']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_hora">3. Selecciona la fecha y hora</label>
                    <input type="datetime-local" id="fecha_hora" name="fecha_hora" required min="<?php echo date('Y-m-d\TH:i'); ?>">
                </div>

                <button type="submit" style="width: 100%;">Confirmar Cita</button>
            </form>
        </div>
    </div>

    <script>
        // 5. JavaScript para el modal (igual que en perfil_cliente.php)
        feather.replace();

        var modal = document.getElementById("modalAgendar");
        var btn = document.getElementById("btnNuevaCita");
        var span = document.getElementById("closeModal");

        btn.onclick = function() {
            modal.style.display = "block";
        }
        span.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>