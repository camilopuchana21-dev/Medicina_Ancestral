<?php
session_start();
require_once '../config/database.php';

// Verificar que se envió por POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../registrarse.html');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validaciones
if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
    $mensaje = "Todos los campos son obligatorios";
    $tipo = "error";
} elseif ($password != $confirm_password) {
    $mensaje = "Las contraseñas no coinciden";
    $tipo = "error";
} elseif (strlen($password) < 6) {
    $mensaje = "La contraseña debe tener al menos 6 caracteres";
    $tipo = "error";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $mensaje = "El email no es válido";
    $tipo = "error";
} else {
    // Verificar si el email ya existe
    $email = mysqli_real_escape_string($conn, $email);
    $check_sql = "SELECT id FROM usuarios WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $mensaje = "Este email ya está registrado";
        $tipo = "error";
    } else {
        // Insertar nuevo usuario
        $nombre = mysqli_real_escape_string($conn, $nombre);
        $apellido = mysqli_real_escape_string($conn, $apellido);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $insert_sql = "INSERT INTO usuarios (nombre, apellido, email, password_hash, rol) 
                      VALUES ('$nombre', '$apellido', '$email', '$password_hash', 'usuario')";
        
        if (mysqli_query($conn, $insert_sql)) {
            // Obtener el ID del usuario recién creado
            $user_id = mysqli_insert_id($conn);
            
            // INICIAR SESIÓN AUTOMÁTICAMENTE
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_nombre'] = $nombre;
            $_SESSION['user_apellido'] = $apellido;
            $_SESSION['user_email'] = $email;
            
            // Redirigir directamente al index.html con sesión iniciada
            header('Location: ../index.html');
            exit;
        } else {
            $mensaje = "Error en la base de datos: " . mysqli_error($conn);
            $tipo = "error";
            header("Location: ../registrarse.html?mensaje=" . urlencode($mensaje) . "&tipo=" . $tipo);
            exit;
        }
    }
}

// Si hay error, redirigir al registro
header("Location: ../registrarse.html?mensaje=" . urlencode($mensaje) . "&tipo=" . $tipo);
exit;
?>