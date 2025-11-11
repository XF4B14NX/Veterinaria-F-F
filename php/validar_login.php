<?php
session_start();
include 'conexiones.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password_ingresada = $_POST['password'];

    // --- BUSCAR EN CLIENTES ---
    $sql_cliente = "SELECT propietario_id, nombre, password FROM propietarios WHERE usuario = ?";
    $stmt_cliente = mysqli_prepare($conexion, $sql_cliente);
    mysqli_stmt_bind_param($stmt_cliente, "s", $usuario);
    mysqli_stmt_execute($stmt_cliente);
    $resultado_cliente = mysqli_stmt_get_result($stmt_cliente);

    if (mysqli_num_rows($resultado_cliente) == 1) {
        $fila_cliente = mysqli_fetch_assoc($resultado_cliente);
        
        if (password_verify($password_ingresada, $fila_cliente['password'])) {
            $_SESSION['user_id'] = $fila_cliente['propietario_id'];
            $_SESSION['user_nombre'] = $fila_cliente['nombre'];
            $_SESSION['rol'] = 'cliente';

            // ¡CAMBIO AQUÍ! Ruta absoluta
            header("Location: /veterinaria-f-f/perfil_cliente.php");
            exit();
        } else {
            login_fallido($conexion, $stmt_cliente);
        }
    } else {
        // --- BUSCAR EN PERSONAL ---
        $sql_personal = "SELECT personal_id, nombre, especialidad, password FROM personal WHERE usuario = ?";
        $stmt_personal = mysqli_prepare($conexion, $sql_personal);
        mysqli_stmt_bind_param($stmt_personal, "s", $usuario);
        mysqli_stmt_execute($stmt_personal);
        $resultado_personal = mysqli_stmt_get_result($stmt_personal);

        if (mysqli_num_rows($resultado_personal) == 1) {
            $fila_personal = mysqli_fetch_assoc($resultado_personal);

            if (password_verify($password_ingresada, $fila_personal['password'])) {
                $_SESSION['user_id'] = $fila_personal['personal_id'];
                $_SESSION['user_nombre'] = $fila_personal['nombre'];
                $_SESSION['rol'] = $fila_personal['especialidad'];

                // ¡CAMBIO AQUÍ! Ruta absoluta
                header("Location: /veterinaria-f-f/listadoclientes.php");
                exit();
            } else {
                login_fallido($conexion, $stmt_cliente, $stmt_personal);
            }
        } else {
            login_fallido($conexion, $stmt_cliente, $stmt_personal);
        }
    }
}

function login_fallido($conexion, $stmt1 = null, $stmt2 = null) {
    $_SESSION['error_login'] = "Usuario o contraseña incorrectos.";
    if ($stmt1) mysqli_stmt_close($stmt1);
    if ($stmt2) mysqli_stmt_close($stmt2);
    mysqli_close($conexion);
    
    // ¡CAMBIO AQUÍ! Ruta absoluta
    header("Location: /veterinaria-f-f/Login.php");
    exit();
}
?>