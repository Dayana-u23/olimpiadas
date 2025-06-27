<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "<script>
      localStorage.clear();
      window.location.href = '../registro.html';
    </script>";
    exit();
}

$conexion = new mysqli("localhost", "root", "", "turismo");
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos");
}

$id = $_SESSION['id_usuario'];

$stmt = $conexion->prepare("SELECT nombre, apellido, email, telefono, direccion, dni FROM clientes WHERE id_cliente = ?");
if (!$stmt) {
    die("Error en la consulta: " . $conexion->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido, $email, $telefono, $direccion, $dni);

if (!$stmt->fetch()) {
    $stmt->close();
    $conexion->close();
    die("No se encontraron datos para el usuario.");
}

$stmt->close();
$conexion->close();
?>

<!DOCTYPE html> 
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mi cuenta - Portal Turístico</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
   <link rel="stylesheet" href="../css/menu.css" />
</head>
<body>

<nav class="navbar navbar-expand-lg shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="../index.html">
      <img src="https://cdn-icons-png.flaticon.com/512/854/854878.png" class="logo-icon" alt="Logo">
      Turismo Pancracio
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="mainMenu">
      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="../index.html"><i class="bi bi-house-door-fill"></i> Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../Paquetes.html"><i class="bi bi-globe-americas"></i> Paquetes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../carrito.php"><i class="bi bi-cart4"></i> Carrito</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../contacto.html"><i class="bi bi-envelope-fill"></i> Contacto</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#"><i class="bi bi-person-circle"></i> Mi cuenta</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

 <style>
#contenedor-cuenta .card {
  border-radius: 1rem;
}

#contenedor-cuenta .card-header {
  border-bottom: 1px solid rgba(255,255,255,0.2);
}

#contenedor-cuenta ul li {
  font-size: 1.1rem;
}
 </style>
<div class="container mt-5" id="contenedor-cuenta">
  <div class="card shadow-lg border-0">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i> Mi Cuenta</h4>
      <a href="#" id="logout-btn" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
      </a>
    </div>
    <div class="card-body">
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>👤 Nombre:</strong> <?php echo htmlspecialchars($nombre); ?></li>
        <li class="list-group-item"><strong>👥 Apellido:</strong> <?php echo htmlspecialchars($apellido); ?></li>
        <li class="list-group-item"><strong>📧 Email:</strong> <?php echo htmlspecialchars($email); ?></li>
        <li class="list-group-item"><strong>📞 Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></li>
        <li class="list-group-item"><strong>🏠 Dirección:</strong> <?php echo htmlspecialchars($direccion); ?></li>
        <li class="list-group-item"><strong>🆔 DNI:</strong> <?php echo htmlspecialchars($dni); ?></li>
      </ul>
    </div>
  </div>
</div>
</div>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/script_menu.js"></script>
<script>
  document.getElementById('logout-btn').addEventListener('click', function(event) {
    event.preventDefault();
    localStorage.removeItem('tipo_usuario');
    localStorage.removeItem('nombre_usuario');
    window.location.href = 'logout.php'; 
  });
</script>
</body>
</html>
