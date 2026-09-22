<?php

$project_folder = "/PHP/PHP_2EVAU/practica final"; 
$base_url = "http://localhost" . $project_folder . "/";

// Verifica si la constante ya está definida antes de definirla
if (!defined("BASE_URL")) {
    define("BASE_URL", rtrim($base_url, '/') . "/");
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

