<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="index.css">
    <link rel="icon" type="image/png" href="./favicon.png">

</head>
<?php
session_start();

require_once __DIR__ . '/config.php';

include './menu.php';


$usuario_autenticado = isset($_SESSION['tipo_usuario']);

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "concesionario";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
    $dni = mysqli_real_escape_string($conn, $_POST['dni']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $password = $_POST['password'];
    $tipo_usuario = mysqli_real_escape_string($conn, $_POST['tipo_usuario']);
    $saldo = floatval($_POST['saldo']);

    
    $sql_verificar = "select id_usuario from usuarios where dni = ? or correo = ?";
    $stmt = mysqli_prepare($conn, $sql_verificar);
    mysqli_stmt_bind_param($stmt, "ss", $dni, $correo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $mensaje = "<p style='color:red;'>El usuario con ese DNI o correo ya está registrado.</p>";
    } 
    else {
        
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

       
        $sql = "insert into usuarios (nombre, apellidos, dni, correo, password, tipo_usuario, saldo) 
                values (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssd", $nombre, $apellidos, $dni, $correo, $password_hash, $tipo_usuario, $saldo);
        
        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "<p style='color:green;'>Usuario registrado correctamente. Redirigiendo a inicio de sesión...</p>";
            echo "<script>setTimeout(() => { window.location.href = 'login.php'; }, 2000);</script>";
        } 
        else {
            $mensaje = "<p style='color:red;'>Error al registrar usuario: " . mysqli_error($conn) . "</p>";
        }
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<body>
    <h2>Registro de Usuario</h2>
    <?php if (!empty($mensaje)) echo $mensaje; ?>
    
    <form method="POST" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>
        
        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" required><br>
        
        <label for="dni">DNI:</label>
        <input type="text" name="dni" required><br>
        
        <label for="correo">Correo:</label>
        <input type="email" name="correo" required><br>
        
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br>
        
        <label for="tipo_usuario">Rol:</label>
        <select name="tipo_usuario" required>
            <option value="comprador">Comprador</option>
            <option value="vendedor">Vendedor</option>
        </select><br>
        
        <label for="saldo">Saldo Inicial:</label>
        <input type="number" name="saldo" value="0" step="0.01" required><br>
        
        <button type="submit">Registrar</button>
    </form>

    <footer class="footer">
        <p>© 2024. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
