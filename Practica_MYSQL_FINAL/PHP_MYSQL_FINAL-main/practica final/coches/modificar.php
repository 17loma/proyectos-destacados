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
$id_usuario_actual = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

include '../menu.php';

$conn = mysqli_connect("localhost", "root", "root", "concesionario");
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$criterio = $_POST['criterio'] ?? '';
$busqueda = $_POST['busqueda'] ?? '';
$coches = [];
$mensaje = "";
$editar_coche = null;


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar']) && !empty($criterio) && !empty($busqueda)) {
    $criterio = mysqli_real_escape_string($conn, $_POST['criterio']);
    $busqueda = mysqli_real_escape_string($conn, $_POST['busqueda']);
    
    $sql = "select * from coches where $criterio like '%$busqueda%'";
    $query_result = mysqli_query($conn, $sql);
    
    if ($query_result) {
        $coches = mysqli_fetch_all($query_result, MYSQLI_ASSOC);
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['seleccionar'])) {
    $id_coche = mysqli_real_escape_string($conn, $_POST['id_coche']);
    
    $sql = "select * from coches where id_coche='$id_coche'";
    $query_result = mysqli_query($conn, $sql);
    
    if ($query_result && mysqli_num_rows($query_result) > 0) {
        $editar_coche = mysqli_fetch_assoc($query_result);
        
        if ($editar_coche['id_usuario'] != $id_usuario_actual && $_SESSION['tipo_usuario'] != 'admin') {
            die("<p style='color:red;'>❌ No tienes permisos para modificar este coche.</p>");
        }
    } 
    else {
        $mensaje = "❌ Coche no encontrado.";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modificar'])) {
    $id_coche = mysqli_real_escape_string($conn, $_POST['id_coche']);
    $modelo = mysqli_real_escape_string($conn, $_POST['modelo']);
    $marca = mysqli_real_escape_string($conn, $_POST['marca']);
    $precio = mysqli_real_escape_string($conn, $_POST['precio']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $alquilado_nuevo = mysqli_real_escape_string($conn, $_POST['alquilado']);
    $foto_nueva = null;
    
    $sql_actual = "select  alquilado from coches where id_coche='$id_coche'";
    $query_actual = mysqli_query($conn, $sql_actual);
    $estado_actual = mysqli_fetch_assoc($query_actual);
    $alquilado_anterior = $estado_actual['alquilado'];
    
    $sql_update = "update coches set
        modelo='$modelo', 
        marca='$marca', 
        precio='$precio', 
        color='$color', 
        alquilado='$alquilado_nuevo'
        where id_coche='$id_coche'";

    if (mysqli_query($conn, $sql_update)) {
        $fecha_actual = date('Y-m-d H:i:s');

        if ($alquilado_anterior == 0 && $alquilado_nuevo == 1) {
            $sql_alquilar = "insert into alquileres (id_coche, id_usuario, prestado) 
                            values ('$id_coche', '$id_usuario_actual', '$fecha_actual')";
            mysqli_query($conn, $sql_alquilar);
        }

        if ($alquilado_anterior == 1 && $alquilado_nuevo == 0) {
            $sql_devolver = "update alquileres 
                           set devuelto = '$fecha_actual'
                           where id_coche = '$id_coche' 
                           and devuelto IS NULL 
                           order BY prestado DESC 
                           limit 1";
            mysqli_query($conn, $sql_devolver);
        }

        header("Location: modificar.php?id_coche=$id_coche&mensaje=✅ Coche actualizado correctamente.");
        exit();
    } 
    else {
        $mensaje = "❌ Error al actualizar el coche.";
    }
}

mysqli_close($conn);
?>


<body>
    <h2>Buscar Coche para Modificar</h2>
    <form class="contenido" action="" method="POST">
        <label for="criterio">Buscar por:</label>
        <select name="criterio" required>
            <option value="modelo">Modelo</option>
            <option value="marca">Marca</option>
        </select>
        <input type="text" name="busqueda" required>
        <button type="submit" name="buscar">Buscar</button>
    </form>
    
    <?php if (!empty($_GET['mensaje'])) echo "<p>" . htmlspecialchars($_GET['mensaje']) . "</p>"; ?>
    
    <?php if (!empty($coches)) : ?>
        <h2 align="center">Resultados de la búsqueda</h2>
        <table class="contenido" border="1">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Seleccionar</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Precio (€)</th>
                    <th>Color</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coches as $coche) : ?>
                    <tr>
                        <td align="center"><img src="../coches/<?php echo htmlspecialchars($coche['foto']); ?>" alt="Foto" width="100"></td>
                        <td align="center">
                            <form method="POST">
                                <input type="hidden" name="id_coche" value="<?php echo htmlspecialchars($coche['id_coche']); ?>">
                                <button type="submit" name="seleccionar">Modificar</button>
                            </form>
                        </td>
                        <td align="center"><?php echo htmlspecialchars($coche['marca']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($coche['modelo']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($coche['precio']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($coche['color']); ?></td>
                        <td align="center"><?php echo $coche['alquilado'] == 0 ? 'Disponible' : 'Alquilado'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if ($editar_coche) : ?>
        <h2>Modificar Coche</h2>
        <form class="contenido" action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_coche" value="<?php echo htmlspecialchars($editar_coche['id_coche']); ?>">
            <label>Modelo:</label>
            <input type="text" name="modelo" value="<?php echo htmlspecialchars($editar_coche['modelo']); ?>" required><br>
            <label>Marca:</label>
            <input type="text" name="marca" value="<?php echo htmlspecialchars($editar_coche['marca']); ?>" required><br>
            <label>Precio (€):</label>
            <input type="number" name="precio" value="<?php echo htmlspecialchars($editar_coche['precio']); ?>" required><br>
            <label>Color:</label>
            <input type="text" name="color" value="<?php echo htmlspecialchars($editar_coche['color']); ?>" required><br>
            <label for="alquilado">Estado:</label>
            <select name="alquilado">
                <option value="0">Disponible</option>
                <option value="1">Alquilado</option>
            </select><br> 
            <label>Imagen:</label>
            <input type="file" name="imagen"><br>
            <button type="submit" name="modificar">Actualizar</button>
        </form>
    <?php endif; ?>
</body>
</html>