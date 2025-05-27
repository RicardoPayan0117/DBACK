<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'dback';
$username = 'root';
$password = 'Admin2024ñ';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]));
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $_GET['action'] ?? $input['action'] ?? '';

switch ($action) {
    case 'get_gruas':
        handleGetGruas($pdo);
        break;
    case 'get_grua':
        handleGetGrua($pdo, $_GET['id'] ?? 0);
        break;
    case 'add_grua':
        handleAddGrua($pdo, $input);
        break;
    case 'update_grua':
        handleUpdateGrua($pdo, $input);
        break;
    case 'delete_grua':
        handleDeleteGrua($pdo, $input);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}

// Obtener lista de grúas
function handleGetGruas($pdo) {
    $page = max(1, intval($_GET['page'] ?? 1));
    $search = trim($_GET['search'] ?? '');
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $where = '';
    $params = [];

    if (!empty($search)) {
        $where = "WHERE marca LIKE :search OR modelo LIKE :search OR placas LIKE :search OR tipo LIKE :search";
        $params[':search'] = "%$search%";
    }

    try {
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM gruas $where");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT * FROM gruas $where ORDER BY id LIMIT :limit OFFSET :offset");
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'gruas' => $stmt->fetchAll(),
            'total' => $total
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener grúas: ' . $e->getMessage()]);
    }
}

// Obtener grúa por ID
function handleGetGrua($pdo, $id) {
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM gruas WHERE id = ?");
        $stmt->execute([$id]);
        $grua = $stmt->fetch();

        if ($grua) {
            echo json_encode(['success' => true, 'grua' => $grua]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Grúa no encontrada']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener grúa: ' . $e->getMessage()]);
    }
}

// Agregar nueva grúa
function handleAddGrua($pdo, $data) {
    $required = ['nombre', 'placa', 'modelo', 'estado', 'capacidad_kg'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Falta el campo $field"]);
            return;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO gruas (nombre, placa, modelo, estado, capacidad_kg) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['placa'],
            $data['modelo'],
            $data['estado'],
            $data['capacidad_kg']
        ]);

        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al agregar: ' . $e->getMessage()]);
    }
}

// Actualizar grúa
function handleUpdateGrua($pdo, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }

    $required = ['nombre', 'placa', 'modelo', 'estado', 'capacidad_kg'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Falta el campo $field"]);
            return;
        }
    }

    try {
        $stmt = $pdo->prepare("UPDATE gruas SET nombre=?, placa=?, modelo=?, estado=?, capacidad_kg=? WHERE id=?");
        $stmt->execute([
            $data['nombre'],
            $data['placa'],
            $data['modelo'],
            $data['estado'],
            $data['capacidad_kg'],
            $data['id']
        ]);

        echo json_encode(['success' => true, 'affected_rows' => $stmt->rowCount()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()]);
    }
}

// Eliminar grúa
function handleDeleteGrua($pdo, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM gruas WHERE id = ?");
        $stmt->execute([$data['id']]);

        echo json_encode(['success' => $stmt->rowCount() > 0]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()]);
    }
}
