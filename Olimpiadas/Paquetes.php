<?php
include 'php/conexion.php';

$sql = "SELECT * FROM productos";
$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta SQL: " . $conexion->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Paquetes Turísticos - Turismo Pancracio</title>
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/menu.css" />
   <link rel="stylesheet" href="css/paquetes.css" />
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
          <a class="nav-link dropdown-toggle" href="#" id="jefeDropdownToggle">
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

<header class="bg-light border-bottom mb-5">
  <div class="container py-3 text-center">
    <h1 class="fw-bold text-success">Nuestros Paquetes Turísticos</h1>
    <p class="text-muted">Elige tu próximo destino y viví una experiencia inolvidable</p>
  </div>
</header>
<div class="container">
  <div class="row g-4">

<div class="container">
  <div class="row g-4">
    <?php while ($producto = $resultado->fetch_assoc()): ?>
      <div class="col-md-4 paquete" data-id="<?= $producto['id_producto'] ?>">
        <div class="card shadow-sm">
          <img src="imagenes/<?= htmlspecialchars($producto['codigo']) ?>.jpg" class="card-img-top" alt="<?= htmlspecialchars($producto['codigo']) ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($producto['codigo']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($producto['descripcion']) ?></p>
            <div class="extra-info">
              <p><strong>Lugar:</strong> <?= htmlspecialchars($producto['lugar']) ?></p>
              <p><strong>Duración:</strong> <?= htmlspecialchars($producto['duracion']) ?></p>
              <p><strong>Incluye:</strong> <?= $producto['incluye'] ?></p>
            </div>
            <p class="fw-bold text-success">$ <?= number_format($producto['precio'], 0, ',', '.') ?> por persona</p>
        <input type="number" class="form-control mb-2 input-cantidad" value="1" min="1" />

            <button class="btn btn-add w-100 btn-agregar-carrito"
              data-id="<?= $producto['id_producto'] ?>"
              data-nombre="<?= htmlspecialchars($producto['codigo']) ?>"
              data-precio="<?= $producto['precio'] ?>">
              <i class="bi bi-cart-plus"></i> Añadir al carrito
            </button>
            <button class="btn btn-danger w-100 mt-2 btn-eliminar" data-id="<?= $producto['id_producto'] ?>" style="display: none;">Eliminar paquete</button>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/script_menu.js"></script>
<script src="js/login.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

  const tipoUsuario = localStorage.getItem('tipo_usuario');
  if (tipoUsuario === 'jefe') {
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
      btn.style.display = 'block';
      btn.addEventListener('click', function () {
        const paqueteDiv = this.closest('.paquete');
        const id = this.dataset.id;

        if (!confirm('¿Estás seguro de eliminar este paquete?')) return;

        fetch('php/eliminar_paquetes.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id=' + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            paqueteDiv.remove();
          } else {
            alert('Error al eliminar: ' + (data.error || 'desconocido'));
          }
        })
        .catch(error => {
          alert('Error en la comunicación con el servidor');
        });
      });
    });
  }

  document.querySelectorAll('.btn-agregar-carrito').forEach(boton => {
    boton.addEventListener('click', function () {
      const id = parseInt(this.dataset.id);
      const nombre = this.dataset.nombre;
      const precio = parseFloat(this.dataset.precio);
      const inputCantidad = this.closest('.card-body').querySelector('.input-cantidad');
      const cantidad = parseInt(inputCantidad.value) || 1;

      if (cantidad < 1) {
        alert('La cantidad debe ser al menos 1');
        return;
      }

      agregarAlCarrito(nombre, precio, id, cantidad);
    });
  });

  actualizarContador();
  mostrarCarrito();
});

function agregarAlCarrito(nombre, precio, id_producto, cantidad = 1) {
  const producto = {
    id_producto: id_producto,
    descripcion: nombre,
    precio: parseFloat(precio),
    cantidad: parseInt(cantidad)
  };

  let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

  const index = carrito.findIndex(p => p.id_producto === id_producto);
  if (index >= 0) {
    carrito[index].cantidad += producto.cantidad;
  } else {
    carrito.push(producto);
  }

  localStorage.setItem('carrito', JSON.stringify(carrito));
  actualizarContador();
  mostrarCarrito();
  alert(`${nombre} agregado al carrito (${producto.cantidad})`);
}

function actualizarContador() {
  const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
  const contador = document.getElementById('contador-carrito');
  if (contador) {
    const totalCantidad = carrito.reduce((acc, item) => acc + item.cantidad, 0);
    contador.textContent = totalCantidad;
  }
}

function mostrarCarrito() {
  const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
  const contenedor = document.getElementById('carrito-contenido');
  if (!contenedor) return; 

  contenedor.innerHTML = '';

  if (carrito.length === 0) {
    contenedor.innerHTML = '<p class="text-center text-muted">El carrito está vacío.</p>';
    const totalSpan = document.getElementById('total');
    if (totalSpan) totalSpan.textContent = '';
    return;
  }

  carrito.forEach((item, index) => {
    const row = document.createElement('div');
    row.classList.add('row', 'mb-3', 'align-items-center', 'border-bottom', 'pb-2');

    row.innerHTML = `
      <div class="col-md-4">${item.descripcion}</div>
      <div class="col-md-2">Cant: ${item.cantidad}</div>
      <div class="col-md-3 text-success">$ ${(item.precio * item.cantidad).toLocaleString()}</div>
      <div class="col-md-3 text-end">
        <button class="btn btn-remove btn-sm" onclick="eliminarPaquete(${index})">
          <i class="bi bi-trash"></i> Quitar
        </button>
      </div>
    `;

    contenedor.appendChild(row);
  });

  const total = carrito.reduce((acc, item) => acc + item.precio * item.cantidad, 0);
  const totalSpan = document.getElementById('total');
  if (totalSpan) totalSpan.textContent = `Total: $ ${total.toLocaleString()}`;
}

function eliminarPaquete(index) {
  let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
  carrito.splice(index, 1);
  localStorage.setItem('carrito', JSON.stringify(carrito));
  mostrarCarrito();
  actualizarContador();
}


</script>

<script src="js/carrito.js"></script>
<script src="js/panelJefeVentas.js"></script>
</body>
</html>