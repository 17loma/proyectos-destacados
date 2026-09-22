<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Coches</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" type="image/png" href="../favicon.png">

</head>
<?php
session_start();
require_once __DIR__ . '/../config.php';


$usuario_autenticado = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : null;


if (!isset($_SESSION['tipo_usuario']) && ($_SERVER['REQUEST_URI'] == '/añadir.php' || $_SERVER['REQUEST_URI'] == '/modificar.php' || $_SERVER['REQUEST_URI'] == '/borrar.php')) {
    header('Location: login.php');
    exit;
}

include '../menu.php';
?>


<body>
    <h2>Gestión de Coches</h2>
    <p>Selecciona una opción:</p>
    
    <ul>
        <li><a href="listar.php">Listar Coches</a></li>
        <li><a href="buscar.php">Buscar Coches</a></li>

        <?php if (isset($_SESSION['tipo_usuario']) && ($_SESSION['tipo_usuario'] === 'admin' || $_SESSION['tipo_usuario'] === 'vendedor')) : ?>
            <li><a href="añadir.php">Registrar Coche</a></li>
            <li><a href="modificar.php">Modificar Coche</a></li>
            <li><a href="borrar.php">Borrar Coche</a></li>
        <?php endif; ?>
    </ul>
    <footer class="footer">
        <p>© 2024. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
