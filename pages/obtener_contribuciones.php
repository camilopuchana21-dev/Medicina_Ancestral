<?php
header('Content-Type: application/json');
require_once '../config/database.php';

// Obtener solo contribuciones aprobadas
$sql = "SELECT c.*, u.nombre, u.apellido 
        FROM contribuciones c
        JOIN usuarios u ON c.user_id = u.id
        WHERE c.estado = 'aprobado'
        ORDER BY c.fecha_creacion DESC";

$resultado = mysqli_query($conn, $sql);
$contribuciones = [];

while ($row = mysqli_fetch_assoc($resultado)) {
    $contribuciones[] = [
        'id' => $row['id'],
        'titulo' => $row['titulo'],
        'descripcion' => $row['descripcion'],
        'categoria' => $row['categoria'],
        'autor' => $row['nombre'] . ' ' . $row['apellido'],
        'fecha' => date('d/m/Y', strtotime($row['fecha_creacion']))
    ];
}

echo json_encode($contribuciones);
?>