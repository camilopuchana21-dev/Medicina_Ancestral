<?php
require_once '../config/database.php';

echo "<h2>Prueba de conexión a la base de datos</h2>";

// Probar conexión
if ($conn) {
    echo "✅ Conexión exitosa a la base de datos<br>";
} else {
    echo "❌ Error de conexión<br>";
}

// Probar inserción directa
$test_sql = "INSERT INTO usuarios (nombre, apellido, email, password_hash, rol) 
             VALUES ('Test', 'User', 'test" . time() . "@test.com', '" . password_hash('123456', PASSWORD_DEFAULT) . "', 'usuario')";

echo "<br>Ejecutando: " . $test_sql . "<br>";

if (mysqli_query($conn, $test_sql)) {
    echo "✅ Inserción exitosa<br>";
    
    // Mostrar los datos insertados
    $result = mysqli_query($conn, "SELECT * FROM usuarios ORDER BY id DESC LIMIT 1");
    $user = mysqli_fetch_assoc($result);
    echo "<pre>";
    print_r($user);
    echo "</pre>";
} else {
    echo "❌ Error en inserción: " . mysqli_error($conn) . "<br>";
}
?>