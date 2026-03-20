<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../iniciar-sesion.html');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $error = "Por favor ingresa tu email y contraseña";
    header("Location: ../iniciar-sesion.html?error=" . urlencode($error));
    exit;
}

$email = mysqli_real_escape_string($conn, $email);
$sql = "SELECT id, nombre, apellido, email, password_hash FROM usuarios WHERE email = '$email'";
$resultado = mysqli_query($conn, $sql);

if (mysqli_num_rows($resultado) == 1) {
    $usuario = mysqli_fetch_assoc($resultado);
    
    if (password_verify($password, $usuario['password_hash'])) {
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_nombre'] = $usuario['nombre'];
        $_SESSION['user_apellido'] = $usuario['apellido'];
        $_SESSION['user_email'] = $usuario['email'];
        
        header('Location: ../index.html');
        exit;
    } else {
        $error = "Contraseña incorrecta";
    }
} else {
    $error = "No existe una cuenta con este email";
}

header("Location: ../iniciar-sesion.html?error=" . urlencode($error));
exit;
?>