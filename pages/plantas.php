<?php
require_once '../config/database.php';

// Consultar todas las plantas
$sql = "SELECT * FROM plantas ORDER BY nombre_comun";
$resultado = query($sql);
$plantas = fetch_all($resultado);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todas las Plantas - Medicina Ancestral</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">🌿 Medicina Ancestral</div>
        <ul class="nav-menu">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="plantas.php" class="active">Plantas</a></li>
            <li><a href="#">Historia</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
        <div class="auth-buttons">
            <a href="#" class="btn-auth btn-login">Iniciar Sesión</a>
            <a href="#" class="btn-auth btn-register">Registrarse</a>
        </div>
    </nav>

    <main class="plantas-page">
        <div class="plantas-header">
            <h1>🌱 Plantas Medicinales</h1>
            <p>Conoce nuestra colección completa de plantas sagradas</p>
        </div>

        <div class="plantas-grid-completo">
            <?php foreach($plantas as $planta): 
                // Consultar propiedades
                $sql_prop = "SELECT p.nombre FROM propiedades p 
                            JOIN planta_propiedades pp ON p.id = pp.propiedad_id 
                            WHERE pp.planta_id = " . $planta['id'] . " LIMIT 3";
                $resultado_prop = query($sql_prop);
                $propiedades = fetch_all($resultado_prop);
            ?>
                <div class="plant-card">
                    <div class="plant-image">
                        <img src="<?php echo $planta['imagen_principal'] ?? 'https://images.unsplash.com/photo-1589917991891-3d3f9b3b7e5a'; ?>" alt="<?php echo $planta['nombre_comun']; ?>">
                    </div>
                    <div class="plant-content">
                        <h3><?php echo $planta['nombre_comun']; ?></h3>
                        <p class="scientific-name"><?php echo $planta['nombre_cientifico']; ?></p>
                        <div class="rating">⭐ <?php echo $planta['rating'] ?? '4.5'; ?></div>
                        <div class="plant-tags">
                            <?php foreach($propiedades as $prop): ?>
                                <span class="tag"><?php echo $prop['nombre']; ?></span>
                            <?php endforeach; ?>
                        </div>
                        <a href="#" class="btn-more">Ver más información →</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <!-- Mismo footer que index.php -->
    </footer>
</body>
</html>