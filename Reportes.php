<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'gestion_gastos_gruas';
$username = 'tu_usuario';
$password = 'tu_contraseña';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Crear tablas si no existen
function crearTablas($conn) {
    $sql = "
    CREATE TABLE IF NOT EXISTS categorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        descripcion TEXT
    );
    
    CREATE TABLE IF NOT EXISTS vehiculos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        matricula VARCHAR(20) NOT NULL,
        modelo VARCHAR(100),
        ano INT,
        estado VARCHAR(50)
    );
    
    CREATE TABLE IF NOT EXISTS gastos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fecha DATE NOT NULL,
        concepto VARCHAR(255) NOT NULL,
        monto DECIMAL(10,2) NOT NULL,
        categoria VARCHAR(100) NOT NULL,
        vehiculo_id INT,
        proveedor VARCHAR(255),
        factura VARCHAR(50),
        observaciones TEXT,
        creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";
    
    $conn->exec($sql);
    
    // Insertar categorías básicas si no existen
    $count = $conn->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
    if ($count == 0) {
        $sql = "INSERT INTO categorias (nombre, descripcion) VALUES 
                ('Combustible', 'Gastos en combustible para los vehículos'),
                ('Mantenimiento', 'Reparaciones y mantenimiento de grúas'),
                ('Peajes', 'Peajes en carreteras'),
                ('Personal', 'Gastos relacionados con el personal'),
                ('Seguros', 'Pagos de seguros de vehículos'),
                ('Impuestos', 'Impuestos y tasas'),
                ('Otros', 'Otros gastos varios')";
        $conn->exec($sql);
    }
}

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['registrar_gasto'])) {
        // Registrar nuevo gasto
        $fecha = $_POST['fecha'];
        $concepto = $_POST['concepto'];
        $monto = $_POST['monto'];
        $categoria = $_POST['categoria'];
        $vehiculo = !empty($_POST['vehiculo']) ? $_POST['vehiculo'] : NULL;
        $proveedor = $_POST['proveedor'];
        $factura = $_POST['factura'];
        $observaciones = $_POST['observaciones'];
        
        try {
            $sql = "INSERT INTO gastos (fecha, concepto, monto, categoria, vehiculo_id, proveedor, factura, observaciones) 
                    VALUES (:fecha, :concepto, :monto, :categoria, :vehiculo_id, :proveedor, :factura, :observaciones)";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':fecha' => $fecha,
                ':concepto' => $concepto,
                ':monto' => $monto,
                ':categoria' => $categoria,
                ':vehiculo_id' => $vehiculo,
                ':proveedor' => $proveedor,
                ':factura' => $factura,
                ':observaciones' => $observaciones
            ]);
            
            $mensaje = "Gasto registrado correctamente!";
        } catch(PDOException $e) {
            $error = "Error al registrar el gasto: " . $e->getMessage();
        }
    } elseif (isset($_POST['agregar_vehiculo'])) {
        // Agregar nuevo vehículo
        $matricula = $_POST['matricula'];
        $modelo = $_POST['modelo'];
        $ano = $_POST['ano'];
        $estado = $_POST['estado'];
        
        try {
            $sql = "INSERT INTO vehiculos (matricula, modelo, ano, estado) 
                    VALUES (:matricula, :modelo, :ano, :estado)";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':matricula' => $matricula,
                ':modelo' => $modelo,
                ':ano' => $ano,
                ':estado' => $estado
            ]);
            
            $mensaje = "Vehículo agregado correctamente!";
        } catch(PDOException $e) {
            $error = "Error al agregar vehículo: " . $e->getMessage();
        }
    }
}

// Obtener datos para las vistas
$categorias = $conn->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
$vehiculos = $conn->query("SELECT * FROM vehiculos")->fetchAll(PDO::FETCH_ASSOC);

// Obtener gastos según filtros
$filtros = [];
$where = "1=1";

if (isset($_GET['fecha_inicio']) && !empty($_GET['fecha_inicio'])) {
    $where .= " AND fecha >= :fecha_inicio";
    $filtros[':fecha_inicio'] = $_GET['fecha_inicio'];
}

if (isset($_GET['fecha_fin']) && !empty($_GET['fecha_fin'])) {
    $where .= " AND fecha <= :fecha_fin";
    $filtros[':fecha_fin'] = $_GET['fecha_fin'];
}

