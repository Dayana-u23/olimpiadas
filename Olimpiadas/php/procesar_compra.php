<?php
include 'conexion.php';
header('Content-Type: application/json');

if (!isset($conexion)) {
    echo json_encode(['ok' => false, 'msg' => 'Error de conexión a la base de datos']);
    exit;
}

$input = file_get_contents('php://input');
if (!$input) {
    echo json_encode(['ok' => false, 'msg' => 'No se recibió ningún dato']);
    exit;
}

$data = json_decode($input, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'ok' => false,
        'msg' => 'JSON inválido',
        'error' => json_last_error_msg(),
        'raw' => $input
    ]);
    exit;
}

if (empty($data['productos']) || empty($data['metodo_pago']) || empty($data['id_cliente'])) {
    echo json_encode(['ok' => false, 'msg' => 'Faltan datos necesarios']);
    exit;
}

$productos = $data['productos'];
$metodo_pago = $data['metodo_pago'];
$id_cliente = intval($data['id_cliente']);
$fecha = $data['fecha'] ?? date('Y-m-d H:i:s');

$stmt_cliente = $conexion->prepare("SELECT id_cliente FROM clientes WHERE id_cliente = ? LIMIT 1");
$stmt_cliente->bind_param("i", $id_cliente);
$stmt_cliente->execute();
$res_cliente = $stmt_cliente->get_result();
if ($res_cliente->num_rows === 0) {
    echo json_encode(['ok' => false, 'msg' => 'Cliente no encontrado']);
    exit;
}

$total = 0;
foreach ($productos as $p) {
    $cantidad = isset($p['cantidad']) ? (int)$p['cantidad'] : 1;
    $precio = isset($p['precio']) ? floatval($p['precio']) : 0;
    $total += $cantidad * $precio;
}

$id_empleado = null; 

$sqlPedido = "INSERT INTO pedido (id_cliente, id_empleado, fecha, estado, metodo_pago, total) VALUES (?, ?, ?, 'pendiente', ?, ?)";
$stmtPedido = $conexion->prepare($sqlPedido);
$stmtPedido->bind_param("isssd", $id_cliente, $id_empleado, $fecha, $metodo_pago, $total);
$stmtPedido->execute();
$id_pedido = $stmtPedido->insert_id;

foreach ($productos as $p) {
    $id_producto = $p['id_producto'] ?? null;
    $cantidad = $p['cantidad'] ?? 1;
    $precio = $p['precio'] ?? 0;

    if (!$id_producto) continue;

    $res = $conexion->query("SELECT 1 FROM productos WHERE id_producto = $id_producto LIMIT 1");
    if ($res->num_rows === 0) continue;

    $stmt_detalle = $conexion->prepare("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)");
    $stmt_detalle->bind_param("iiid", $id_pedido, $id_producto, $cantidad, $precio);
    $stmt_detalle->execute();
}

echo json_encode([
    'ok' => true,
    'compra' => [
        'id_pedido' => $id_pedido,
        'estado' => 'pendiente',
        'total' => $total
    ]
]);
