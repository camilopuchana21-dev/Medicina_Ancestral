<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

$sql = "SELECT c.*, u.nombre, u.apellido 
        FROM contribuciones c
        JOIN usuarios u ON c.user_id = u.id
        WHERE c.id = $id AND c.estado = 'aprobado'";

$resultado = mysqli_query($conn, $sql);

if (mysqli_num_rows($resultado) == 0) {
    echo json_encode(['error' => 'Saber no encontrado']);
    exit;
}

$row = mysqli_fetch_assoc($resultado);

echo json_encode([
    'id' => $row['id'],
    'titulo' => $row['titulo'],
    'descripcion' => $row['descripcion'],
    'contenido' => $row['contenido'],
    'categoria' => $row['categoria'],
    'autor' => $row['nombre'] . ' ' . $row['apellido'],
    'fecha' => date('d/m/Y', strtotime($row['fecha_creacion'])),
    'referencias' => $row['referencias']
]);
?>
