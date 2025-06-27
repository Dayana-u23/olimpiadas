<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['paquetes'])) {
    $_SESSION['paquetes'] = [
        [
            'id' => 1,
            'titulo' => 'Punta Cana',
            'descripcion' => 'Viaje al Caribe',
            'lugar' => 'Punta Cana',
            'duracion' => '8 dias / 7 noches',
            'incluye' => [
                'Alojamiento en hotel 4 estrellas a 50 metros de la playa',
                'Traslados aeropuerto-hotel-aeropuerto',
                'Desayunos y almuerzos en restaurantes locales',
                'Guia turistico en español',
                'Snorkel y kayak'
            ],
            'precio' => 450000,
            'imagen' => 'imagenes/Playa_Caribe.jpg'
        ],
    
    ];
}

echo json_encode(array_values($_SESSION['paquetes']));
