<?php
session_start();

$conexion = new mysqli("localhost", "root", "", "turismo");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


$correo = trim($_POST['email']);
$password = $_POST['clave'];


$stmt = $conexion->prepare("SELECT id_cliente, nombre, clave FROM clientes WHERE email = ?");
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id_cliente, $nombre_cliente, $hash_clave);
    $stmt->fetch();

    
    if (password_verify($password, $hash_clave)) {
        
        $_SESSION['id_usuario'] = $id_cliente;
        $_SESSION['nombre_usuario'] = $nombre_cliente;
        $_SESSION['tipo_usuario'] = 'cliente';

        
      echo "<script>
    localStorage.setItem('tipo_usuario', 'cliente');
    localStorage.setItem('nombre_usuario', " . json_encode($nombre_cliente) . ");
    localStorage.setItem('id_cliente', " . json_encode($id_cliente) . ");
    window.location.href = '../index.html';
</script>";

        exit();
    } else {
    
        echo "<script>
            alert('Contraseña incorrecta.');
            window.history.back();
        </script>";
        exit();
    }
} else {
    
    echo "<script>
        alert('Usuario no encontrado.');
        window.history.back();
    </script>";
    exit();
}

$stmt->close();
$conexion->close();
?>
