<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'turismo';

$conexion = new mysqli($host, $user, $password, $dbname);
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}
$conexion->set_charset('utf8');
?>
