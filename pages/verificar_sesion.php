<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

$response = [
    'logged_in' => false,
    'nombre' => '',
    'apellido' => ''
];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT nombre, apellido FROM usuarios WHERE id = $user_id";
    $resultado = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        $response['logged_in'] = true;
        $response['nombre'] = $usuario['nombre'];
        $response['apellido'] = $usuario['apellido'];
    }
}

echo json_encode($response);
?>