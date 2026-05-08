<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$nombre = trim($_GET['planta'] ?? '');
if (empty($nombre)) {
    echo json_encode(['ok' => false]);
    exit;
}

$nombre = mysqli_real_escape_string($conn, $nombre);

// Busca por nombre exacto O por nombre sin tildes
$sql = "SELECT * FROM plantas WHERE 
        LOWER(nombre_comun) = LOWER('$nombre') 
        OR LOWER(CONVERT(nombre_comun USING ascii)) = LOWER('$nombre')
        LIMIT 1";
$resultado = mysqli_query($conn, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    echo json_encode(['ok' => true, 'planta' => mysqli_fetch_assoc($resultado)]);
} else {
    echo json_encode(['ok' => false]);
}
?>