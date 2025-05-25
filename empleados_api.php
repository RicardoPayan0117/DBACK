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
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
    exit;
}

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Obtener los datos de la solicitud
$input = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? $input['action'] ?? '';

switch ($action) {
    case 'get_employees':
        getEmployees($pdo);
        break;
    case 'get_employee':
        getEmployee($pdo);
        break;
    case 'add_employee':
        addEmployee($pdo, $input);
        break;
    case 'update_employee':
        updateEmployee($pdo, $input);
        break;
    case 'delete_employee':
        deleteEmployee($pdo, $input);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}

function getEmployees($pdo) {
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $limit = 5;
    $offset = ($page - 1) * $limit;
    
    $where = '';
    $params = [];
    
    if (!empty($search)) {
        $where = "WHERE firstName LIKE :search OR lastName LIKE :search OR email LIKE :search OR department LIKE :search";
        $params[':search'] = "%$search%";
    }
    
    try {
        // Obtener el total de empleados
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM empleados $where");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        // Obtener los empleados para la página actual
        $stmt = $pdo->prepare("SELECT * FROM empleados $where ORDER BY id LIMIT :limit OFFSET :offset");
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $employees = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'employees' => $employees,
            'total' => $total
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener empleados: ' . $e->getMessage()]);
    }
}

function getEmployee($pdo) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
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

function addEmployee($pdo, $data) {
    $firstName = $data['firstName'] ?? '';
    $lastName = $data['lastName'] ?? '';
    $email = $data['email'] ?? '';
    $department = $data['department'] ?? '';
    $salary = isset($data['salary']) ? floatval($data['salary']) : 0;
    
    // Validación
    if (empty($firstName) || empty($lastName) || empty($email) || empty($department) || $salary <= 0) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos y el salario debe ser mayor a 0']);
        return;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'El email no es válido']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO empleados (firstName, lastName, email, department, salary) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $email, $department, $salary]);
        
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        // Manejo específico de errores de duplicado de email
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo json_encode(['success' => false, 'message' => 'El email ya está registrado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agregar empleado: ' . $e->getMessage()]);
        }
    }
}

function updateEmployee($pdo, $data) {
    $id = isset($data['id']) ? intval($data['id']) : 0;
    $firstName = $data['firstName'] ?? '';
    $lastName = $data['lastName'] ?? '';
    $email = $data['email'] ?? '';
    $department = $data['department'] ?? '';
    $salary = isset($data['salary']) ? floatval($data['salary']) : 0;
    
    // Validación
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID de empleado no válido']);
        return;
    }
    
    if (empty($firstName) || empty($lastName) || empty($email) || empty($department) || $salary <= 0) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos y el salario debe ser mayor a 0']);
        return;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'El email no es válido']);
        return;
    }
    
    try {
        // Verificar si el email ya existe para otro empleado
        $checkStmt = $pdo->prepare("SELECT id FROM empleados WHERE email = ? AND id != ?");
        $checkStmt->execute([$email, $id]);
        
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'El email ya está registrado para otro empleado']);
            return;
        }
        
        $stmt = $pdo->prepare("UPDATE empleados SET firstName = ?, lastName = ?, email = ?, department = ?, salary = ? WHERE id = ?");
        $stmt->execute([$firstName, $lastName, $email, $department, $salary, $id]);
        
        echo json_encode(['success' => true, 'affected_rows' => $stmt->rowCount()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar empleado: ' . $e->getMessage()]);
    }
}

function deleteEmployee($pdo, $data) {
    $id = isset($data['id']) ? intval($data['id']) : 0;
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID de empleado no válido']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM empleados WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => $stmt->rowCount() > 0, 'affected_rows' => $stmt->rowCount()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar empleado: ' . $e->getMessage()]);
    }
}