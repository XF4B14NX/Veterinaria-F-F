<?php
session_start();
include 'conexiones.php';

// 1. Función de error mejorada
function login_fallido($conexion, $mensaje, $stmt1 = null, $stmt2 = null) {
    $_SESSION['error_login'] = $mensaje; // Usamos el mensaje específico
    if ($stmt1) mysqli_stmt_close($stmt1);
    if ($stmt2) mysqli_stmt_close($stmt2);
    mysqli_close($conexion);
    header("Location: /veterinaria-f-f/Login.php");
    exit();
}

// 2. Verificamos que sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password_ingresada = $_POST['password'];

    // --- PASO A: BUSCAR EN CLIENTES (propietarios) ---
    $sql_cliente = "SELECT propietario_id, nombre, password FROM propietarios WHERE usuario = ?";
    $stmt_cliente = mysqli_prepare($conexion, $sql_cliente);
    mysqli_stmt_bind_param($stmt_cliente, "s", $usuario);
    mysqli_stmt_execute($stmt_cliente);
    $resultado_cliente = mysqli_stmt_get_result($stmt_cliente);

    if (mysqli_num_rows($resultado_cliente) == 1) {
        // --- Usuario SÍ existe en 'propietarios' ---
        $fila_cliente = mysqli_fetch_assoc($resultado_cliente);
        
        // Verificamos la contraseña
        if (password_verify($password_ingresada, $fila_cliente['password'])) {
            // ¡ÉXITO CLIENTE!
            $_SESSION['user_id'] = $fila_cliente['propietario_id'];
            $_SESSION['user_nombre'] = $fila_cliente['nombre'];
            $_SESSION['rol'] = 'cliente';
            header("Location: /veterinaria-f-f/perfil_cliente.php");
            exit();
        } else {
            // Error: Contraseña incorrecta
            login_fallido($conexion, "La contraseña es incorrecta.", $stmt_cliente);
        }

    } else {
        // --- Usuario NO existe en 'propietarios', BUSCAMOS EN 'personal' ---
        $sql_personal = "SELECT personal_id, nombre, especialidad, password FROM personal WHERE usuario = ?";
        $stmt_personal = mysqli_prepare($conexion, $sql_personal);
        mysqli_stmt_bind_param($stmt_personal, "s", $usuario);
        mysqli_stmt_execute($stmt_personal);
        $resultado_personal = mysqli_stmt_get_result($stmt_personal);

        if (mysqli_num_rows($resultado_personal) == 1) {
            // --- Usuario SÍ existe en 'personal' ---
            $fila_personal = mysqli_fetch_assoc($resultado_personal);

            // Verificamos la contraseña
            if (password_verify($password_ingresada, $fila_personal['password'])) {
                // ¡ÉXITO PERSONAL!
                $_SESSION['user_id'] = $fila_personal['personal_id'];
                $_SESSION['user_nombre'] = $fila_personal['nombre'];
                $_SESSION['rol'] = $fila_personal['especialidad'];
                header("Location: /veterinaria-f-f/listadoclientes.php");
                exit();
            } else {
                // Error: Contraseña incorrecta
                login_fallido($conexion, "La contraseña es incorrecta.", $stmt_cliente, $stmt_personal);
            }

        } else {
            // --- Usuario NO existe en NINGUNA tabla ---
            // Error: Cuenta inexistente
            login_fallido($conexion, "Cuenta inexistente.", $stmt_cliente, $stmt_personal);
        }
    }
}
?>