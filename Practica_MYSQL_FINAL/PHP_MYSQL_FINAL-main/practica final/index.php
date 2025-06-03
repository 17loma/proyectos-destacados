<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Concesionario - Inicio</title>
    <link rel="stylesheet" href="./index.css">
    <link rel="icon" type="image/png" href="./favicon.png">

</head>
<?php
session_start();


$usuario_autenticado = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : null;
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : null;

include './menu.php'; 
?>


<body>
    <header class="header">
        <h1 >Bienvenido a Concesionarios Loma</h1>
        <?php if ($usuario_autenticado) : ?>
            <h2 class="entrada">Hola, <?php echo htmlspecialchars($nombre_usuario); ?> </h2>
        <?php else : ?>
            <h2>Bienvenido, invitado. Por favor, inicie sesión para realizar acciones.</h2>
        <?php endif; ?>
    </header>

    <main class="contenido">
        <h2>Sistema de Gestión de Coches</h2>
        <p>En esta pagina podrás alquilar coches y también vender los tuyos</p>
        
        <ul>
            

            <?php if ($usuario_autenticado) : ?>
                <?php if ($usuario_autenticado === 'comprador') : ?>
                    <li><a href="alquilar.php">Alquilar Coches</a></li>
                <?php elseif ($usuario_autenticado === 'vendedor') : ?>
                    <li><a href="añadir.php">Registrar Coche</a></li>
                    <li><a href="modificar.php">Modificar Coche</a></li>
                    <li><a href="borrar.php">Borrar Coche</a></li>
                <?php elseif ($usuario_autenticado === 'admin') : ?>
                    <li><a href="gestionar.php">Gestionar Coches y Usuarios</a></li>
                <?php endif; ?>
            <?php else : ?>
                <li>Para realizar más acciones: <a href="login.php">Inicia sesión aqui</a></li>
                <li> Si no tienes cuenta, <a href="registro.php">Registrate aqui</a></li>
            <?php endif; ?>
        </ul>
        <br>
        <div align="center"> 
            <img src="COCHE.jpg" alt="Imagen de coches" width="800" >
        </div>
    </main>
    
    <footer class="footer">
        <p>© 2024. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
