<?php
include 'conexion.php';
header('Content-Type: application/json');

$id_pedido = $_GET['id_pedido'] ?? '';

if (!$id_pedido || !is_numeric($id_pedido)) {
    echo json_encode(['ok' => false, 'msg' => 'ID de pedido inválido']);
    exit;
}

$sqlPedido = "
SELECT p.*, c.nombre AS nombre_cliente, jv.nombre AS nombre_empleado
FROM pedido p
LEFT JOIN clientes c ON p.id_cliente = c.id_cliente
LEFT JOIN jefe_ventas jv ON p.id_empleado = jv.id_empleado
WHERE p.id_pedido = ?";

$stmt = $conexion->prepare($sqlPedido);
if (!$stmt) {
    echo json_encode(['ok' => false, 'msg' => 'Error en la consulta pedido']);
    exit;
}

$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['ok' => false, 'msg' => 'Pedido no encontrado']);
    exit;
}

$pedido = $result->fetch_assoc();

$sqlDetalle = "SELECT dp.cantidad, dp.precio, pr.descripcion AS nombre
               FROM detalle_pedido dp
               JOIN productos pr ON dp.id_producto = pr.id_producto
               WHERE dp.id_pedido = ?";
$stmt2 = $conexion->prepare($sqlDetalle);
if (!$stmt2) {
    echo json_encode(['ok' => false, 'msg' => 'Error en la consulta detalle']);
    exit;
}

$stmt2->bind_param("i", $id_pedido);
$stmt2->execute();
$result2 = $stmt2->get_result();

$productos = [];

while ($row = $result2->fetch_assoc()) {
    $productos[] = [
        'nombre' => $row['nombre'],
        'cantidad' => (int)$row['cantidad'],
        'precio' => (float)$row['precio']
    ];
}

$pedido['productos'] = $productos;
$pedido['total'] = (float)$pedido['total'];

if (!isset($pedido['metodo_pago'])) {
    $pedido['metodo_pago'] = 'No definido';
}

if (!isset($pedido['estado'])) {
    $pedido['estado'] = 'pendiente';
}

if (!isset($pedido['fecha'])) {
    $pedido['fecha'] = 'No definida';
}

echo json_encode(['ok' => true, 'pedido' => $pedido]);
