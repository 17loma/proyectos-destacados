<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Borrar Usuarios</title>
        <link rel="stylesheet" href="../index.css">
        <link rel="icon" type="image/png" href="../favicon.png">
    </head>
    <?php
    session_start();
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
        header("Location: ../login.php");
        exit();
    }

    include '../menu.php';

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "concesionario";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $mensaje = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuarios'])) {
        $usuariosSeleccionados = $_POST['usuarios'];
        $usuariosLista = implode("','", array_map('mysqli_real_escape_string', array_fill(0, count($usuariosSeleccionados), $conn), $usuariosSeleccionados));
        
        $sql = "delete from usuarios where correo in ('$usuariosLista')";
        if (mysqli_query($conn, $sql)) {
            $mensaje = "Usuarios eliminados correctamente.";
        } else {
            $mensaje = "Error al eliminar los usuarios.";
        }
    }

    $sql = "select nombre, apellidos, dni, correo from usuarios";
    $result = mysqli_query($conn, $sql);
    $usuarios = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_close($conn);
    ?>

        <body>
        <h2 align="center" >Borrar Usuarios</h2>
        <?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>
        <form action="" method="post">
            <table class="contenido" border="1">
                <tr>
                    <th>Seleccionar</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Correo</th>
                    
                </tr>
                <?php foreach ($usuarios as $usuario) : ?>
                    <tr>
                        <td align="center"><input type="checkbox" name="usuarios[]" value="<?php echo htmlspecialchars($usuario['correo']); ?>"></td>
                        <td align="center"><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($usuario['apellidos']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($usuario['dni']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($usuario['correo']); ?></td>
                    </tr> 
                <?php endforeach; ?>
            </table> <br>
            <button type="submit" style="display: block; margin: 0 auto;" onclick="return confirm('¿Seguro que deseas eliminar los usuarios seleccionados?');">Eliminar Seleccionados</button>
        </form>
        
        <footer class="footer">
        <p>© 2024. Todos los derechos reservados.</p>
    </footer>
    </body>
    </html>