<?php
include('config.php');  // Asegúrate de que esta línea esté presente

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario_autenticado = isset($_SESSION['tipo_usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú de Navegación</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="menu">
        <ul>
            <li><a href="<?php echo BASE_URL; ?>index.php">Inicio</a></li>
            <li><a href="<?php echo BASE_URL; ?>coches/index.php">Coches</a></li>

            <?php if ($usuario_autenticado) : ?>
                <?php if (in_array($_SESSION['tipo_usuario'], ['vendedor', 'admin'])) : ?>
                    <li><a href="<?php echo BASE_URL; ?>usuarios/index.php">Usuarios</a></li>
                    <li><a href="<?php echo BASE_URL; ?>alquileres/index.php">Alquileres</a></li>
                <?php endif; ?>
                <li><a href="<?php echo BASE_URL; ?>logout.php">Cerrar Sesión</a></li>
            <?php else : ?>
                <li><a href="<?php echo BASE_URL; ?>login.php">Iniciar Sesión</a></li>
                <li><a href="<?php echo BASE_URL; ?>registro.php">Registrarse</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</body>
</html>

