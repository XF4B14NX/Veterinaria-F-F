<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veterinaria F&F - Crear Usuario</title>
    
    <style>
        /* (Tu CSS existente va aquí) */
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
        .register-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }
        h1 { text-align: center; margin-bottom: 10px; color: #333; }
        p { text-align: center; color: #666; font-size: 14px; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
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
            margin-top: 10px;
        }
        button:hover { background-color: #8fc1ea; }
        .back-to-login { text-align: center; margin-top: 20px; font-size: 14px; color: #666; }
        .back-to-login a { color: #3c74a3; text-decoration: none; font-weight: bold; }
    </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veterinaria F&F - Crear Usuario</title>
    <style>
        /* (Tu CSS va aquí) */
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f0f0f0; }
        .register-container { background-color: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 450px; }
        h1 { text-align: center; margin-bottom: 10px; color: #333; }
        p { text-align: center; color: #666; font-size: 14px; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"], input[type="tel"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        button { width: 100%; padding: 12px; background-color: #3c74a3; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; margin-top: 10px; }
        button:hover { background-color: #8fc1ea; }
        .back-to-login { text-align: center; margin-top: 20px; font-size: 14px; color: #666; }
        .back-to-login a { color: #3c74a3; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Crear Usuario</h1>
        <p>Un clic más cerca de tus pacientes peludos: ingresa a la Agenda Veterinaria</p>
        
        <form action="php/registrar_usuario.php" method="POST">
            
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios" placeholder="Ingresa tu nombre completo">
            </div>
            <div class="form-group">
                <label for="rut">RUT</label>
                <input type="text" id="rut" name="rut" required placeholder="12345678-9">
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" required placeholder="ejemplo@correo.com">
            </div>
            <div class="form-group">
                <label for="telefono">Número de Teléfono</label>
                <input type="tel" id="telefono" name="telefono" required placeholder="912345678">
            </div>
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" required placeholder="Elige un nombre de usuario">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="Crea una contraseña">
            </div>
            <button type="submit">Crear Usuario</button>
        </form>

        <div class="back-to-login">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>