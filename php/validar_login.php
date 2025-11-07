<?php
// 1. Iniciamos la sesión (OBLIGATORIO para usar $_SESSION)
session_start();

// 2. Incluimos la conexión
include 'conexiones.php';

// 3. Verificamos que se haya enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 4. Obtenemos el usuario y la contraseña del formulario
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password_ingresada = $_POST['password'];

    // --- PASO A: BUSCAR EN CLIENTES (propietarios) ---
    
    // 5. Preparamos la consulta SQL (Buscamos en la tabla 'propietarios')
    $sql_cliente = "SELECT propietario_id, nombre, password FROM propietarios WHERE usuario = ?";
    $stmt_cliente = mysqli_prepare($conexion, $sql_cliente);
    mysqli_stmt_bind_param($stmt_cliente, "s", $usuario);
    mysqli_stmt_execute($stmt_cliente);
    $resultado_cliente = mysqli_stmt_get_result($stmt_cliente);

    // 6. Verificamos si se encontró un CLIENTE
    if (mysqli_num_rows($resultado_cliente) == 1) {
        
        $fila_cliente = mysqli_fetch_assoc($resultado_cliente);
        
        // 7. Verificamos la contraseña encriptada del CLIENTE
        if (password_verify($password_ingresada, $fila_cliente['password'])) {
            
            // ¡ÉXITO! Es un cliente.
            // 8. "Recordamos" al usuario guardando sus datos en la SESIÓN
            $_SESSION['user_id'] = $fila_cliente['propietario_id'];
            $_SESSION['user_nombre'] = $fila_cliente['nombre'];
            $_SESSION['rol'] = 'cliente'; // Definimos su rol

            // 9. Redirigimos al perfil del cliente (perfil_cliente.php)
            header("Location: perfil_cliente.php");
            exit(); // Detenemos el script

        } else {
            // Contraseña incorrecta (para el cliente encontrado)
            login_fallido($conexion, $stmt_cliente);
        }

    } else {
        
        // --- PASO B: BUSCAR EN PERSONAL (si no es cliente) ---
        // (Asegúrate que tu tabla 'personal' tenga 'usuario' y 'password' como en 'propietarios')
        
        $sql_personal = "SELECT personal_id, nombre, especialidad, password FROM personal WHERE usuario = ?";
        $stmt_personal = mysqli_prepare($conexion, $sql_personal);
        mysqli_stmt_bind_param($stmt_personal, "s", $usuario);
        mysqli_stmt_execute($stmt_personal);
        $resultado_personal = mysqli_stmt_get_result($stmt_personal);

        if (mysqli_num_rows($resultado_personal) == 1) {
            
            $fila_personal = mysqli_fetch_assoc($resultado_personal);

            // 7. Verificamos la contraseña encriptada del PERSONAL
            if (password_verify($password_ingresada, $fila_personal['password'])) {
                
                // ¡ÉXITO! Es personal de la clínica.
                $_SESSION['user_id'] = $fila_personal['personal_id'];
                $_SESSION['user_nombre'] = $fila_personal['nombre'];
                $_SESSION['rol'] = $fila_personal['especialidad']; // Ej. "Administrador", "Veterinario"

                // 9. Redirigimos al panel de admin (listadoclientes.php)
                header("Location: listadoclientes.php");
                exit();

            } else {
                // Contraseña incorrecta (para el personal encontrado)
                login_fallido($conexion, $stmt_cliente, $stmt_personal);
            }

        } else {
            // Error Final: El usuario no existe en NINGUNA tabla
            login_fallido($conexion, $stmt_cliente, $stmt_personal);
        }
    }
}

// Función para manejar un login fallido
function login_fallido($conexion, $stmt1 = null, $stmt2 = null) {
    // 10. Enviamos un mensaje de error de vuelta al login.php
    $_SESSION['error_login'] = "Usuario o contraseña incorrectos.";
    
    // 11. Cerramos todo
    if ($stmt1) mysqli_stmt_close($stmt1);
    if ($stmt2) mysqli_stmt_close($stmt2);
    mysqli_close($conexion);
    
    // 12. Redirigimos de vuelta al login
    header("Location: login.php");
    exit();
}
?>