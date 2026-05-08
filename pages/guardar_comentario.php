<?php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['ok' => false, 'error' => 'No autenticado']);
    exit;
}

$usuario_id = $_SESSION['user_id'];
// DESPUÉS
$planta_nombre = mysqli_real_escape_string($conn, trim($_POST['planta_id'] ?? ''));
$comentario    = trim($_POST['comentario'] ?? '');

if (empty($comentario) || empty($planta_nombre)) {
    echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
    exit;
}

// Buscar el ID numérico de la planta por su nombre
// DESPUÉS
$sql_planta = "SELECT id FROM Plantas WHERE LOWER(nombre_comun) = LOWER('$planta_nombre') LIMIT 1";
$res_planta = mysqli_query($conn, $sql_planta);
if (!$res_planta || mysqli_num_rows($res_planta) === 0) {
    echo json_encode(['ok' => false, 'error' => 'Planta no encontrada']);
    exit;
}
$planta_id = mysqli_fetch_assoc($res_planta)['id'];

$comentario_safe = mysqli_real_escape_string($conn, $comentario);
$sql = "INSERT INTO comentarios_plantas (usuario_id, planta_id, comentario, fecha) 
        VALUES ($usuario_id, $planta_id, '$comentario_safe', NOW())";

if (mysqli_query($conn, $sql)) {
    echo json_encode([
        'ok'     => true,
        'nombre' => $_SESSION['user_nombre'] . ' ' . $_SESSION['user_apellido'],
        'fecha'  => date('d/m/Y H:i')
    ]);
} else {
    echo json_encode(['ok' => false, 'error' => 'Error en base de datos']);
}
?>