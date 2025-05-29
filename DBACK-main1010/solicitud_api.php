<?php
require 'conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

function sanitize($str) {
    return htmlspecialchars(strip_tags(trim($str)));
}

// Sanitización y validación
$nombre = sanitize($data['nombre'] ?? '');
$telefono = isset($data['telefono']) && is_numeric($data['telefono']) ? (int)$data['telefono'] : null;
$email = trim($data['email'] ?? '') ?: null;
$ubicacion_origen = sanitize($data['ubicacion_origen'] ?? '');
$ubicacion_destino = sanitize($data['ubicacion_destino'] ?? '');
$tipo_vehiculo = $data['tipo_vehiculo'] ?? 'Baica';
$marca = sanitize($data['marca'] ?? '');
$modelo = sanitize($data['modelo'] ?? '');
$placa = sanitize($data['placa'] ?? '');
$foto_vehiculo = trim($data['foto_vehiculo'] ?? '') ?: null;
$tipo_servicio = sanitize($data['tipo_servicio'] ?? '');
$descripcion = sanitize($data['descripcion'] ?? '');
$distancia = trim($data['distancia'] ?? '') ?: null;
$costo = isset($data['costo']) && is_numeric($data['costo']) ? $data['costo'] : null;
$metodo_pago = $data['metodo_pago'] ?? 'Efectivo';
$consentimiento = isset($data['consentimiento']) && $data['consentimiento'] == true ? 1 : 0;

// Validación obligatoria
error_log(print_r($data, true));

if (empty($nombre) || $telefono === null || empty($ubicacion_origen) || empty($ubicacion_destino) || empty($tipo_servicio) || empty($descripcion)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben ser completados']);
    exit;
}

// Validación de email
if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Formato de email inválido.']);
    exit;
}

// Validación de valores aceptables
$vehiculos_validos = ['Baica', 'Automóvil', 'Camioneta', 'Motocicleta', 'Autobus', 'Submarino'];
if (!in_array($tipo_vehiculo, $vehiculos_validos)) {
    echo json_encode(['success' => false, 'message' => 'Tipo de vehículo no válido']);
    exit;
}

$metodos_validos = ['Efectivo', 'PayPal'];
if (!in_array($metodo_pago, $metodos_validos)) {
    echo json_encode(['success' => false, 'message' => 'Método de pago no válido']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO solicitudes_servicio 
        (nombre, telefono, email, ubicacion_origen, ubicacion_destino, tipo_vehiculo, marca, modelo, placa, 
         foto_vehiculo, tipo_servicio, descripcion, distancia, costo, metodo_pago, consentimiento)
        VALUES 
        (:nombre, :telefono, :email, :ubicacion_origen, :ubicacion_destino, :tipo_vehiculo, :marca, :modelo, :placa,
         :foto_vehiculo, :tipo_servicio, :descripcion, :distancia, :costo, :metodo_pago, :consentimiento)
    ");

    $stmt->execute([
        ':nombre' => $nombre,
        ':telefono' => $telefono,
        ':email' => $email,
        ':ubicacion_origen' => $ubicacion_origen,
        ':ubicacion_destino' => $ubicacion_destino,
        ':tipo_vehiculo' => $tipo_vehiculo,
        ':marca' => $marca,
        ':modelo' => $modelo,
        ':placa' => $placa,
        ':foto_vehiculo' => $foto_vehiculo,
        ':tipo_servicio' => $tipo_servicio,
        ':descripcion' => $descripcion,
        ':distancia' => $distancia,
        ':costo' => $costo,
        ':metodo_pago' => $metodo_pago,
        ':consentimiento' => $consentimiento,
    ]);

    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Solicitud guardada con éxito']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al guardar solicitud: ' . $e->getMessage()]);
}

