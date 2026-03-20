<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'No autorizado']);
    exit;
}

// Obtener datos
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];

$password_actual = $data['password_actual'];
$password_nueva = $data['password_nueva'];

// Validaciones
if (strlen($password_nueva) < 6) {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'La nueva contraseña debe tener al menos 6 caracteres']);
    exit;
}

// Obtener contraseña actual del usuario
$sql = "SELECT password_hash FROM usuarios WHERE id = $user_id";
$resultado = mysqli_query($conn, $sql);
$usuario = mysqli_fetch_assoc($resultado);

// Verificar contraseña actual
if (!password_verify($password_actual, $usuario['password_hash'])) {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'Contraseña actual incorrecta']);
    exit;
}

// Actualizar contraseña
$nuevo_hash = password_hash($password_nueva, PASSWORD_DEFAULT);
$update_sql = "UPDATE usuarios SET password_hash = '$nuevo_hash' WHERE id = $user_id";

if (mysqli_query($conn, $update_sql)) {
    echo json_encode(['tipo' => 'success', 'mensaje' => 'Contraseña cambiada exitosamente']);
} else {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'Error al cambiar contraseña']);
}
?>