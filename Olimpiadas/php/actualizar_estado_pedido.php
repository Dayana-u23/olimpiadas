<?php
include 'conexion.php';
header('Content-Type: application/json');

if (!$conexion || $conexion->connect_errno) {
    echo json_encode(['ok' => false, 'msg' => 'Error de conexión a la base de datos']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id_pedido']) || empty($data['estado']) || empty($data['id_empleado'])) {
    echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
    exit;
}

$id_pedido = intval($data['id_pedido']);
$estado = $data['estado']; // 'pagado' o 'cancelado'
$id_empleado = intval($data['id_empleado']);

$estados_validos = ['pagado', 'cancelado'];
if (!in_array($estado, $estados_validos)) {
    echo json_encode(['ok' => false, 'msg' => 'Estado inválido']);
    exit;
}

$sql = "UPDATE pedido SET estado = ?, id_empleado = ? WHERE id_pedido = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(['ok' => false, 'msg' => 'Error en la preparación: ' . $conexion->error]);
    exit;
}

$stmt->bind_param('sii', $estado, $id_empleado, $id_pedido);

if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'msg' => 'Estado actualizado correctamente']);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Error al actualizar: ' . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
