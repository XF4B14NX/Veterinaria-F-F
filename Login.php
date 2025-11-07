<?php
// 1. Iniciamos la sesión
// Esto es necesario para poder recibir y mostrar mensajes de error.
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veterinaria F&F - Login</title>
    
    <style>
        /* (Tu CSS de Login.html va aquí) */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f0f0;
            background-size: cover;
            background-position: center;
        }
        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
            color: #333;
        }
        p {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #3c74a3;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #8fc1ea;
        }
        .create-account {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .create-account a {
            color: #3c74a3;
            text-decoration: none;
            font-weight: bold;
        }
        .error-message {
            color: #D8000C;
            background-color: #FFD2D2;
            border: 1px solid #D8000C;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <p>Un clic más cerca de tus pacientes peludos: ingresa a la Agenda Veterinaria</p>
        
        <?php
        // 2. Si el backend (validar_login.php) nos envió un error, lo mostramos aquí.
        if (isset($_SESSION['error_login'])) {
            echo '<div class="error-message">' . $_SESSION['error_login'] . '</div>';
            // 3. Borramos el error de la sesión para que no se muestre de nuevo
            unset($_SESSION['error_login']);
        }
        ?>


        <form action="validar_login.php" method="POST">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input 
                    type="text" 
                    id="usuario" 
                    name="usuario" 
                    required
                    placeholder="Ingresa tu usuario">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    placeholder="Ingresa tu contraseña">
            </div>

            <button type="submit">Entrar</button>
        </form>

        <div class="create-account">
            ¿No tienes cuenta? <a href="crear_usuario.php">Regístrate aquí</a>
        </div>
    </div>
</body>
</html>