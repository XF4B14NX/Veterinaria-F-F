<?php
// 1. Incluimos el guardián de admin y la conexión
include 'php/verificar_sesion_admin.php';
include 'php/conexiones.php';

// 2. Inicializamos variables
$dueño = null;
$mascotas = [];
$mascota_seleccionada = null;
$historial = [];

// 3. Lógica de Búsqueda (RF-010)
// Si se busca por RUT...
if (isset($_GET['rut']) && !empty($_GET['rut'])) {
    $rut_buscado = mysqli_real_escape_string($conexion, $_GET['rut']);
    
    // Buscamos al dueño
    $sql_dueño = "SELECT * FROM propietarios WHERE rut = ?";
    $stmt_dueño = mysqli_prepare($conexion, $sql_dueño);
    mysqli_stmt_bind_param($stmt_dueño, "s", $rut_buscado);
    mysqli_stmt_execute($stmt_dueño);
    $resultado_dueño = mysqli_stmt_get_result($stmt_dueño);
    
    if (mysqli_num_rows($resultado_dueño) == 1) {
        $dueño = mysqli_fetch_assoc($resultado_dueño);
        // Si encontramos al dueño, buscamos sus mascotas
        $sql_mascotas = "SELECT * FROM mascotas WHERE propietario_id = ?";
        $stmt_mascotas = mysqli_prepare($conexion, $sql_mascotas);
        mysqli_stmt_bind_param($stmt_mascotas, "i", $dueño['propietario_id']);
        mysqli_stmt_execute($stmt_mascotas);
        $resultado_mascotas = mysqli_stmt_get_result($stmt_mascotas);
        while ($fila = mysqli_fetch_assoc($resultado_mascotas)) {
            $mascotas[] = $fila;
        }
        mysqli_stmt_close($stmt_mascotas);
    }
    mysqli_stmt_close($stmt_dueño);
} 
// Si se selecciona una mascota directamente...
else if (isset($_GET['mascota_id']) && is_numeric($_GET['mascota_id'])) {
    $id_mascota_buscada = $_GET['mascota_id'];
    
    // Buscamos la mascota
    $sql_mascota = "SELECT * FROM mascotas WHERE mascota_id = ?";
    $stmt_mascota = mysqli_prepare($conexion, $sql_mascota);
    mysqli_stmt_bind_param($stmt_mascota, "i", $id_mascota_buscada);
    mysqli_stmt_execute($stmt_mascota);
    $resultado_mascota = mysqli_stmt_get_result($stmt_mascota);
    
    if (mysqli_num_rows($resultado_mascota) == 1) {
        $mascota_seleccionada = mysqli_fetch_assoc($resultado_mascota);
        
        // Buscamos al dueño de esa mascota
        $sql_dueño_mascota = "SELECT * FROM propietarios WHERE propietario_id = ?";
        $stmt_dueño_mascota = mysqli_prepare($conexion, $sql_dueño_mascota);
        mysqli_stmt_bind_param($stmt_dueño_mascota, "i", $mascota_seleccionada['propietario_id']);
        mysqli_stmt_execute($stmt_dueño_mascota);
        $dueño = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_dueño_mascota));
        mysqli_stmt_close($stmt_dueño_mascota);

        // Si encontramos la mascota, cargamos su historial (RF-011)
        $sql_historial = "SELECT * FROM historial_vacunas WHERE mascota_id = ? ORDER BY fecha DESC";
        $stmt_historial = mysqli_prepare($conexion, $sql_historial);
        mysqli_stmt_bind_param($stmt_historial, "i", $id_mascota_buscada);
        mysqli_stmt_execute($stmt_historial);
        $resultado_historial = mysqli_stmt_get_result($stmt_historial);
        while ($fila = mysqli_fetch_assoc($resultado_historial)) {
            $historial[] = $fila;
        }
        mysqli_stmt_close($stmt_historial);
    }
    mysqli_stmt_close($stmt_mascota);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichas Clínicas - F&F Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
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
        .card-header {
            padding: 20px 25px; background-color: #f9f9f9; border-bottom: 1px solid #f0f2f5; }
        .card-header h3 {
            margin: 0; font-size: 20px; font-weight: 500; }
        .card-body {
            padding: 25px; }
        .form-group {
            margin-bottom: 20px; }
        .form-group label {
            display: block; margin-bottom: 8px; color: #555; font-weight: 500; font-size: 16px; }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; font-family: 'Roboto', sans-serif; color: #333; background-color: #f9f9f9; box-sizing: border-box; }
        button {
            padding: 12px 22px; background-color: #3c74a3; color: #ffffff; border: none; border-radius: 4px; font-size: 16px; font-weight: 500; cursor: pointer; transition: background-color 0.3s ease; }
        button:hover {
            background-color: #8fc1ea; }
        .table-wrapper {
            overflow-x: auto; }
        table {
            width: 100%; border-collapse: collapse; }
        th, td {
            padding: 16px; text-align: left; border-bottom: 1px solid #f0f2f5; font-size: 15px; color: #555555; }
        thead th {
            background-color: #f9f9f9; font-weight: 500; color: #333333; font-size: 14px; text-transform: uppercase; }
        .search-results ul {
            list-style: none; padding: 0; margin: 0; }
        .search-results li a {
            display: block; padding: 15px; background-color: #f9f9f9; border-radius: 4px; margin-bottom: 10px; text-decoration: none; color: #333; font-weight: 500; }
        .search-results li a:hover {
            background-color: #e9e9e9; }
        .info-p {
             font-size: 16px; color: #555555; line-height: 1.6; }
        .info-p strong {
             color: #333333; font-weight: 500; }
        .grid-ficha {
             display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
    </style>
</head>
<body>

    <div class="admin-wrapper">
        
        <aside class="sidebar">
            <div class="sidebar-logo">F&F Admin</div>
  <nav class="sidebar-nav">
    <a href="mant_citas.php"><i data-feather="calendar"></i> Mant. Citas</a> 
    <a href="listadoclientes.php"><i data-feather="users"></i> Listado Clientes</a>
    <a href="mant_fichas_clinicas.php" class="active"><i data-feather="file-text"></i> Mant. Fichas Clínicas</a>
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
            <h2>Mantenedor de Fichas Clínicas</h2>
            
            <div class="card">
                <div class="card-header">
                    <h3>1. Buscar Dueño por RUT (RF-010)</h3>
                </div>
                <div class="card-body">
                    <form action="mant_fichas_clinicas.php" method="GET">
                        <div class="form-group" style="max-width: 400px;">
                            <label for="rut">RUT del Dueño</label>
                            <input type="text" id="rut" name="rut" placeholder="12345678-9" value="<?php echo isset($_GET['rut']) ? htmlspecialchars($_GET['rut']) : ''; ?>">
                        </div>
                        <button type="submit">Buscar Dueño</button>
                    </form>
                </div>
            </div>

            <?php if ($dueño && !$mascota_seleccionada): // Si buscamos un RUT y encontramos dueño, pero aún no elegimos mascota ?>
            <div class="card">
                <div class="card-header">
                    <h3>2. Seleccionar Mascota</h3>
                </div>
                <div class="card-body search-results">
                    <h4>Dueño: <?php echo htmlspecialchars($dueño['nombre']); ?></h4>
                    <?php if (!empty($mascotas)): ?>
                        <p>Selecciona una mascota para ver su ficha:</p>
                        <ul>
                            <?php foreach ($mascotas as $mascota): ?>
                                <li>
                                    <a href="mant_fichas_clinicas.php?mascota_id=<?php echo $mascota['mascota_id']; ?>">
                                        <?php echo (strtolower($mascota['especie']) == 'perro') ? '🐶' : '🐱'; ?>
                                        <?php echo htmlspecialchars($mascota['nombre']) . ' (' . htmlspecialchars($mascota['especie']) . ', ' . htmlspecialchars($mascota['raza']) . ')'; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Este dueño no tiene mascotas registradas.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($mascota_seleccionada): // Si ya seleccionamos una mascota ?>
            <div class="grid-ficha">
                <div class="card">
                    <div class="card-header">
                        <h3>Añadir a Ficha (RF-012)</h3>
                    </div>
                    <div class="card-body">
                        <form action="php/crear_entrada_ficha.php" method="POST">
                            <input type="hidden" name="mascota_id" value="<?php echo $mascota_seleccionada['mascota_id']; ?>">
                            
                            <div class="form-group">
                                <label for="fecha">Fecha</label>
                                <input type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="nombre_procedimiento">Procedimiento / Vacuna</label>
                                <input type="text" id="nombre_procedimiento" name="nombre_procedimiento" placeholder="Ej: Vacuna Óctuple, Consulta" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="observaciones">Observaciones / Diagnóstico</label>
                                <textarea id="observaciones" name="observaciones" rows="4" placeholder="Paciente presenta..."></textarea>
                            </div>
                            
                            <button type="submit">Guardar en Ficha</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Historial de <?php echo htmlspecialchars($mascota_seleccionada['nombre']); ?> (RF-011)</h3>
                    </div>
                    <div class="card-body">
                        <p class="info-p"><strong>Dueño:</strong> <?php echo htmlspecialchars($dueño['nombre']); ?></p>
                        <p class="info-p"><strong>Especie:</strong> <?php echo htmlspecialchars($mascota_seleccionada['especie']); ?></p>
                        <p class="info-p"><strong>Raza:</strong> <?php echo htmlspecialchars($mascota_seleccionada['raza']); ?></p>
                        <hr>
                        <h4>Historial de Atenciones</h4>
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Procedimiento</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($historial)): ?>
                                        <?php foreach ($historial as $entrada): ?>
                                            <tr>
                                                <td><?php echo date("d/m/Y", strtotime($entrada['fecha'])); ?></td>
                                                <td><?php echo htmlspecialchars($entrada['nombre_vacuna']); ?></td>
                                                <td><?php echo htmlspecialchars($entrada['observaciones']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3">Esta mascota aún no tiene historial clínico.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </main>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>