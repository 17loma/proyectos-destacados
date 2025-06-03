<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Coches</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<?php
session_start();
require_once __DIR__ . '/../config.php';

$usuario_autenticado = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : null;
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

include '../menu.php';

$conn = mysqli_connect("localhost", "root", "root", "concesionario");
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['alquilar']) && isset($_POST['id_coche'])) {
    $id_coche = intval($_POST['id_coche']);
    $fecha_actual = date('Y-m-d H:i:s');
    
    $sql_alquilar = "insert into alquileres (id_coche, id_usuario, prestado) values ($id_coche, $id_usuario, '$fecha_actual')";
    $sql_actualizar_estado = "update coches set alquilado = 1 where id_coche = $id_coche";
    
    if (mysqli_query($conn, $sql_alquilar) && mysqli_query($conn, $sql_actualizar_estado)) {
        $mensaje = "<p style='color:green;'>✅ Coche alquilado correctamente.</p>";
    } 
    else {
        $mensaje = "<p style='color:red;'>❌ Error al alquilar el coche.</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar']) && isset($_POST['id_coche'])) {
    $id_coche = intval($_POST['id_coche']);
    
    $sql_propietario = "select id_usuario from coches where id_coche = $id_coche";
    $result_propietario = mysqli_query($conn, $sql_propietario);
    $row_propietario = mysqli_fetch_assoc($result_propietario);
    $propietario_id = $row_propietario['id_usuario'];
    
    if ($usuario_autenticado && ($id_usuario == $propietario_id || $_SESSION['tipo_usuario'] == 'admin')) {
        $sql_borrar_alquileres = "delete from alquileres where id_coche = $id_coche";
        mysqli_query($conn, $sql_borrar_alquileres);

        $sql_borrar_coche = "delete from coches where id_coche = $id_coche";
        if (mysqli_query($conn, $sql_borrar_coche)) {
            $mensaje = "<p style='color:green;'>✅ Coche eliminado correctamente.</p>";
        } 
        else {
            $mensaje = "<p style='color:red;'>❌ Error al eliminar el coche.</p>";
        }
    } 
    else {
        $mensaje = "<p style='color:red;'>❌ No tienes permiso para eliminar este coche.</p>";
    }
}

$sql = "select * from coches";
$result = mysqli_query($conn, $sql);
$total_coches = mysqli_num_rows($result);
?>

<body>
    <h1 align="center">Listado de Coches</h1>
    
    <?php if (!empty($mensaje)) echo $mensaje; ?>
    
    <?php if ($total_coches > 0) : ?>
        <table class="contenido" border="1">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Precio (€)</th>
                    <th>Estado</th>
                    <?php if ($usuario_autenticado) : ?>
                        <th>Acción</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td align="center">
                            <?php 
                                $ruta_foto = "../coches/fotos/" . basename(htmlspecialchars($row['foto']));
                                if (!empty($row['foto']) && file_exists(__DIR__ . "/fotos/" . basename($row['foto']))) {
                                    echo "<img src='$ruta_foto' alt='Foto del coche' width='100'>";
                                } 
                                else {
                                    echo "No disponible";
                                }
                            ?>
                        </td>
                        <td align="center"><?php echo htmlspecialchars($row['modelo']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($row['marca']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($row['precio']); ?> €</td>
                        <td align="center"><?php echo $row['alquilado'] == 0 ? 'Disponible' : 'Alquilado'; ?></td>
                        <?php if ($usuario_autenticado) : ?>
                            <td align="center">
                                <?php if ($_SESSION['tipo_usuario'] == 'comprador' && $row['alquilado'] == 0) : ?>
                                    <form method="POST">
                                        <input type="hidden" name="id_coche" value="<?php echo htmlspecialchars($row['id_coche']); ?>">
                                        <button type="submit" name="alquilar">Alquilar</button>
                                    </form>
                                <?php elseif (($_SESSION['tipo_usuario'] == 'vendedor' && $row['id_usuario'] == $id_usuario) || $_SESSION['tipo_usuario'] == 'admin') : ?>
                                    <form method="POST">
                                        <input type="hidden" name="id_coche" value="<?php echo htmlspecialchars($row['id_coche']); ?>">
                                        <button type="submit" name="eliminar" onclick="return confirm('¿Seguro que deseas eliminar este coche?');">Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p style="color: red;">❌ No hay coches actualmente disponibles.</p>
    <?php endif; ?>
    <footer class="footer">
        <p>© 2024. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
