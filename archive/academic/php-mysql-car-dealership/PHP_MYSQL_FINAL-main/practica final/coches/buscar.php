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

include '../menu.php';

$usuario_autenticado = isset($_SESSION['tipo_usuario']);
$id_usuario_actual = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "concesionario";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$criterio = $_POST['criterio'] ?? '';
$busqueda = $_POST['busqueda'] ?? '';
$resultados = [];
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar']) && !empty($criterio) && !empty($busqueda)) {
    $criterio = mysqli_real_escape_string($conn, $criterio);
    $busqueda = mysqli_real_escape_string($conn, $busqueda);
    
    $sql = "select * from coches where $criterio like '%$busqueda%'";

    if ($usuario_autenticado && $_SESSION['tipo_usuario'] == 'comprador') {
        $sql .= " and alquilado = 0"; 
    }

    $query_result = mysqli_query($conn, $sql);
    if ($query_result) {
        $resultados = mysqli_fetch_all($query_result, MYSQLI_ASSOC);
    }
    
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['alquilar']) && isset($_POST['id_coche'])) {
    $id_coche = intval($_POST['id_coche']);
    $fecha_actual = date('d-m-Y H:i:s');
    
    $sql_alquilar = "insert into alquileres (id_coche, id_usuario, prestado) values ($id_coche, $id_usuario_actual, '$fecha_actual')";
    $sql_actualizar_estado = "update coches set alquilado = 1 where id_coche = $id_coche";
    
    if (mysqli_query($conn, $sql_alquilar) && mysqli_query($conn, $sql_actualizar_estado)) {
        $mensaje = "<p style='color:green;'>Coche alquilado correctamente.</p>";
    } 
    else {
        $mensaje = "<p style='color:red;'>Error al alquilar el coche.</p>";
    }
    
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar']) && isset($_POST['id_coche'])) {
    $id_coche = intval($_POST['id_coche']);
    $sql_obtener_propietario = "select id_usuario from coches where id_coche = $id_coche";
    $result = mysqli_query($conn, $sql_obtener_propietario);
    $propietario = mysqli_fetch_assoc($result);
    $propietario_id = $propietario['id_usuario'];

    if ($usuario_autenticado && ($id_usuario_actual == $propietario_id || $_SESSION['tipo_usuario'] == 'admin')) {
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

mysqli_close($conn);
?>

<body>
    <h2>Formulario de Búsqueda</h2>
    <form class="contenido" action="" method="POST">
        <label for="criterio">Buscar por:</label>
        <select name="criterio" required>
            <option value="modelo">Modelo</option>
            <option value="marca">Marca</option>
            <option value="color">Color</option>
        </select>
        <input type="text" name="busqueda" required>
        <button type="submit" name="buscar">Buscar</button>
    </form>
    <?php if (!empty($mensaje)) echo $mensaje; ?>
    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar'])) : ?>
        <h2 align="center">Resultados de la búsqueda</h2>
        <?php if (!empty($resultados)) : ?>
            <table class="contenido" border="1">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Precio (€)</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $coche) : ?>
                        <tr>
                            <td align="center">
                                <?php 
                                    $ruta_foto = "../coches/fotos/" . basename(htmlspecialchars($coche['foto']));
                                    if (!empty($coche['foto']) && file_exists(__DIR__ . "/fotos/" . basename($coche['foto']))) {
                                        echo "<img src='$ruta_foto' alt='Foto del coche' width='100'>";
                                    } 
                                    else {
                                        echo "No disponible";
                                    }
                                ?>
                            </td>
                            <td align="center"><?php echo htmlspecialchars($coche['marca']); ?></td>
                            <td align="center"><?php echo htmlspecialchars($coche['modelo']); ?></td>
                            <td align="center"><?php echo htmlspecialchars($coche['precio']); ?> €</td>
                            <td align="center"><?php echo $coche['alquilado'] == 0 ? 'Disponible' : 'Alquilado'; ?></td>
                            <td align="center">
                                <?php if ($_SESSION['tipo_usuario'] == 'comprador' && $coche['alquilado'] == 0) : ?>
                                    <form method="POST">
                                        <input type="hidden" name="id_coche" value="<?php echo htmlspecialchars($coche['id_coche']); ?>">
                                        <button type="submit" name="alquilar">Alquilar</button>
                                    </form>
                                <?php elseif ($_SESSION['tipo_usuario'] == 'vendedor' && $coche['id_usuario'] == $id_usuario_actual || $_SESSION['tipo_usuario'] == 'admin') : ?>
                                    <form method="POST">
                                        <input type="hidden" name="id_coche" value="<?php echo htmlspecialchars($coche['id_coche']); ?>">
                                        <button type="submit" name="eliminar" onclick="return confirm('¿Seguro que deseas eliminar este coche?');">Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No se encontraron resultados.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>