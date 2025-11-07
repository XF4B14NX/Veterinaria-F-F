<?php
// 1. ¡Incluimos al "guardia"!
include 'php/verificar_sesion_cliente.php';

// 2. Incluimos la conexión a la BD
include 'php/conexiones.php';

// 3. --- VALIDACIÓN DE SEGURIDAD ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Error: No se especificó una mascota.";
    exit();
}
$id_mascota_solicitada = $_GET['id'];

// 4.Verificamos que esta mascota le pertenezca al usuario logueado
$sql_verificar = "SELECT nombre, especie, raza FROM mascotas WHERE mascota_id = ? AND propietario_id = ?";
$stmt_verificar = mysqli_prepare($conexion, $sql_verificar);
mysqli_stmt_bind_param($stmt_verificar, "ii", $id_mascota_solicitada, $id_usuario_logueado);
mysqli_stmt_execute($stmt_verificar);
$resultado_verificar = mysqli_stmt_get_result($stmt_verificar);

if (mysqli_num_rows($resultado_verificar) != 1) {
    echo "Error: No tienes permisos para ver esta mascota.";
    exit();
}
$mascota = mysqli_fetch_assoc($resultado_verificar);
$nombre_mascota = $mascota['nombre'];
$especie_mascota = $mascota['especie'];
$raza_mascota = $mascota['raza'];

mysqli_stmt_close($stmt_verificar);
// --- FIN DE VALIDACIÓN ---
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de <?php echo htmlspecialchars($nombre_mascota); ?> - F&F</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
             font-family: 'Roboto', sans-serif;
             margin: 0;
              background-color: #f0f2f5;
              color: #333333; }
        nav {
             background-color: #ffffff;
             box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
              padding: 0 2rem; display:
               flex; justify-content: space-between;
                align-items: center; height: 60px; }
        .nav-left {
             display: flex;
             align-items: center; }
        .nav-logo {
             font-size: 24px;
             font-weight: 700; color: #8fc1ea;
              text-decoration: none;
               margin-right: 2rem; }
        .nav-links a {
             font-size: 16px;
             font-weight: 500;
              color: #555555; text-decoration: none;
               padding: 20px 1rem; }
        .nav-links a.active {
             color: #2196F3;
             border-bottom: 3px solid #2196F3; }
        .nav-links a:hover {
             color: #2196F3; }
        .nav-right a {
             font-size: 14px; color: #666666;
             text-decoration: none; }
        .container {
             max-width: 1000px; margin: 30px auto;
             padding: 0 1rem; }
        h2 {
             font-size: 28px;
            font-weight: 500;
            color: #333333;
            margin-bottom: 20px; }
        .card {
             background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px; }
        .card p { font-size: 16px; color: #555555;
             line-height: 1.6; }
        .card p strong { color: #333333;
             font-weight: 500; }
        .back-link { font-size: 16px;
             font-weight: 500;
              color: #2196F3;
             text-decoration: none;
              display: inline-block;
               margin-bottom: 20px; }
        .back-link:hover {
             text-decoration: underline; }
        /* Estilos para la tabla */
        .table-wrapper {
             overflow-x: auto; }
        table {
             width: 100%; border-collapse: collapse;
            margin-top: 20px; }
        th, td {
             padding: 16px; text-align: left;
            border-bottom: 1px solid #f0f2f5;
            font-size: 15px; color: #555555; }
        thead th {
             background-color: #f9f9f9;
            font-weight: 500; color: #333333;
            font-size: 14px;
            text-transform: uppercase; }
        tbody tr:hover {
             background-color: #fcfcfc; }
    </style>
</head>
<body>

    <nav>
        <div class="nav-left">
            <a href="perfil_cliente.php" class="nav-logo">F&F</a>
            <div class="nav-links">
                <a href="perfil_cliente.php">Mi Perfil</a>
                <a href="mis_mascotas.php" class="active">Mis Mascotas</a>
                <a href="agendar_cita.php">Agendar Cita</a>
            </div>
        </div>
        <div class="nav-right">
            <a href="php/logout.php">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <a href="perfil_cliente.php" class="back-link">&larr; Volver a Mi Perfil</a>
        <h2>Ficha de <?php echo htmlspecialchars($nombre_mascota); ?></h2>
        
        <div class="card">
            <h3>Información de la Mascota</h3>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre_mascota); ?></p>
            <p><strong>Especie:</strong> <?php echo htmlspecialchars($especie_mascota); ?></p>
            <p><strong>Raza:</strong> <?php echo htmlspecialchars($raza_mascota); ?></p>
        </div>

        <div class="card">
            <h3>Historial de Vacunas (RF-004)</h3>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Nombre Vacuna</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // --- INICIO LECTURA HISTORIAL (RF-004) ---
                        $sql_vacunas = "SELECT fecha, nombre_vacuna, observaciones 
                                        FROM historial_vacunas
                                        WHERE mascota_id = ?
                                        ORDER BY fecha DESC";
                        
                        $stmt_vacunas = mysqli_prepare($conexion, $sql_vacunas);
                        mysqli_stmt_bind_param($stmt_vacunas, "i", $id_mascota_solicitada);
                        mysqli_stmt_execute($stmt_vacunas);
                        $resultado_vacunas = mysqli_stmt_get_result($stmt_vacunas);

                        if (mysqli_num_rows($resultado_vacunas) > 0) {
                            while ($vacuna = mysqli_fetch_assoc($resultado_vacunas)) {
                                $fecha_vacuna = date("d/m/Y", strtotime($vacuna['fecha']));
                                echo "<tr>";
                                echo "<td>" . $fecha_vacuna . "</td>";
                                echo "<td>" . htmlspecialchars($vacuna['nombre_vacuna']) . "</td>";
                                echo "<td>" . htmlspecialchars($vacuna['observaciones']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo '<tr><td colspan="3">Esta mascota aún no tiene vacunas registradas.</td></tr>';
                        }
                        mysqli_stmt_close($stmt_vacunas);
                        mysqli_close($conexion);
                        // --- FIN LECTURA HISTORIAL (RF-004) ---
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> 
</body>
</html>