<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['logged_in' => false]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT id, nombre, apellido, email, telefono, idioma FROM usuarios WHERE id = $user_id";
$resultado = mysqli_query($conn, $sql);

if (mysqli_num_rows($resultado) == 1) {
    $usuario = mysqli_fetch_assoc($resultado);
    $usuario['logged_in'] = true;
    echo json_encode($usuario);
} else {
    echo json_encode(['logged_in' => false]);
}
?>