<?php
session_start();
require_once '../config/database.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = "Por favor ingresa tu email y contraseña";
    } else {
        $email = mysqli_real_escape_string($conn, $email);
        
        $sql = "SELECT id, nombre, apellido, email, password_hash 
                FROM usuarios 
                WHERE email = '$email'";
        
        $resultado = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($resultado) == 1) {
            $usuario = mysqli_fetch_assoc($resultado);
            
            if (password_verify($password, $usuario['password_hash'])) {
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_nombre'] = $usuario['nombre'];
                $_SESSION['user_apellido'] = $usuario['apellido'];
                $_SESSION['user_email'] = $usuario['email'];
                
                header('Location: index.php');
                exit;
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "No existe una cuenta con este email";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Medicina Ancestral</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Mismos estilos que antes */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2c5f2d, #1a3a1a);
            min-height: 100vh;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            background: linear-gradient(to right, #2c5f2d, #4a7c4a);
            color: white;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }
        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .btn-auth {
            text-decoration: none;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .btn-login {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        .btn-login:hover {
            background: white;
            color: #2c5f2d;
        }
        .btn-register {
            background: white;
            color: #2c5f2d;
            border: 2px solid white;
        }
        .btn-register:hover {
            background: transparent;
            color: white;
        }
        .login-container {
            max-width: 400px;
            margin: 120px auto 50px;
            padding: 2.5rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header i {
            font-size: 3rem;
            color: #2c5f2d;
            margin-bottom: 1rem;
        }
        .login-header h1 {
            color: #2c5f2d;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #666;
        }
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            background: #fee;
            color: #c00;
            border: 1px solid #fcc;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #1a3a1a;
            font-weight: 600;
        }
        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
        }
        .form-group input:focus {
            border-color: #4a7c4a;
            outline: none;
        }
        .btn-login-submit {
            width: 100%;
            padding: 1rem;
            background: #2c5f2d;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-login-submit:hover {
            background: #4a7c4a;
        }
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e0e0e0;
        }
        .register-link a {
            color: #2c5f2d;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">🌿 Medicina Ancestral</div>
        <ul class="nav-menu">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="plantas.php">Plantas</a></li>
            <li><a href="#">Historia</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
        <div class="auth-buttons">
            <a href="iniciar-sesion.php" class="btn-auth btn-login">Iniciar Sesión</a>
            <a href="../registrarse.html" class="btn-auth btn-register">Registrarse</a>
        </div>
    </nav>

    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-leaf"></i>
            <h1>Bienvenido de vuelta</h1>
            <p>Ingresa a tu cuenta</p>
        </div>

        <?php if ($error): ?>
            <div class="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="iniciar-sesion.php">
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                       placeholder="tucorreo@ejemplo.com" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" 
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login-submit">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>

        <div class="register-link">
            ¿No tienes una cuenta? <a href="../registrarse.html">Regístrate aquí</a>
        </div>
    </div>
</body>
</html>