<?php
include 'conexion.php';

header('Content-Type: application/json');

$id_pedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : 0;

if ($id_pedido <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'ID de pedido inválido']);
    exit;
}


if (!$conexion || $conexion->connect_errno) {
    echo json_encode(['ok' => false, 'msg' => 'Error de conexión a la base de datos']);
    exit;
}


$sql = "SELECT 
            p.fecha,
            prod.descripcion AS producto,
            dp.cantidad,
            dp.precio,
            (dp.cantidad * dp.precio) AS total
        FROM pedido p
        INNER JOIN detalle_pedido dp ON p.id_pedido = dp.id_pedido
        INNER JOIN productos prod ON dp.id_producto = prod.id_producto
        WHERE p.estado = 'pagado'
        ORDER BY p.fecha DESC";


$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo json_encode(['ok' => false, 'msg' => 'Error en la preparación de la consulta: ' . $conexion->error]);
    exit;
}

$stmt->bind_param('i', $id_pedido);

if (!$stmt->execute()) {
    echo json_encode(['ok' => false, 'msg' => 'Error al ejecutar la consulta: ' . $stmt->error]);
    exit;
}

$result = $stmt->get_result();

$productos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = [
        'id_producto' => $row['id_producto'],
        'descripcion' => $row['descripcion'],
        'cantidad' => (int)$row['cantidad'],
        'precio' => (float)$row['precio'],
        'total' => (float)$row['total'],
    ];
}

if (count($productos) === 0) {
    echo json_encode(['ok' => false, 'msg' => 'No se encontraron productos para este pedido']);
    exit;
}

echo json_encode(['ok' => true, 'productos' => $productos]);
