<?php
session_start();
header('Content-Type: application/json');


$mysqli = new mysqli("localhost", "root", "", "turismo");

if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Error en la conexión']);
    exit;
}

$legajo = $mysqli->real_escape_string($_POST['legajo'] ?? '');
$codigo = $mysqli->real_escape_string($_POST['codigo'] ?? '');

if (empty($legajo) || empty($codigo)) {
    echo json_encode(['success' => false, 'message' => 'Complete todos los campos']);
    exit;
}

$query = "SELECT id_empleado, nombre, codigo FROM jefe_ventas WHERE id_empleado = '$legajo' AND codigo = '$codigo' LIMIT 1";
$result = $mysqli->query($query);

if ($result && $result->num_rows === 1) {
    $jefe = $result->fetch_assoc();

    echo json_encode([
        'success' => true,
        'nombre' => $jefe['nombre'],
        'id_empleado' => $jefe['id_empleado']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Legajo o código incorrecto']);
}

$mysqli->close();
?>
