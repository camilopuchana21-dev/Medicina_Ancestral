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
        <a href="registrarse.php" class="btn-auth btn-register">
            <i class="fas fa-user-plus"></i> Registrarse
        </a>
    <?php endif; ?>
</div>