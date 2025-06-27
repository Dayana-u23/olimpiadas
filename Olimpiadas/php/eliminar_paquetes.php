<?php
header('Content-Type: application/json');
include 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id_producto'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'ID no recibido']);
    exit;
}

$sql = "DELETE FROM productos WHERE id_producto = ?";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Error en la consulta: ' . $conexion->error]);
    exit;
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
$stmt->close();
$conexion->close();
