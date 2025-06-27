<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "turismo");
if ($conexion->connect_error) {
  die("Conexión fallida: " . $conexion->connect_error);
}


$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$dni = trim($_POST['dni']);
$telefono = trim($_POST['telefono']);
$direccion = trim($_POST['direccion']);
$email = trim($_POST['email']);
$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);


$verificar_email = $conexion->prepare("SELECT email FROM clientes WHERE email = ?");
$verificar_email->bind_param("s", $email);
$verificar_email->execute();
$verificar_email->store_result();
if ($verificar_email->num_rows > 0) {
  echo "El correo ya está registrado.";
  exit();
}
$verificar_email->close();

$verificar_dni = $conexion->prepare("SELECT dni FROM clientes WHERE dni = ?");
$verificar_dni->bind_param("s", $dni);
$verificar_dni->execute();
$verificar_dni->store_result();
if ($verificar_dni->num_rows > 0) {
  echo "El DNI ya está registrado.";
  exit();
}
$verificar_dni->close();


$insertar = $conexion->prepare("INSERT INTO clientes (nombre, apellido, dni, telefono, direccion, email, clave) VALUES (?, ?, ?, ?, ?, ?, ?)");
$insertar->bind_param("sssssss", $nombre, $apellido, $dni, $telefono, $direccion, $email, $clave);

if ($insertar->execute()) {
 
  $_SESSION['id_usuario'] = $conexion->insert_id;

 
  $_SESSION['nombre'] = $nombre;
  $_SESSION['apellido'] = $apellido;
  $_SESSION['email'] = $email;

  echo '<!DOCTYPE html>
  <html lang="es">
  <head><meta charset="UTF-8"><title>Registro exitoso</title></head>
  <body>
    <script>
      localStorage.setItem("tipo_usuario", "cliente");
      localStorage.setItem("nombre_usuario", '.json_encode($nombre).');
      alert("Registro exitoso. Bienvenido/a '.$nombre.'");
      window.location.href = "../index.html";
    </script>
  </body>
  </html>';
} else {
  echo "Error al registrar: " . $insertar->error;
}

$insertar->close();
$conexion->close();
?>
