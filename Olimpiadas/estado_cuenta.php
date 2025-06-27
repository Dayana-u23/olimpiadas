<?php
include 'php/conexion.php';

$ventas = [];
$totalVentas = 0;
$totalCantidad = 0;


$sql = "
SELECT 
  p.id_pedido,
  p.fecha,
  dp.id_producto,
  pr.codigo AS producto,
  dp.cantidad,
  (dp.cantidad * pr.precio) AS total
FROM pedido p
JOIN detalle_pedido dp ON p.id_pedido = dp.id_pedido
JOIN productos pr ON dp.id_producto = pr.id_producto
WHERE p.estado = 'pagado'
ORDER BY p.fecha DESC"
;


$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $ventas[] = $row;
        $totalVentas += $row['total'];
        $totalCantidad += $row['cantidad'];
    }
}

$comisiones = $totalVentas * 0.1; // 10% comisión
?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Estado de la cuenta - Turismo Pancracio</title>
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/menu.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.html">
      <img src="https://cdn-icons-png.flaticon.com/512/854/854878.png" class="logo-icon" alt="Logo" />
      Turismo Pancracio
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="mainMenu">
      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.html"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="Paquetes.php"><i class="bi bi-globe-americas"></i> Paquetes</a></li>
        <li class="nav-item"><a class="nav-link" href="carrito.html"><i class="bi bi-cart4"></i> Carrito</a></li>
        <li class="nav-item"><a class="nav-link" href="contacto.html"><i class="bi bi-envelope-fill"></i> Contacto</a></li>

        <li class="nav-item" id="menu-general">
          <a class="nav-link" id="menu-login-link" href="registro.html">
            <i class="bi bi-person-fill"></i> Iniciar sesión / Registrarse
          </a>
        </li>
        <li class="nav-item cliente-only" style="display: none;">
          <a class="nav-link" id="cliente-menu-link" href="php/mi_cuenta.php">
            <i class="bi bi-person-circle"></i> Mi cuenta
          </a>
        </li>
        <li class="nav-item dropdown jefe-only" style="display: none;">
          <a class="nav-link dropdown-toggle" href="#" id="jefeDropdownToggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-briefcase-fill"></i> Panel de Ventas
          </a>
          <ul class="dropdown-menu" id="jefeDropdownMenu">
            <li><a class="dropdown-item" href="carga_productos.html">Cargar Producto</a></li>
            <li><a class="dropdown-item" href="pedidos.html">Ver Pedidos</a></li>
            <li><a class="dropdown-item" href="estado_cuenta.php">Estado de Cuenta</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <div class="row text-center">
    <div class="col-md-4">
      <div class="card shadow-sm border-success">
        <div class="card-body">
          <h5 class="card-title text-success">Total de Ventas</h5>
          <p class="card-text display-6">$<?= number_format($totalVentas, 0, ',', '.') ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm border-info">
        <div class="card-body">
          <h5 class="card-title text-info">Productos Vendidos</h5>
          <p class="card-text display-6"><?= $totalCantidad ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm border-warning">
        <div class="card-body">
          <h5 class="card-title text-warning">Comisiones Acumuladas</h5>
          <p class="card-text display-6">$<?= number_format($comisiones, 0, ',', '.') ?></p>
        </div>
      </div>
    </div>
  </div>
   <table class="table table-hover table-bordered mt-4">
  <thead class="table-success">
    <tr>
      <th>Fecha</th>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($ventas)): ?>
      <?php foreach ($ventas as $v): ?>
        <tr>
          <td><?= htmlspecialchars($v['fecha']) ?></td>
          <td><?= htmlspecialchars($v['producto']) ?></td>
          <td><?= (int)$v['cantidad'] ?></td>
          <td>$<?= number_format($v['total'], 0, ',', '.') ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="4" class="text-center">No hay ventas registradas.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>


  <div class="text-center mt-4">
    <a href="pedidos.html" class="btn btn-outline-success">
      <i class="bi bi-arrow-left-circle"></i> Volver al Panel de pedidos
    </a>
  </div>
</div>

<script src="js/login.js"></script>
<script src="js/panelJefeVentas.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
