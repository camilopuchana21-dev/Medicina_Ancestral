<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$token = mysqli_real_escape_string($conn, $data['token']);
$password_nueva = $data['password'];

if (strlen($password_nueva) < 6) {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'La contraseña debe tener al menos 6 caracteres']);
    exit;
}

// Verificar token válido
$sql = "SELECT user_id, expiracion FROM recuperacion_passwords 
        WHERE token = '$token' AND usado = 0";
$resultado = mysqli_query($conn, $sql);

if (mysqli_num_rows($resultado) == 0) {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'Enlace inválido o ya utilizado']);
    exit;
}

$recuperacion = mysqli_fetch_assoc($resultado);
$expiracion = strtotime($recuperacion['expiracion']);
$ahora = time();

if ($ahora > $expiracion) {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'El enlace ha expirado. Solicita uno nuevo']);
    exit;
}

// Actualizar contraseña
$user_id = $recuperacion['user_id'];
$nuevo_hash = password_hash($password_nueva, PASSWORD_DEFAULT);
$update_sql = "UPDATE usuarios SET password_hash = '$nuevo_hash' WHERE id = $user_id";

if (mysqli_query($conn, $update_sql)) {
    // Marcar token como usado
    $update_token = "UPDATE recuperacion_passwords SET usado = 1 WHERE token = '$token'";
    mysqli_query($conn, $update_token);
    
    echo json_encode(['tipo' => 'success', 'mensaje' => 'Contraseña restablecida exitosamente']);
} else {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'Error al restablecer contraseña']);
}
?>