<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Usuarios</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<?php
session_start();
if (!isset($_SESSION['tipo_usuario'])) {
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
    die("Conexión fallida: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = mysqli_real_escape_string($conn, $_POST['id_usuario']);
    
    $sql = "delete from usuarios where id_usuario='$id_usuario'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Usuario eliminado correctamente.'); window.location.href='buscar.php';</script>";
    } 
    else {
        echo "<script>alert('Error al eliminar el usuario.');</script>";
    }
}

$criterio = $_POST['criterio'] ?? '';
$busqueda = $_POST['busqueda'] ?? '';
$resultados = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar']) 
    && !empty($criterio) && !empty($busqueda)) {
    $sql = "select id_usuario, nombre, apellidos, dni, correo, tipo_usuario, saldo from usuarios where $criterio like '%$busqueda%'";
    $query_result = mysqli_query($conn, $sql);
    if ($query_result) {
        $resultados = mysqli_fetch_all($query_result, MYSQLI_ASSOC);
    }
}
mysqli_close($conn);
?>

<body>
    <h2>Buscar Usuario</h2>
    <form class="contenido" action="" method="POST">
        <label for="criterio">Buscar por:</label>
        <select name="criterio" required>
            <option value="nombre">Nombre</option>
            <option value="apellidos">Apellidos</option>
            <option value="dni">DNI</option>
            <option value="correo">Correo</option>
        </select>
        <input type="text" name="busqueda" required>
        <button type="submit" name="buscar">Buscar</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar'])) : ?>
        <h2 align="center">Resultados</h2>
        <?php if (!empty($resultados)) : ?>
            <table class="contenido" border="1">
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Correo</th>
                    <th>Tipo de Usuario</th>
                    <th>Saldo (€)</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($resultados as $usuario) : ?>
                    <tr>
                        <td align="center"><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($usuario['apellidos']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($usuario['dni']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($usuario['correo']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($usuario['tipo_usuario']); ?></td>
                        <td align="center"><?php echo number_format($usuario['saldo'], 2); ?> €</td>
                        <td align="center">
                            <a href="modificar.php?id_usuario=<?php echo htmlspecialchars($usuario['id_usuario']); ?>">Modificar</a> |
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($usuario['id_usuario']); ?>">
                                <button type="submit" onclick="return confirm('¿Seguro que deseas borrar este usuario?');">Borrar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p>No se encontraron resultados.</p>
        <?php endif; ?>
    <?php endif; ?>

    <footer class="footer">
        <p>© 2024. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
