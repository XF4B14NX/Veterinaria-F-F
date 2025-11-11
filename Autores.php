<?php
// 1. ¡Incluimos al "guardia" de Admin!
include 'php/verificar_sesion_admin.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autores del Proyecto - F&F Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f0f2f5; 
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
            width: 250px; 
            background-color: #34495e; 
            color: #ffffff;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar-logo {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 700;           
            color: #2196F3; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-nav {
            flex-grow: 1; 
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
            border-left: 4px solid transparent; /
        }

        .sidebar-nav a:hover {
            background-color: #44617a; 
            color: #ffffff;
        }

        .sidebar-nav a.active {
            background-color: #44617a;
            color: #ffffff;
            border-left-color: #2196F3; 
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
            overflow-y: auto; 
        }

        h2 {
            font-size: 28px;
            font-weight: 500;
            color: #333333;
            margin-bottom: 30px;
        }

        /* Contenedor de las tarjetas de autor */
        .authors-grid {
            display: flex; 
            justify-content: center; 
            flex-wrap: wrap; 
            gap: 30px; 
        }

        /* Tarjeta de Autor (Estilo Login) */
        .author-card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            width: 100%; 
            max-width: 280px; 
        }

        .author-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%; 
            object-fit: cover;
            margin: 0 auto 20px auto; 
            border: 3px solid #f0f2f5; 
        }

        .author-card h3 {
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333333;
        }

        .author-card p {
            font-size: 15px;
            color: #666666;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="sidebar-logo"> F&F Admin </div>
            
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
            <h2>Autores del Proyecto</h2>
            <div class="authors-grid">
                <div class="author-card">
                    <img src="" alt="Foto de Fabian">
                    <h3>Fabian Jara Sanchez</h3>
                    <p>.............</p>
                </div>
                <div class="author-card">
                    <img src="" alt="Foto de Fernanda">
                    <h3>Fernanda Aguirre Saez</h3>
                    <p>.............</p>
                </div>
            </div>
        </main>
    </div>
    <script>
        feather.replace();
    </script>
</body>
</html>