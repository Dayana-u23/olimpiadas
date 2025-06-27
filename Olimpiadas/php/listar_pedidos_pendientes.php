<?php
include 'conexion.php';

header('Content-Type: application/json');

if (!$conexion || $conexion->connect_errno) {
    echo json_encode(['ok' => false, 'msg' => 'Error de conexión a la base de datos']);
    exit;
}

$sql = "SELECT p.id_pedido, p.fecha, p.estado, p.metodo_pago, p.total, c.nombre AS cliente_nombre 
        FROM pedido p 
        LEFT JOIN clientes c ON p.id_cliente = c.id_cliente 
        WHERE p.estado = 'pendiente' 
        ORDER BY p.fecha DESC";

$result = $conexion->query($sql);

if (!$result) {
    echo json_encode(['ok' => false, 'msg' => 'Error en la consulta: ' . $conexion->error]);
    exit;
}

$pedidos = [];

while ($pedido = $result->fetch_assoc()) {
    $id_pedido = $pedido['id_pedido'];
    $sqlProd = "SELECT dp.id_producto, pr.descripcion, dp.cantidad, dp.precio
                FROM detalle_pedido dp
                JOIN productos pr ON dp.id_producto = pr.id_producto
                WHERE dp.id_pedido = $id_pedido";
    $resProd = $conexion->query($sqlProd);
    $productos = [];
    if ($resProd) {
        while ($prod = $resProd->fetch_assoc()) {
            $productos[] = $prod;
        }
    }

    $pedido['productos'] = $productos;
    $pedidos[] = $pedido;
}

echo json_encode(['ok' => true, 'pedidos' => $pedidos]);
?>
