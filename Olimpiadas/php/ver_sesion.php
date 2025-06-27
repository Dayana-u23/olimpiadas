<?php
session_start();

$response = ['autenticado' => false];

if (isset($_SESSION['id_usuario'], $_SESSION['nombre_usuario'], $_SESSION['tipo_usuario'])) {
    $response = [
        'autenticado' => true,
        'nombre' => $_SESSION['nombre_usuario'],
        'tipo' => $_SESSION['tipo_usuario']
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
