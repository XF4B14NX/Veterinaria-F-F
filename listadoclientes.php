<?php
// 1. ¡Incluimos al "guardia" de Admin!
include 'php/verificar_sesion_admin.php';

// 2. Incluimos la conexión a la BD
include 'php/conexiones.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado clientes - F&F Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    
    <style>
        /* --- Estilos Base (Inspirados en el Login) --- */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f0f2f5; /* Mismo fondo gris claro del login */
            color: #333333;
        }

        /* --- Contenedor Principal del Admin --- */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
            background-color: #f0f2f5;
        }

        /* --- Sidebar (Barra Lateral) --- */
        .sidebar {
            width: 250px; /* Ancho del sidebar */
            background-color: #34495e; /* Un gris azulado oscuro para el sidebar */
            color: #ffffff;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            flex-shrink: 0; /* Evita que se encoja */
        }

        .sidebar-logo {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 700;
            /* Usamos el verde del botón de login para el logo */
            color: #8fc1ea; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-nav {
            flex-grow: 1; /* Permite que la navegación ocupe el espacio restante */
            padding: 15px 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.2s ease, color 0.2s ease;
            border-left: 4px solid transparent; /* Para el indicador activo */
        }

        .sidebar-nav a:hover {
            background-color: #44617a; /* Un tono más claro al pasar el mouse */
            color: #ffffff;
        }

        .sidebar-nav a.active {
            background-color: #44617a;
            color: #ffffff;
            border-left-color: #2196F3; /* Azul para el indicador de activo */
        }

        .sidebar-nav a svg {
            margin-right: 12px;
            width: 20px;
            height: 20px;
        }

        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 15px;
        }
        
        .sidebar-footer a:hover {
            color: #ffffff;
        }
        
        /* --- Contenido Principal --- */
        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto; /* Permite scroll si el contenido es largo */
        }

        h2 {
            font-size: 28px;
            font-weight: 500;
            color: #333333;
            margin-bottom: 30px;
        }
        
        /* --- Contenedor de Acciones (Buscar y Añadir) --- */
        .actions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        input[type="text"] {
            padding: 12px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 15px;
            color: #333333;
            width: 100%;
            max-width: 450px; 
            box-sizing: border-box; 
        }
        
        input[type="text"]::placeholder {
            color: #999999; 
        }      
        .btn-add {
            padding: 12px 22px;
            background-color: #64b8fc; 
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-add:hover {
            background-color: #8fc1ea; 
        }

        .card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px; 
        }

        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #f0f2f5; 
            font-size: 15px;
            color: #555555;
        }

        thead th {
            background-color: #f9f9f9;
            font-weight: 500;
            color: #333333;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        tbody tr:last-child td {
            border-bottom: none;
        }
        
        tbody tr:hover {
            background-color: #fcfcfc;
        }
        td a {
            font-weight: 500;
            text-decoration: none;
            margin-right: 15px;
        }

        .action-modify {
            color: #2196F3; 
        }
        .action-modify:hover {
            text-decoration: underline;
        }

        .action-delete {
            color: #f44336; 
        }
        .action-delete:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="sidebar-logo">F&F Admin</div>
            
            <nav class="sidebar-nav">
                <a href="mant_citas.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'mant_citas.php') ? 'active' : ''; ?>">
                    <i data-feather="calendar"></i> Mant. Citas
                </a>
                <a href="listadoclientes.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'listadoclientes.php') ? 'active' : ''; ?>">
                    <i data-feather="users"></i> Listado Clientes
                </a>
                <a href="mant_fichas_clinicas.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'mant_fichas_clinicas.php') ? 'active' : ''; ?>">
                    <i data-feather="file-text"></i> Mant. Fichas Clínicas
                </a>
                
                <?php if ($rol_personal_logueado == 'Administrador'): ?>
                    <a href="#"><i data-feather="briefcase"></i> Mant. Personal</a>
                    <a href="#"><i data-feather="settings"></i> Configuración</a>
                    <a href="#"><i data-feather="bar-chart-2"></i> Reportes</a>
                <?php endif; ?>
                
                <a href="Autores.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'Autores.php') ? 'active' : ''; ?>">
                    <i data-feather="info"></i> Autor
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="php/logout.php"><i data-feather="log-out"></i> Cerrar Sesión</a>
            </div>
        </aside>

        <main class="main-content">
            <h2>Listado Clientes (¡Hola, <?php echo htmlspecialchars($nombre_personal_logueado . " - " . $rol_personal_logueado); ?>!)</h2>
            
            <div class="actions-header">
                <input type="text" placeholder="Buscar cliente por nombre o RUT (Búsqueda por PK)...">
                <a href="crear_usuario.php" class="btn-add">Añadir Cliente</a>
            </div>

            <div class="card">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>RUT</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Mascotas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // (El código PHP para llenar la tabla va aquí...)
                            $sql_listado = "SELECT * FROM propietarios";
                            $resultado_listado = mysqli_query($conexion, $sql_listado);

                            if (mysqli_num_rows($resultado_listado) > 0) {
                                while ($cliente = mysqli_fetch_assoc($resultado_listado)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($cliente['nombre']) . "</td>";
                                    echo "<td>" . htmlspecialchars($cliente['rut']) . "</td>";
                                    echo "<td>" . htmlspecialchars($cliente['correo']) . "</td>";
                                    echo "<td>" . htmlspecialchars($cliente['numero']) . "</td>";
                                    
                                    // Cálculo de mascotas
                                    $sql_count_mascotas = "SELECT COUNT(*) as total FROM mascotas WHERE propietario_id = ?";
                                    $stmt_count = mysqli_prepare($conexion, $sql_count_mascotas);
                                    mysqli_stmt_bind_param($stmt_count, "i", $cliente['propietario_id']);
                                    mysqli_stmt_execute($stmt_count);
                                    $count = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_count))['total'];
                                    echo "<td>" . $count . "</td>";
                                    mysqli_stmt_close($stmt_count);

                                    echo '<td>';
                                    echo '<a href="#" class="action-modify">Modificar</a>'; 
                                    echo '<a href="#" class="action-delete">Eliminar</a>'; 
                                    echo '</td>';
                                    echo "</tr>";
                                }
                            } else {
                                echo '<tr><td colspan="6">No hay clientes registrados.</td></tr>';
                            }
                            mysqli_close($conexion);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script> feather.replace(); </script>
</body>
</html>