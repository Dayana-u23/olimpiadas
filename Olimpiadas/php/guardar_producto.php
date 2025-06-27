<?php
include 'conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido']);
    exit;
}

$codigo = trim($_POST['codigo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$lugar = trim($_POST['lugar'] ?? '');
$duracion = trim($_POST['duracion'] ?? '');
$incluye = trim($_POST['incluye'] ?? '');
$precio = floatval($_POST['precio'] ?? 0);


if (!$codigo || !$descripcion || !$lugar || !$duracion || !$incluye || $precio <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'Faltan datos o precio inválido']);
    exit;
}

$sql = "INSERT INTO productos (codigo, descripcion, lugar, duracion, incluye, precio) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(['ok' => false, 'msg' => 'Error al preparar consulta: ' . $conexion->error]);
    exit;
}

$stmt->bind_param("sssssd", $codigo, $descripcion, $lugar, $duracion, $incluye, $precio);

if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'msg' => 'Producto guardado correctamente']);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Error al guardar: ' . $stmt->error]);
}

$stmt->close();
?>
