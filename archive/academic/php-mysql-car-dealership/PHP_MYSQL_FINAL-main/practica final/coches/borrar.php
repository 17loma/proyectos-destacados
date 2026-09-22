<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar y Borrar Coches</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<?php 
session_start();
require_once __DIR__ . '/../config.php';

$usuario_autenticado = isset($_SESSION['tipo_usuario']);
$id_usuario_actual = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

include '../menu.php';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar']) && isset($_POST['coches'])) {
    if (!$usuario_autenticado || !in_array($_SESSION['tipo_usuario'], ['admin', 'vendedor'])) {
        die("No tienes permisos para eliminar coches.");
    }

    $cochesSeleccionados = array_map('intval', $_POST['coches']);
    
    if ($_SESSION['tipo_usuario'] == 'vendedor') {
        $cochesSeleccionados = array_filter($cochesSeleccionados, function($id_coche) use ($conn, $id_usuario_actual) {
            $sql_verificar = "select id_usuario from coches where id_coche = $id_coche";
            $resultado_verificacion = mysqli_query($conn, $sql_verificar);
            $datos_coche = mysqli_fetch_assoc($resultado_verificacion);
            return $datos_coche['id_usuario'] == $id_usuario_actual;
        });
    }

    if (!empty($cochesSeleccionados)) {
        $cochesLista = implode(",", $cochesSeleccionados);
        $sql = "delete from coches where id_coche in ($cochesLista)";
        if (mysqli_query($conn, $sql)) {
            $mensaje = "<p style='color:green;'>Coches eliminados correctamente.</p>";
        } 
        else {
            $mensaje = "<p style='color:red;'>Error al eliminar los coches.</p>";
        }
    } 
    else {
        $mensaje = "<p style='color:red;'>No tienes permiso para eliminar este coche.</p>";
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
        <?php if (!empty($resultados)) : ?>
            <h2 align="center">Resultados de la búsqueda</h2>
            <form action="" method="POST">
                <table class="contenido" border="1">
                    <tr>
                        <th>Foto</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Color</th>
                        <th>Precio (€)</th>
                        <?php if ($usuario_autenticado && in_array($_SESSION['tipo_usuario'], ['admin', 'vendedor'])) : ?>
                            <th>Seleccionar</th>
                        <?php endif; ?>
                    </tr>
                    <?php foreach ($resultados as $coche) : ?>
                        <tr>
                            <td align="center">
                            <?php 
                                    $ruta_foto = "../coches/fotos/" . basename(htmlspecialchars($coche['foto']));
                                    if (!empty($coche['foto']) && file_exists(__DIR__ . "/fotos/" . basename($coche['foto']))) {
                                        echo "<img src='$ruta_foto' alt='Foto del coche' width='100'>";
                                    } else {
                                        echo "No disponible";
                                    }
                                ?>
                            </td>
                            <td align="center"><?php echo htmlspecialchars($coche['modelo']); ?></td>
                            <td align="center"><?php echo htmlspecialchars($coche['marca']); ?></td>
                            <td align="center"><?php echo htmlspecialchars($coche['color']); ?></td>
                            <td align="center"><?php echo htmlspecialchars($coche['precio']); ?> €</td>
                            <?php if ($usuario_autenticado && in_array($_SESSION['tipo_usuario'], ['admin', 'vendedor'])) : ?>
                                <td align="center"><input type="checkbox" name="coches[]" value="<?php echo htmlspecialchars($coche['id_coche']); ?>"></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <br>
                <div align="center">
                    <?php if ($usuario_autenticado && in_array($_SESSION['tipo_usuario'], ['admin', 'vendedor'])) : ?>
                        <button type="submit" name="eliminar" >Eliminar Seleccionados</button>        
                    <?php endif; ?>
                </div>
            </form>
        <?php else : ?>
            <p>No se encontraron resultados.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
