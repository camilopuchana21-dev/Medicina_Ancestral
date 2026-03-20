<?php
session_start();
require_once '../config/database.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicina Ancestral</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">🌿 Medicina Ancestral</div>
        <ul class="nav-menu">
            <li><a href="index.php" class="active">Inicio</a></li>
            <li><a href="plantas.php">Plantas</a></li>
            <li><a href="#">Historia</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
        <div class="auth-buttons">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span class="user-name" style="color: white; margin-right: 1rem;">
                    <i class="fas fa-user"></i> 
                    <?php echo $_SESSION['user_nombre'] . ' ' . $_SESSION['user_apellido']; ?>
                </span>
                <a href="cerrar-sesion.php" class="btn-auth btn-login">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            <?php else: ?>
                <a href="iniciar-sesion.php" class="btn-auth btn-login">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
                <a href="../registrarse.html" class="btn-auth btn-register">
                    <i class="fas fa-user-plus"></i> Registrarse
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- EL RESTO DE TU CONTENIDO DE index.php -->
    <!-- (Mantén todo lo que ya tenías) -->
    
</body>
</html>