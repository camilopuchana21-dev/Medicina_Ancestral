<?php
session_start();
require_once '../config/database.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../iniciar-sesion.html?error=' . urlencode('Debes iniciar sesión para contribuir'));
    exit;
}

// Verificar que se envió por POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../index.html');
    exit;
}

$user_id = $_SESSION['user_id'];
$titulo = trim($_POST['titulo'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$contenido = trim($_POST['contenido'] ?? '');
$referencias = trim($_POST['referencias'] ?? '');

// Validaciones
if (empty($titulo) || empty($categoria) || empty($descripcion) || empty($contenido)) {
    $mensaje = "Todos los campos marcados con * son obligatorios";
    $tipo = "error";
    header("Location: contribuir.html?mensaje=" . urlencode($mensaje) . "&tipo=" . $tipo);
    exit;
}

// Validar que la categoría sea válida
$categorias_validas = ['planta', 'remedio', 'historia', 'termino', 'experiencia'];
if (!in_array($categoria, $categorias_validas)) {
    $mensaje = "Categoría no válida";
    $tipo = "error";
    header("Location: contribuir.html?mensaje=" . urlencode($mensaje) . "&tipo=" . $tipo);
    exit;
}

// Sanitizar datos
$titulo = mysqli_real_escape_string($conn, $titulo);
$categoria = mysqli_real_escape_string($conn, $categoria);
$descripcion = mysqli_real_escape_string($conn, $descripcion);
$contenido = mysqli_real_escape_string($conn, $contenido);
$referencias = mysqli_real_escape_string($conn, $referencias);

// Crear tabla si no existe (por si acaso)
$sql_create = "CREATE TABLE IF NOT EXISTS contribuciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL,
    contenido TEXT NOT NULL,
    referencias TEXT,
    estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $sql_create);

// Insertar contribución
$sql = "INSERT INTO contribuciones (user_id, titulo, categoria, descripcion, contenido, referencias, estado) 
        VALUES ($user_id, '$titulo', '$categoria', '$descripcion', '$contenido', '$referencias', 'aprobado')";

if (mysqli_query($conn, $sql)) {
    $mensaje = "¡Gracias por tu contribución! Tu saber ha sido publicado y ya está disponible para la comunidad.";
    $tipo = "success";
} else {
    $mensaje = "Error al guardar: " . mysqli_error($conn);
    $tipo = "error";
}

header("Location: contribuir.html?mensaje=" . urlencode($mensaje) . "&tipo=" . $tipo);
exit;
?>