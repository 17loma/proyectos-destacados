<?php
session_start();
require_once __DIR__ . '/../config.php';


if (!isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario_autenticado = isset($_SESSION['tipo_usuario']);
$id_usuario = $_SESSION['id_usuario'];


if (!in_array($_SESSION['tipo_usuario'], ['vendedor', 'admin'])) {
    die("Acceso denegado. Debes iniciar sesión como vendedor o administrador.");
}

include '../menu.php';

$conn = mysqli_connect("localhost", "root", "root", "concesionario");
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$mensaje = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $modelo = mysqli_real_escape_string($conn, $_POST['modelo']);
    $marca = mysqli_real_escape_string($conn, $_POST['marca']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $precio = floatval($_POST['precio']);
    $alquilado = isset($_POST['alquilado']) ? intval($_POST['alquilado']) : 0;
    $ruta_foto = "fotos/default.jpg"; 

    
    if (!empty($_FILES['imagen']['name'])) {
        $foto_nombre = basename($_FILES['imagen']['name']);
        $foto_tmp = $_FILES['imagen']['tmp_name'];
        $directorio_destino = __DIR__ . "/fotos/";
        $ruta_foto = "fotos/" . $foto_nombre;

        
        if (!is_dir($directorio_destino)) {
            mkdir($directorio_destino, 0755, true);
        }

        
        $permitidos = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (in_array($_FILES['imagen']['type'], $permitidos)) {
            if (move_uploaded_file($foto_tmp, $directorio_destino . $foto_nombre)) {
                $ruta_foto = "fotos/" . mysqli_real_escape_string($conn, $foto_nombre);
            } 
            else {
                $mensaje = "<p style='color:red;'>Error al subir la imagen.</p>";
            }
        } 
        else {
            $mensaje = "<p style='color:red;'>Formato de imagen no permitido.</p>";
        }
    }

    
    $sql = "insert into coches (modelo, marca, color, precio, alquilado, foto, id_usuario) 
            values ('$modelo', '$marca', '$color', '$precio', '$alquilado', '$ruta_foto', '$id_usuario')";

    if (mysqli_query($conn, $sql)) {
        $id_coche = mysqli_insert_id($conn); 

       
        if ($alquilado == 1) {
            $sql_alquiler = "insert into alquileres (id_coche, id_usuario, prestado) 
            values ('$id_coche', '$id_usuario', NOW())";
            mysqli_query($conn, $sql_alquiler);
        }

        $mensaje = "<p style='color:green;'>✅ Coche añadido con éxito.</p>";
    } 
    else {
        $mensaje = "<p style='color:red;'>❌ Error al añadir el coche: " . mysqli_error($conn) . "</p>";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Coche</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
    <header class="header">
        <h1>Añadir Coche</h1>
    </header>

    <main class="contenido">
        <?php if (!empty($mensaje)) echo $mensaje; ?>

        <form class="contenido" action="" method="post" enctype="multipart/form-data">
            <label for="modelo">Modelo:</label>
            <input type="text" name="modelo" required><br>

            <label for="marca">Marca:</label>
            <input type="text" name="marca" required><br>

            <label for="color">Color:</label>
            <input type="text" name="color" required><br>

            <label for="precio">Precio:</label>
            <input type="number" name="precio" step="0.01" required><br>

            <label for="alquilado">Estado:</label>
            <select name="alquilado">
                <option value="0">Disponible</option>
                <option value="1">Alquilado</option>
            </select><br>

            <label for="imagen">Imagen:</label>
            <input type="file" name="imagen" accept="image/*"><br>

            <button type="submit">Añadir Coche</button>
        </form>
    </main>

    <footer class="footer">
        <p>© 2024. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
