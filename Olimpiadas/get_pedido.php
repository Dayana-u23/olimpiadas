<?php
include 'conexion.php';
header('Content-Type: application/json');

if (!isset($_GET['id_pedido'])) {
    echo json_encode(['ok' => false, 'msg' => 'No se recibió id_pedido']);
    exit;
}

$id_pedido = (int)$_GET['id_pedido'];


$sql = "SELECT p.*, c.nombre AS cliente_nombre FROM pedido p
        LEFT JOIN clientes c ON p.id_cliente = c.id_cliente
        WHERE p.id_pedido = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['ok' => false, 'msg' => 'Pedido no encontrado']);
    exit;
}

$pedido = $result->fetch_assoc();

$sql_det = "SELECT dp.*, pr.descripcion FROM detalle_pedido dp
            LEFT JOIN productos pr ON dp.id_producto = pr.id_producto
            WHERE dp.id_pedido = ?";
$stmt_det = $conexion->prepare($sql_det);
$stmt_det->bind_param("i", $id_pedido);
$stmt_det->execute();
$result_det = $stmt_det->get_result();

$productos = [];
while ($row = $result_det->fetch_assoc()) {
    $productos[] = [
        'id_producto' => $row['id_producto'],
        'nombre' => $row['descripcion'],
        'cantidad' => $row['cantidad'],
        'precio' => floatval($row['precio']),
    ];
}

$pedido['productos'] = $productos;

echo json_encode(['ok' => true, 'pedido' => $pedido]);
?>
