<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'No autorizado']);
    exit;
}

// Obtener datos JSON
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];

// Sanitizar datos
$nombre = mysqli_real_escape_string($conn, $data['nombre']);
$apellido = mysqli_real_escape_string($conn, $data['apellido']);
$email = mysqli_real_escape_string($conn, $data['email']);
$telefono = mysqli_real_escape_string($conn, $data['telefono']);
$idioma = mysqli_real_escape_string($conn, $data['idioma']);

// Validaciones
if (empty($nombre) || empty($apellido) || empty($email)) {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'Nombre, apellido y email son obligatorios']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'Email no válido']);
    exit;
}

// Verificar si el email ya está en uso por otro usuario
$check_sql = "SELECT id FROM usuarios WHERE email = '$email' AND id != $user_id";
$check_result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'Este email ya está siendo usado por otro usuario']);
    exit;
}

// Actualizar datos
$update_sql = "UPDATE usuarios SET 
               nombre = '$nombre',
               apellido = '$apellido',
               email = '$email',
               telefono = '$telefono',
               idioma = '$idioma'
               WHERE id = $user_id";

if (mysqli_query($conn, $update_sql)) {
    // Actualizar datos de sesión
    $_SESSION['user_nombre'] = $nombre;
    $_SESSION['user_apellido'] = $apellido;
    $_SESSION['user_email'] = $email;
    
    echo json_encode(['tipo' => 'success', 'mensaje' => 'Perfil actualizado correctamente']);
} else {
    echo json_encode(['tipo' => 'error', 'mensaje' => 'Error al actualizar: ' . mysqli_error($conn)]);
}
?>