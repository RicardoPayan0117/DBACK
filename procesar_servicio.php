<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "5211";
$dbname = "DBACK";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['error' => "Error de conexión: " . $conn->connect_error]));
}

// Función para registrar logs
function registrarLog($conn, $tipo, $descripcion, $datos = null) {
    $sql = "INSERT INTO logs (tipo, descripcion, datos) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $datosJson = $datos ? json_encode($datos) : null;
    $stmt->bind_param("sss", $tipo, $descripcion, $datosJson);
    $stmt->execute();
    $stmt->close();
}

// Procesar la solicitud
try {
    // Validar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception("Método no permitido");
    }

    // Obtener datos del formulario
    $input = $_POST;
    $files = $_FILES;

    // Validar campos obligatorios
    $requiredFields = ['nombre', 'telefono', 'ubicacion_origen', 'ubicacion_destino', 
                      'vehiculo', 'marca', 'modelo', 'tipo_servicio', 'consentimiento'];
    
    foreach ($requiredFields as $field) {
        if (empty($input[$field])) {
            throw new Exception("El campo $field es obligatorio");
        }
    }

    // Validar consentimiento
    if ($input['consentimiento'] !== 'on') {
        throw new Exception("Debe aceptar la política de privacidad");
    }

    // Iniciar transacción
    $conn->begin_transaction();

    // 1. Registrar/actualizar cliente
    $sqlCliente = "INSERT INTO clientes (nombre, telefono, email) 
                   VALUES (?, ?, ?)
                   ON DUPLICATE KEY UPDATE nombre = VALUES(nombre), email = VALUES(email)";
    
    $stmtCliente = $conn->prepare($sqlCliente);
    $stmtCliente->bind_param("sss", 
        $conn->real_escape_string($input['nombre']),
        $conn->real_escape_string($input['telefono']),
        $conn->real_escape_string($input['email'] ?? null)
    );
    $stmtCliente->execute();
    $clienteId = $stmtCliente->insert_id ?: $conn->insert_id;
    $stmtCliente->close();

    // 2. Registrar vehículo
    $sqlVehiculo = "INSERT INTO vehiculos (cliente_id, tipo_vehiculo, marca, modelo, foto)
                    VALUES (?, ?, ?, ?, ?)";
    
    $stmtVehiculo = $conn->prepare($sqlVehiculo);
    
    // Manejar la foto si se subió
    $fotoPath = null;
    if (!empty($files['foto']['name'])) {
        $targetDir = "uploads/vehiculos/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $fileExt = pathinfo($files['foto']['name'], PATHINFO_EXTENSION);
        $fileName = "veh_" . $clienteId . "_" . time() . "." . $fileExt;
        $targetFile = $targetDir . $fileName;
        
        // Validar archivo
        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($files['foto']['type'], $allowedTypes)) {
            throw new Exception("Formato de imagen no válido. Solo se aceptan JPG y PNG");
        }
        
        if ($files['foto']['size'] > 5 * 1024 * 1024) { // 5MB
            throw new Exception("El archivo es demasiado grande. Máximo 5MB");
        }
        
        if (move_uploaded_file($files['foto']['tmp_name'], $targetFile)) {
            $fotoPath = $targetFile;
        }
    }
    
    $stmtVehiculo->bind_param("issss", 
        $clienteId,
        $conn->real_escape_string($input['vehiculo']),
        $conn->real_escape_string($input['marca']),
        $conn->real_escape_string($input['modelo']),
        $fotoPath
    );
    $stmtVehiculo->execute();
    $vehiculoId = $stmtVehiculo->insert_id;
    $stmtVehiculo->close();

    // 3. Registrar solicitud
    $sqlSolicitud = "INSERT INTO solicitudes (
                        cliente_id, vehiculo_id, ubicacion_origen, ubicacion_destino,
                        tipo_servicio, descripcion, urgencia, distancia_km, costo_estimado
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmtSolicitud = $conn->prepare($sqlSolicitud);
    
    $distancia = isset($input['distancia']) ? floatval(str_replace(' km', '', $input['distancia'])) : null;
    $costo = isset($input['costo']) ? floatval(str_replace(' MXN', '', $input['costo'])) : null;
    
    $stmtSolicitud->bind_param("iisssssdd",
        $clienteId,
        $vehiculoId,
        $conn->real_escape_string($input['ubicacion_origen']),
        $conn->real_escape_string($input['ubicacion_destino']),
        $conn->real_escape_string($input['tipo_servicio']),
        $conn->real_escape_string($input['descripcion'] ?? ''),
        $conn->real_escape_string($input['urgencia'] ?? 'normal'),
        $distancia,
        $costo
    );
    $stmtSolicitud->execute();
    $solicitudId = $stmtSolicitud->insert_id;
    $stmtSolicitud->close();

    // 4. Registrar pago si corresponde
    if (isset($input['metodo_pago_seleccionado']) && $costo > 0) {
        $sqlPago = "INSERT INTO pagos (
                        solicitud_id, metodo_pago, monto_deposito, monto_total,
                        paypal_order_id, paypal_status, paypal_email, paypal_name
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmtPago = $conn->prepare($sqlPago);
        
        $deposito = $costo * 0.2;
        $metodoPago = $conn->real_escape_string($input['metodo_pago_seleccionado']);
        
        $stmtPago->bind_param("isdsssss",
            $solicitudId,
            $metodoPago,
            $deposito,
            $costo,
            $conn->real_escape_string($input['paypal_order_id'] ?? null),
            $conn->real_escape_string($input['paypal_status'] ?? null),
            $conn->real_escape_string($input['paypal_email'] ?? null),
            $conn->real_escape_string($input['paypal_name'] ?? null)
        );
        $stmtPago->execute();
        $stmtPago->close();
    }

    // 5. Registrar consentimiento
    $sqlConsentimiento = "INSERT INTO consentimientos (
                            cliente_id, solicitud_id, aceptado, ip_address, user_agent
                          ) VALUES (?, ?, TRUE, ?, ?)";
    
    $stmtConsentimiento = $conn->prepare($sqlConsentimiento);
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    $stmtConsentimiento->bind_param("iiss",
        $clienteId,
        $solicitudId,
        $ip,
        $userAgent
    );
    $stmtConsentimiento->execute();
    $stmtConsentimiento->close();

    // Confirmar transacción
    $conn->commit();

    // Registrar log exitoso
    registrarLog($conn, 'solicitud', 'Nueva solicitud creada', [
        'cliente_id' => $clienteId,
        'vehiculo_id' => $vehiculoId,
        'solicitud_id' => $solicitudId
    ]);

    // Respuesta exitosa
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Solicitud registrada con éxito',
        'solicitud_id' => $solicitudId,
        'cliente_id' => $clienteId
    ]);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    if ($conn->in_transaction) {
        $conn->rollback();
    }

    // Registrar log de error
    registrarLog($conn, 'error', 'Error al procesar solicitud', [
        'error' => $e->getMessage(),
        'input' => $input ?? null
    ]);

    // Respuesta de error
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    $conn->close();
}