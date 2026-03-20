<?php
// Configuración de conexión a MySQL
$host = 'localhost';
$usuario = 'root';
$contraseña = '';
$base_datos = 'medicina_ancestral';

// Crear conexión
$conn = mysqli_connect($host, $usuario, $contraseña, $base_datos);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Establecer caracteres UTF-8
mysqli_set_charset($conn, "utf8");

// Función para ejecutar consultas
function query($sql) {
    global $conn;
    $resultado = mysqli_query($conn, $sql);
    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conn));
    }
    return $resultado;
}
?>