if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
    $where .= " AND categoria = :categoria";
    $filtros[':categoria'] = $_GET['categoria'];
}

if (isset($_GET['vehiculo']) && !empty($_GET['vehiculo'])) {
    $where .= " AND vehiculo_id = :vehiculo";
    $filtros[':vehiculo'] = $_GET['vehiculo'];
}

$sql_gastos = "SELECT g.*, v.matricula 
               FROM gastos g 
               LEFT JOIN vehiculos v ON g.vehiculo_id = v.id 
               WHERE $where 
               ORDER BY g.fecha DESC";

$stmt_gastos = $conn->prepare($sql_gastos);
foreach ($filtros as $key => $value) {
    $stmt_gastos->bindValue($key, $value);
}
$stmt_gastos->execute();
$gastos = $stmt_gastos->fetchAll(PDO::FETCH_ASSOC);

// Calcular total de gastos
$total_gastos = 0;
foreach ($gastos as $gasto) {
    $total_gastos += $gasto['monto'];
}

// Datos para gráfico
$datos_grafico = $conn->query("SELECT categoria, SUM(monto) as total FROM gastos GROUP BY categoria")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Gestión de Gastos - Empresa de Grúas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h2, h3 { color: #333; }
        .form-section { margin-bottom: 30px; padding: 20px; background: #f9f9f9; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button, .btn { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        button:hover, .btn:hover { background-color: #45a049; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .tabs { display: flex; margin-bottom: 20px; }
        .tab { padding: 10px 20px; background: #ddd; margin-right: 5px; cursor: pointer; border-radius: 5px 5px 0 0; }
        .tab.active { background: #4CAF50; color: white; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background-color: #dff0d8; color: #3c763d; }
        .alert-error { background-color: #f2dede; color: #a94442; }
        .grafico-container { width: 80%; margin: 30px auto; }
        .filtros { background-color: #e9e9e9; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function mostrarTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
            document.querySelector(`.tab[onclick="mostrarTab('${tabId}')"]`).classList.add('active');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            mostrarTab('registro-gastos');
            
            // Configurar gráfico
            const ctx = document.getElementById('graficoGastos').getContext('2d');
            const categorias = <?php echo json_encode(array_column($datos_grafico, 'categoria')); ?>;
            const montos = <?php echo json_encode(array_column($datos_grafico, 'total')); ?>;
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: categorias,
                    datasets: [{
                        data: montos,
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                            '#9966FF', '#FF9F40', '#8AC24A', '#607D8B'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Distribución de Gastos por Categoría'
                        }
                    }
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Sistema de Gestión de Gastos - Empresa de Grúas</h1>
        
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="tabs">
            <div class="tab active" onclick="mostrarTab('registro-gastos')">Registro de Gastos</div>
            <div class="tab" onclick="mostrarTab('gestion-vehiculos')">Gestión de Vehículos</div>
            <div class="tab" onclick="mostrarTab('reportes')">Reportes</div>
        </div>
        
        <!-- Pestaña de Registro de Gastos -->
        <div id="registro-gastos" class="tab-content active">
            <h2>Registrar Nuevo Gasto</h2>
            
            <form method="post" class="form-section">
                <input type="hidden" name="registrar_gasto" value="1">
                
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" required>
                </div>
                
                <div class="form-group">
                    <label for="concepto">Concepto:</label>
                    <input type="text" id="concepto" name="concepto" required>
                </div>
                
                <div class="form-group">
                    <label for="monto">Monto ($):</label>
                    <input type="number" id="monto" name="monto" step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select id="categoria" name="categoria" required>
                        <option value="">Seleccione una categoría</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['nombre']; ?>"><?php echo $categoria['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="vehiculo">Vehículo (opcional):</label>
                    <select id="vehiculo" name="vehiculo">
                        <option value="">No aplica</option>
                        <?php foreach ($vehiculos as $vehiculo): ?>
                            <option value="<?php echo $vehiculo['id']; ?>"><?php echo $vehiculo['matricula']; ?> - <?php echo $vehiculo['modelo']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="proveedor">Proveedor:</label>
                    <input type="text" id="proveedor" name="proveedor">
                </div>
                
                <div class="form-group">
                    <label for="factura">N° Factura/Recibo:</label>
                    <input type="text" id="factura" name="factura">
                </div>
                
                <div class="form-group">
                    <label for="observaciones">Observaciones:</label>
                    <textarea id="observaciones" name="observaciones" rows="3"></textarea>
                </div>
                
                <button type="submit">Registrar Gasto</button>
            </form>
            
            <h2>Últimos Gastos Registrados</h2>
            <table>
                <tr>
                    <th>Fecha</th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Categoría</th>
                    <th>Vehículo</th>
                </tr>
                <?php foreach (array_slice($gastos, 0, 5) as $gasto): ?>
                <tr>
                    <td><?php echo $gasto['fecha']; ?></td>
                    <td><?php echo $gasto['concepto']; ?></td>
                    <td>$<?php echo number_format($gasto['monto'], 2); ?></td>
                    <td><?php echo $gasto['categoria']; ?></td>
                    <td><?php echo $gasto['matricula'] ?? 'N/A'; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
        <!-- Pestaña de Gestión de Vehículos -->
        <div id="gestion-vehiculos" class="tab-content">
            <h2>Agregar Nuevo Vehículo</h2>
            
            <form method="post" class="form-section">
                <input type="hidden" name="agregar_vehiculo" value="1">
                
                <div class="form-group">
                    <label for="matricula">Matrícula:</label>
                    <input type="text" id="matricula" name="matricula" required>
                </div>
                
                <div class="form-group">
                    <label for="modelo">Modelo:</label>
                    <input type="text" id="modelo" name="modelo" required>
                </div>
                
                <div class="form-group">
                    <label for="ano">Año:</label>
                    <input type="number" id="ano" name="ano" min="1900" max="<?php echo date('Y'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado" required>
                        <option value="Activo">Activo</option>
                        <option value="En mantenimiento">En mantenimiento</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                
                <button type="submit">Agregar Vehículo</button>
            </form>
            
            <h2>Vehículos Registrados</h2>
            <table>
                <tr>
                    <th>Matrícula</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Estado</th>
                </tr>
                <?php foreach ($vehiculos as $vehiculo): ?>
                <tr>
                    <td><?php echo $vehiculo['matricula']; ?></td>
                    <td><?php echo $vehiculo['modelo']; ?></td>
                    <td><?php echo $vehiculo['ano']; ?></td>
                    <td><?php echo $vehiculo['estado']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
        <!-- Pestaña de Reportes -->
        <div id="reportes" class="tab-content">
            <h2>Reportes de Gastos</h2>
            
            <div class="filtros">
                <form method="get">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha Inicio:</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo $_GET['fecha_inicio'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_fin">Fecha Fin:</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo $_GET['fecha_fin'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="categoria">Categoría:</label>
                        <select id="categoria" name="categoria">
                            <option value="">Todas</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo $categoria['nombre']; ?>" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] == $categoria['nombre']) ? 'selected' : ''; ?>>
                                    <?php echo $categoria['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="vehiculo">Vehículo:</label>
                        <select id="vehiculo" name="vehiculo">
                            <option value="">Todos</option>
                            <?php foreach ($vehiculos as $vehiculo): ?>
                                <option value="<?php echo $vehiculo['id']; ?>" <?php echo (isset($_GET['vehiculo']) && $_GET['vehiculo'] == $vehiculo['id']) ? 'selected' : ''; ?>>
                                    <?php echo $vehiculo['matricula']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit">Filtrar</button>
                    <a href="?" class="btn">Limpiar</a>
                </form>
            </div>
            
            <h3>Total gastado: $<?php echo number_format($total_gastos, 2); ?></h3>
            
            <div class="grafico-container">
                <canvas id="graficoGastos"></canvas>
            </div>
            
            <h3>Detalle de Gastos</h3>
            <table>
                <tr>
                    <th>Fecha</th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Categoría</th>
                    <th>Vehículo</th>
                    <th>Proveedor</th>
                    <th>Factura</th>
                </tr>
                <?php if (count($gastos) > 0): ?>
                    <?php foreach ($gastos as $gasto): ?>
                    <tr>
                        <td><?php echo $gasto['fecha']; ?></td>
                        <td><?php echo $gasto['concepto']; ?></td>
                        <td>$<?php echo number_format($gasto['monto'], 2); ?></td>
                        <td><?php echo $gasto['categoria']; ?></td>
                        <td><?php echo $gasto['matricula'] ?? 'N/A'; ?></td>
                        <td><?php echo $gasto['proveedor'] ?? '-'; ?></td>
                        <td><?php echo $gasto['factura'] ?? '-'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No se encontraron gastos con los filtros seleccionados</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>
</html>