<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'dback';
$username = 'root';
$password = 'Admin2024ñ';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $_GET['action'] ?? $input['action'] ?? '';

switch ($action) {
    case 'get_solicitudes':
        handleGetSolicitudes($pdo);
        break;
    case 'get_solicitud':
        handleGetSolicitud($pdo, $_GET['id'] ?? 0);
        break;
    case 'add_solicitud':
        handleAddSolicitud($pdo, $input);
        break;
    case 'update_solicitud':
        handleUpdateSolicitud($pdo, $input);
        break;
    case 'delete_solicitud':
        handleDeleteSolicitud($pdo, $input);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}

function handleGetSolicitudes($pdo) {
    $page = max(1, intval($_GET['page'] ?? 1));
    $search = trim($_GET['search'] ?? '');
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $where = '';
    $params = [];

    if (!empty($search)) {
        $where = "WHERE nombre LIKE :search OR email LIKE :search OR ubicacion_origen LIKE :search OR ubicacion_destino LIKE :search OR descripcion LIKE :search OR status LIKE :search";
        $params[':search'] = "%$search%";
    }

    try {
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM solicitudes_servicio $where");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT * FROM solicitudes_servicio $where ORDER BY id DESC LIMIT :limit OFFSET :offset");
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'solicitudes' => $stmt->fetchAll(),
            'total' => $total
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener solicitudes: ' . $e->getMessage()]);
    }
}

function handleGetSolicitud($pdo, $id) {
    $id = intval($id);
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM solicitudes_servicio WHERE id = ?");
        $stmt->execute([$id]);
        $solicitud = $stmt->fetch();

        if ($solicitud) {
            echo json_encode(['success' => true, 'solicitud' => $solicitud]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Solicitud no encontrada']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener solicitud: ' . $e->getMessage()]);
    }
}

function handleAddSolicitud($pdo, $data) {
    $required = ['nombre', 'telefono', 'email', 'ubicacion_origen', 'ubicacion_destino', 'tipo_vehiculo', 'marca', 'modelo', 'placa', 'foto_vehiculo', 'tipo_servicio', 'descripcion', 'distancia', 'costo', 'metodo_pago', 'consentimiento', 'fecha_solicitud', 'status', 'gruas_id'];

    foreach ($required as $field) {
        if (!isset($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Falta el campo $field"]);
            return;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO solicitudes_servicio 
            (nombre, telefono, email, ubicacion_origen, ubicacion_destino, tipo_vehiculo, marca, modelo, placa, foto_vehiculo, tipo_servicio, descripcion, distancia, costo, metodo_pago, consentimiento, fecha_solicitud, status, gruas_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['nombre'],
            $data['telefono'],
            $data['email'],
            $data['ubicacion_origen'],
            $data['ubicacion_destino'],
            $data['tipo_vehiculo'],
            $data['marca'],
            $data['modelo'],
            $data['placa'],
            $data['foto_vehiculo'],
            $data['tipo_servicio'],
            $data['descripcion'],
            $data['distancia'],
            $data['costo'],
            $data['metodo_pago'],
            $data['consentimiento'],
            $data['fecha_solicitud'],
            $data['status'],
            $data['gruas_id']
        ]);

        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al agregar solicitud: ' . $e->getMessage()]);
    }
}

function handleUpdateSolicitud($pdo, $data) {
    if (empty($data['id']) || intval($data['id']) <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }

    $fields = ['nombre', 'telefono', 'email', 'ubicacion_origen', 'ubicacion_destino', 'tipo_vehiculo', 'marca', 'modelo', 'placa', 'foto_vehiculo', 'tipo_servicio', 'descripcion', 'distancia', 'costo', 'metodo_pago', 'consentimiento', 'fecha_solicitud', 'status', 'gruas_id'];

    $setParts = [];
    $values = [];

    foreach ($fields as $field) {
        if (isset($data[$field])) {
            $setParts[] = "$field = ?";
            $values[] = $data[$field];
        }
    }

    if (empty($setParts)) {
        echo json_encode(['success' => false, 'message' => 'No hay campos para actualizar']);
        return;
    }

    $values[] = intval($data['id']);

    $sql = "UPDATE solicitudes_servicio SET " . implode(', ', $setParts) . " WHERE id = ?";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        echo json_encode(['success' => true, 'affected_rows' => $stmt->rowCount()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar solicitud: ' . $e->getMessage()]);
    }
}

function handleDeleteSolicitud($pdo, $data) {
    if (empty($data['id']) || intval($data['id']) <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM solicitudes_servicio WHERE id = ?");
        $stmt->execute([intval($data['id'])]);

        echo json_encode(['success' => $stmt->rowCount() > 0]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar solicitud: ' . $e->getMessage()]);
    }
}


