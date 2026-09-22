<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Usuario</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<?php
session_start();

   
if (!isset($_SESSION['tipo_usuario']) || !in_array($_SESSION['tipo_usuario'], ['vendedor', 'admin'])) {
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
    $dni = mysqli_real_escape_string($conn, $_POST['dni']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 
    $tipo_usuario = mysqli_real_escape_string($conn, $_POST['tipo_usuario']);
    $saldo = floatval($_POST['saldo']);

    
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

   
    $sql_verificar = "select id_usuario from usuarios where dni='$dni' or correo='$correo'";
    $resultado = mysqli_query($conn, $sql_verificar);

    if (mysqli_num_rows($resultado) > 0) {
        echo "El usuario con ese DNI o correo ya está registrado.";
    } 
    else {
       
        $sql = "insert into usuarios (nombre, apellidos, dni, correo, password, tipo_usuario, saldo) 
                values ('$nombre', '$apellidos', '$dni', '$correo', '$hashedPassword', '$tipo_usuario', '$saldo')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Usuario insertado correctamente.'); window.location.href='listar.php';</script>";
        } 
        else {
            echo "Error al registrar usuario: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>


<body >
    <h2 align="center">Añadir Usuario</h2>
    <?php if (!empty($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>
    <form class="contenido" align="center" method="POST" action="">
        <label align="center" for="nombre">Nombre:</label>
        <input type="text" name="nombre" align="center" required><br>
        
        <label for="apellidos" align="center">Apellidos:</label>
        <input type="text" name="apellidos" align="center" required><br>
        
        <label for="dni" align="center">DNI:</label>
        <input type="text" name="dni" align="center" required><br>
        
        <label for="correo" align="center" >Correo:</label>
        <input type="email" name="correo" align="center" required><br>
        
        <label for="password" align="center">Contraseña:</label>
        <input type="password" name="password" align="center" required><br>
        
        <label for="tipo_usuario" align="center">Rol:</label>
        <select name="tipo_usuario" required>
            <option value="comprador">Comprador</option>
            <option value="vendedor">Vendedor</option>
            <option value="admin">Administrador</option>
        </select><br>
        
        <label for="saldo" align="center">Saldo Inicial:</label>
        <input type="number" name="saldo" align="center" value="0" step="0.01" required><br>
        
        <button type="submit" align="center">Añadir Usuario</button>
    </form>

    <footer class="footer">
        <p>© 2024. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
