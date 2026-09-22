<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
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


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = mysqli_real_escape_string($conn, $_POST['id_usuario']);
    
    $sql = "delete from usuarios where id_usuario='$id_usuario'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Usuario eliminado correctamente.'); window.location.href='listar.php';</script>";
    } 
    else {
        echo "<script>alert('Error al eliminar el usuario.');</script>";
    }
}

$sql = "select id_usuario, nombre, apellidos, dni, correo, tipo_usuario, saldo from usuarios";
$result = mysqli_query($conn, $sql);
?>


<body>
    <h2 align="center">Listado de Usuarios</h2>
    <table class="contenido" border="1">
        <tr>
            <th>Acciones</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>DNI</th>
            <th>Correo</th>
            <th>Tipo de Usuario</th>
            <th>Saldo (€)</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td align="center">
                    <a href="modificar.php?id_usuario=<?php echo $row['id_usuario']; ?>">Modificar</a> 
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario']; ?>">
                        <button type="submit" onclick="return confirm('¿Seguro que deseas borrar este usuario?');">Borrar</button>
                    </form>
                </td>
                <td align="center"><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td align="center"><?php echo htmlspecialchars($row['apellidos']); ?></td>
                <td align="center"><?php echo htmlspecialchars($row['dni']); ?></td>
                <td align="center"><?php echo htmlspecialchars($row['correo']); ?></td>
                <td align="center"><?php echo htmlspecialchars($row['tipo_usuario']); ?></td>
                <td align="center"><?php echo number_format($row['saldo'], 2); ?> €</td>
            </tr>
        <?php endwhile; ?>
    </table>
    <footer class="footer">
        <p>© 2024. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
