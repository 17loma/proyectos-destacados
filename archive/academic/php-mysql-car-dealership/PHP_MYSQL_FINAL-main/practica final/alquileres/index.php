<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Alquileres</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || !in_array($_SESSION['tipo_usuario'], ['admin', 'vendedor'])) {
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
$id_usuario_actual = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar']) && !empty($_POST['alquileres'])) {
    $alquileresSeleccionados = array_map('intval', $_POST['alquileres']);
    
    if ($_SESSION['tipo_usuario'] == 'vendedor') {
        $alquileresSeleccionados = array_filter($alquileresSeleccionados, function($id_alquiler) use ($conn, $id_usuario_actual) {
            $sql_verificar = "SELECT id_usuario FROM alquileres WHERE id_alquiler = $id_alquiler";
            $resultado_verificacion = mysqli_query($conn, $sql_verificar);
            $datos_alquiler = mysqli_fetch_assoc($resultado_verificacion);
            return $datos_alquiler['id_usuario'] == $id_usuario_actual;
        });
    }

    if (!empty($alquileresSeleccionados)) {
        $alquileresLista = implode(",", $alquileresSeleccionados);
        
        $sql_coches = "SELECT id_coche FROM alquileres WHERE id_alquiler IN ($alquileresLista)";
        $result_coches = mysqli_query($conn, $sql_coches);
        
        if ($result_coches) {
            while ($row = mysqli_fetch_assoc($result_coches)) {
                $id_coche = $row['id_coche'];
                mysqli_query($conn, "UPDATE coches SET alquilado = 0 WHERE id_coche = '$id_coche'");
            }
        }
        
        $sql = "DELETE FROM alquileres WHERE id_alquiler IN ($alquileresLista)";
        if (mysqli_query($conn, $sql)) {
            $mensaje = "Alquileres eliminados correctamente.";
        } else {
            $mensaje = "Error al eliminar los alquileres.";
        }
    } else {
        $mensaje = "<p style='color:red;'>No puedes eliminar alquileres que no has registrado tú.</p>";
    }
}

$sql = "SELECT a.id_alquiler, a.id_coche, a.id_usuario, a.prestado, a.devuelto, 
               u.nombre, u.apellidos, u.dni, 
               c.marca, c.modelo, c.precio
        FROM alquileres a
        JOIN usuarios u ON a.id_usuario = u.id_usuario
        JOIN coches c ON a.id_coche = c.id_coche";
$result = mysqli_query($conn, $sql);
$alquileres = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);
?>

<body>
    <h1 align="center">Gestión de Alquileres</h1>
    <?php if (!empty($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>
    <?php if (!empty($alquileres)) : ?>
        <form action="" method="POST">
            <table align="center" border="1">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Usuario</th>
                        <th>DNI</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Precio (€)</th>
                        <th>Fecha de Préstamo</th>
                        <th>Fecha de Devolución</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alquileres as $alquiler) : ?>
                        <tr>
                            <td align="center">
                                <input type="checkbox" name="alquileres[]" value="<?php echo htmlspecialchars($alquiler['id_alquiler']); ?>">
                            </td>
                            <td align="center"><?php echo htmlspecialchars($alquiler['nombre'] . " " . $alquiler['apellidos']); ?></td>
                            <td align="center"><?php echo htmlspecialchars($alquiler['dni']); ?></td>
                            <td align="center"><?php echo htmlspecialchars($alquiler['marca']); ?></td>
                            <td align="center"><?php echo htmlspecialchars($alquiler['modelo']); ?></td>
                            <td align="center"><?php echo htmlspecialchars($alquiler['precio']); ?></td>
                            <td align="center"><?php echo !empty($alquiler['prestado']) ? htmlspecialchars($alquiler['prestado']) : 'No registrado'; ?></td>
                            <td align="center"><?php echo !empty($alquiler['devuelto']) ? htmlspecialchars($alquiler['devuelto']) : 'No devuelto'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table> <br>
            <button type="submit" name="eliminar" style="display: block; margin: 0 auto; " onclick="return confirm('¿Seguro que deseas eliminar los alquileres seleccionados?');">Eliminar Seleccionados</button>
        </form>
    <?php else : ?>
        <p>No hay alquileres registrados.</p>
    <?php endif; ?>
    <footer class="footer">
        <p>© 2024. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
