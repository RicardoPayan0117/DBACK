<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'dback';
$username = 'root';
$password = '5211';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]));
}

// Obtener datos de la solicitud
$input = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $_GET['action'] ?? $input['action'] ?? '';

// Registrar para depuración (opcional)
error_log("Acción recibida: $action");
error_log("Datos recibidos: " . print_r(array_merge($_GET, $input), true));

switch ($action) {
    case 'get_employees':
        handleGetEmployees($pdo);
        break;
    case 'get_employee':
        handleGetEmployee($pdo, $_GET['id'] ?? 0);
        break;
    case 'add_employee':
        handleAddEmployee($pdo, $input);
        break;
    case 'update_employee':
        handleUpdateEmployee($pdo, $input);
        break;
    case 'delete_employee':
        handleDeleteEmployee($pdo, $input);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}

function handleGetEmployees($pdo) {
    $page = max(1, intval($_GET['page'] ?? 1));
    $search = trim($_GET['search'] ?? '');
    $limit = 5;
    $offset = ($page - 1) * $limit;
    
    $where = '';
    $params = [];
    
    if (!empty($search)) {
        $where = "WHERE firstName LIKE :search OR lastName LIKE :search OR email LIKE :search OR department LIKE :search";
        $params[':search'] = "%$search%";
    }
    
    try {
        // Contar total
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM empleados $where");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        // Obtener registros
        $stmt = $pdo->prepare("SELECT * FROM empleados $where ORDER BY id LIMIT :limit OFFSET :offset");
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        echo json_encode([
            'success' => true,
            'employees' => $stmt->fetchAll(),
            'total' => $total
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener empleados: ' . $e->getMessage()]);
    }
}

function handleGetEmployee($pdo, $id) {
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM empleados WHERE id = ?");
        $stmt->execute([$id]);
        $employee = $stmt->fetch();
        
        if ($employee) {
            echo json_encode(['success' => true, 'employee' => $employee]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Empleado no encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener empleado: ' . $e->getMessage()]);
    }
}

function handleAddEmployee($pdo, $data) {
    $required = ['firstName', 'lastName', 'email', 'department', 'salary'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Falta el campo $field"]);
            return;
        }
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO empleados (firstName, lastName, email, department, salary) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['department'],
            $data['salary']
        ]);
        
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        $message = strpos($e->getMessage(), 'Duplicate entry') !== false 
            ? 'El email ya está registrado' 
            : 'Error al agregar: ' . $e->getMessage();
        echo json_encode(['success' => false, 'message' => $message]);
    }
}

function handleUpdateEmployee($pdo, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    $required = ['firstName', 'lastName', 'email', 'department', 'salary'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Falta el campo $field"]);
            return;
        }
    }
    
    try {
        // Verificar email único
        $checkStmt = $pdo->prepare("SELECT id FROM empleados WHERE email = ? AND id != ?");
        $checkStmt->execute([$data['email'], $data['id']]);
        
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'El email ya está registrado']);
            return;
        }
        
        $stmt = $pdo->prepare("UPDATE empleados SET firstName=?, lastName=?, email=?, department=?, salary=? WHERE id=?");
        $stmt->execute([
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['department'],
            $data['salary'],
            $data['id']
        ]);
        
        echo json_encode(['success' => true, 'affected_rows' => $stmt->rowCount()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()]);
    }
}

function handleDeleteEmployee($pdo, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM empleados WHERE id = ?");
        $stmt->execute([$data['id']]);
        
        echo json_encode(['success' => $stmt->rowCount() > 0]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()]);
    }
}