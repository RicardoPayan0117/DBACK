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
        $where = "WHERE nombre LIKE :search OR apellido_paterno LIKE :search OR apellido_materno LIKE :search OR cargo LIKE :search OR email LIKE :search";
        $params[':search'] = "%$search%";
    }
    
    try {
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM personal $where");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        $stmt = $pdo->prepare("SELECT * FROM personal $where ORDER BY id LIMIT :limit OFFSET :offset");
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
        $stmt = $pdo->prepare("SELECT * FROM personal WHERE id = ?");
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
    $required = ['nombre', 'apellido_paterno', 'apellido_materno', 'usuario', 'contraseña', 'cargo', 'sueldo_diario', 'telefono', 'email'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Falta el campo $field"]);
            return;
        }
    }

    try {
        // Verifica que no se repita el email
        $checkStmt = $pdo->prepare("SELECT id FROM personal WHERE email = ?");
        $checkStmt->execute([$data['email']]);
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'El email ya está registrado']);
            return;
        }

        // Hashear la contraseña
        $hashedPassword = password_hash($data['contraseña'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO personal (nombre, apellido_paterno, apellido_materno, usuario, contraseña, cargo, sueldo_diario, telefono, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['apellido_paterno'],
            $data['apellido_materno'],
            $data['usuario'],
            $hashedPassword, // Aquí va el hash
            $data['cargo'],
            $data['sueldo_diario'],
            $data['telefono'],
            $data['email']
        ]);

        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al agregar: ' . $e->getMessage()]);
    }
}

function handleUpdateEmployee($pdo, $data) {
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }

    $required = ['nombre', 'apellido_paterno', 'apellido_materno', 'usuario', 'contraseña', 'cargo', 'sueldo_diario', 'telefono', 'email'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Falta el campo $field"]);
            return;
        }
    }

    try {
        // Verificar email único
        $checkStmt = $pdo->prepare("SELECT id FROM personal WHERE email = ? AND id != ?");
        $checkStmt->execute([$data['email'], $data['id']]);
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'El email ya está registrado']);
            return;
        }

        // Hashear la contraseña
        $hashedPassword = password_hash($data['contraseña'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE personal SET nombre=?, apellido_paterno=?, apellido_materno=?, usuario=?, contraseña=?, cargo=?, sueldo_diario=?, telefono=?, email=? WHERE id=?");
        $stmt->execute([
            $data['nombre'],
            $data['apellido_paterno'],
            $data['apellido_materno'],
            $data['usuario'],
            $hashedPassword, // Aquí va el hash
            $data['cargo'],
            $data['sueldo_diario'],
            $data['telefono'],
            $data['email'],
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
        $stmt = $pdo->prepare("DELETE FROM personal WHERE id = ?");
        $stmt->execute([$data['id']]);

        echo json_encode(['success' => $stmt->rowCount() > 0]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()]);
    }
}
