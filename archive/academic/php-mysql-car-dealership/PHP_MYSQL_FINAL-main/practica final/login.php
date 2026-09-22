<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_SESSION['tipo_usuario'])) {
    
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/config.php';

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "concesionario";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$mensaje = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST['dni'];
    $password = $_POST['password'];

   
    $sql = "select id_usuario, nombre, tipo_usuario, password from usuarios where dni = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $dni);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);

       
        if (password_verify($password, $usuario['password'])) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];

           
            if ($usuario['tipo_usuario'] == "comprador") {
                
                header("Location: " . BASE_URL . "coches/buscar.php");
            } 
            else {
                
                header("Location: " . BASE_URL . "index.php");
            }
            exit();
        } 
        else {
            $mensaje = "❌ Contraseña incorrecta.";
        }
    } 
    else {
        $mensaje = "❌ Usuario no encontrado.";
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="icon" type="image/png" href="./favicon.png">
    <style> 
        
        body {
            background-image: url('./login.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }


        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }


        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }


        .login-container button {
            background-color: #00509e;
            color: white;
            border: none;
            padding: 10px 15px;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
        }

        .login-container button:hover {
            background-color: #003366;
        }


        .error {
            color: red;
            font-weight: bold;
        }

        .registro {
            color:rgb(34, 126, 11);

        }
</style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if (!empty($mensaje)) echo "<p class='error'>$mensaje</p>"; ?>

        <form method="POST" action="">
            <label for="dni">DNI:</label>
            <input type="text" name="dni" required><br>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" required><br>

            <button type="submit">Iniciar Sesión</button> <br>

            <h2>¿No tienes una cuenta todavía?<br>
            <a href="registro.php" class="registro">Regístrate</a></h2>
        </form>
    </div>
</body>
</html>

