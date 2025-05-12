<?php
// Conexión a la base de datos (comentada para seguridad)
/*
$host = 'localhost';          // Servidor de la base de datos
$dbname = 'empresa_gruas';    // Nombre de la base de datos
$username = 'tu_usuario';     // Usuario de MySQL
$password = 'tu_contraseña';  // Contraseña de MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
*/
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gastos - Empresa de Grúas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0;
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
        }
        
        .sidebar_header {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar_icon {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }
        
        .sidebar_icon--logo {
            width: 40px;
            height: 40px;
        }
        
        .sidebar_text {
            font-size: 16px;
        }
        
        .sidebar_list {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }
        
        .sidebar_element {
            padding: 15px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
        }
        
        .sidebar_element:hover {
            background-color: #34495e;
        }
        
        .sidebar_link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            width: 100%;
        }
        
        .sidebar_footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar_title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .sidebar_info {
            font-size: 14px;
            opacity: 0.8;
        }
        
        /* Main content styles */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            background-color: #f5f5f5;
        }
        
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .section { 
            margin-bottom: 40px; 
            border: 1px solid #ddd; 
            padding: 20px; 
            border-radius: 5px; 
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        
        th, td { 
            padding: 10px; 
            text-align: left; 
            border-bottom: 1px solid #ddd; 
        }
        
        th { 
            background-color: #f2f2f2; 
        }
        
        .form-group { 
            margin-bottom: 15px; 
        }
        
        label { 
            display: block; 
            margin-bottom: 5px; 
        }
        
        input, select, textarea { 
            width: 100%; 
            padding: 8px; 
            box-sizing: border-box; 
        }
        
        button { 
            background-color: #4CAF50; 
            color: white; 
            padding: 10px 15px; 
            border: none; 
            cursor: pointer; 
        }
        
        button:hover { 
            background-color: #45a049; 
        }
        
        .success { 
            color: green; 
            margin: 10px 0; 
        }
        
        .error { 
            color: red; 
            margin: 10px 0; 
        }
        
        .tabs { 
            display: flex; 
            margin-bottom: 20px; 
        }
        
        .tab { 
            padding: 10px 20px; 
            cursor: pointer; 
            background-color: #f1f1f1; 
            margin-right: 5px; 
        }
        
        .tab.active { 
            background-color: #4CAF50; 
            color: white; 
        }
        
        .tab-content { 
            display: none; 
        }
        
        .tab-content.active { 
            display: block; 
        }
        
        .charts-container { 
            margin-top: 30px; 
        }
        
        .chart-container { 
            width: 48%; 
            display: inline-block; 
        }
        
        .chart-container:last-child { 
            margin-left: 4%; 
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
   <!-- Barra lateral con efecto de texto emergente -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar_header">
        <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo" alt="Logo DBACK">
        <span class="sidebar_text">Grúas DBACK</span>
    </div>

    <ul class="sidebar_list">
        <li class="sidebar_element">
            <a href="#" class="sidebar_link">
                <i class="fas fa-home sidebar_icon"></i>
                <span class="sidebar_text">Inicio</span>
            </a>
        </li>
        
        <li class="sidebar_element">
            <a href="Gruas.php" class="sidebar_link">
                <i class="fas fa-truck sidebar_icon"></i>
                <span class="sidebar_text">Grúas</span>
            </a>
        </li>
        
        <li class="sidebar_element">
            <a href="Gastos.php" class="sidebar_link">
                <i class="fas fa-money-bill-wave sidebar_icon"></i>
                <span class="sidebar_text">Gastos</span>
            </a>
        </li>
        
        <li class="sidebar_element">
            <a href="Empleados.html" class="sidebar_link">
                <i class="fas fa-users sidebar_icon"></i>
                <span class="sidebar_text">Empleados</span>
            </a>
        </li>

        <li class="sidebar_element">
            <a href="#" class="sidebar_link">
                <i class="fas fa-cog sidebar_icon"></i>
                <span class="sidebar_text">Configuración</span>
            </a>
        </li>
    </ul>

    <div class="sidebar_footer">
        <div class="sidebar_element">
            <i class="fas fa-user-circle sidebar_icon"></i>
            <div class="sidebar_user-info">
                <div class="sidebar_text sidebar_title">Ricardo Payán</div>
                <div class="sidebar_text sidebar_info">Ingeniero de Software</div>
            </div>
        </div>
    </div>
</aside>

<style>
    /* Estilos para el efecto de texto emergente */
    .sidebar {
        width: 70px;
        position: fixed;
        height: 100vh;
        transition: width 0.3s ease;
        z-index: 1000;
        overflow: hidden;
    }
    
    .sidebar:hover {
        width: 250px;
    }
    
    .sidebar_text {
        opacity: 0;
        transition: opacity 0.2s ease;
        margin-left: 10px;
        white-space: nowrap;
    }
    
    .sidebar:hover .sidebar_text {
        opacity: 1;
        transition-delay: 0.1s;
    }
    
    .sidebar_element {
        display: flex;
        align-items: center;
    }
    
    .sidebar_link {
        display: flex;
        align-items: center;
    }
    
    .sidebar_user-info {
        opacity: 0;
        transition: opacity 0.2s ease;
        margin-left: 10px;
    }
    
    .sidebar:hover .sidebar_user-info {
        opacity: 1;
        transition-delay: 0.1s;
    }
    
    .main-content {
        margin-left: 70px;
        transition: margin-left 0.3s ease;
    }
    
    .sidebar:hover ~ .main-content {
        margin-left: 250px;
    }
</style>

<script>
    // Opcional: Mejorar la experiencia en móviles
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        
        // Para dispositivos táctiles
        if ('ontouchstart' in window) {
            let isExpanded = false;
            
            sidebar.addEventListener('click', function(e) {
                if (e.target.closest('.sidebar_element') || e.target.closest('.sidebar_footer')) {
                    return; // No hacer nada si se hace clic en un elemento interactivo
                }
                
                isExpanded = !isExpanded;
                this.style.width = isExpanded ? '250px' : '70px';
                document.querySelector('.main-content').style.marginLeft = 
                    isExpanded ? '250px' : '70px';
            });
        }
    });
</script>
<!-- Barra lateral con efecto de texto emergente -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar_header">
        <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo" alt="Logo DBACK">
        <span class="sidebar_text">Grúas DBACK</span>
    </div>

    <ul class="sidebar_list">
        <li class="sidebar_element">
            <a href="MenuAdmin.PHP" class="sidebar_link">  <!-- Enlace modificado -->
                <i class="fas fa-home sidebar_icon"></i>
                <span class="sidebar_text">Inicio</span>
            </a>
        </li>
        
        <li class="sidebar_element">
            <a href="Gruas.php" class="sidebar_link">
                <i class="fas fa-truck sidebar_icon"></i>
                <span class="sidebar_text">Grúas</span>
            </a>
        </li>
        
        <li class="sidebar_element">
            <a href="Gastos.php" class="sidebar_link">
                <i class="fas fa-money-bill-wave sidebar_icon"></i>
                <span class="sidebar_text">Gastos</span>
            </a>
        </li>
        
        <li class="sidebar_element">
            <a href="Empleados.html" class="sidebar_link">
                <i class="fas fa-users sidebar_icon"></i>
                <span class="sidebar_text">Empleados</span>
            </a>
        </li>

        
             <li class="sidebar_element" onclick="showSection('panel-solicitud')">
                <a href="panel-solicitud.html" class="sidebar_link">
                    <i class="fas fa-users sidebar_icon"></i>
                    <span class="sidebar_text">panel-solicitud</span>
                </a>
            </li>

    </ul>

    <div class="sidebar_footer">
        <div class="sidebar_element">
            <i class="fas fa-user-circle sidebar_icon"></i>
            <div class="sidebar_user-info">
                <div class="sidebar_text sidebar_title">Ricardo Payán</div>
                <div class="sidebar_text sidebar_info">Ingeniero de Software</div>
            </div>
        </div>
    </div>
</aside>

<style>
    /* Estilos para el efecto de texto emergente */
    .sidebar {
        width: 70px;
        position: fixed;
        height: 100vh;
        transition: width 0.3s ease;
        z-index: 1000;
        overflow: hidden;
    }
    
    .sidebar:hover {
        width: 250px;
    }
    
    .sidebar_text {
        opacity: 0;
        transition: opacity 0.2s ease;
        margin-left: 10px;
        white-space: nowrap;
    }
    
    .sidebar:hover .sidebar_text {
        opacity: 1;
        transition-delay: 0.1s;
    }
    
    .sidebar_element {
        display: flex;
        align-items: center;
    }
    
    .sidebar_link {
        display: flex;
        align-items: center;
    }
    
    .sidebar_user-info {
        opacity: 0;
        transition: opacity 0.2s ease;
        margin-left: 10px;
    }
    
    .sidebar:hover .sidebar_user-info {
        opacity: 1;
        transition-delay: 0.1s;
    }
    
    .main-content {
        margin-left: 70px;
        transition: margin-left 0.3s ease;
    }
    
    .sidebar:hover ~ .main-content {
        margin-left: 250px;
    }
</style>

<script>
    // Opcional: Mejorar la experiencia en móviles
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        
        // Para dispositivos táctiles
        if ('ontouchstart' in window) {
            let isExpanded = false;
            
            sidebar.addEventListener('click', function(e) {
                if (e.target.closest('.sidebar_element') || e.target.closest('.sidebar_footer')) {
                    return; // No hacer nada si se hace clic en un elemento interactivo
                }
                
                isExpanded = !isExpanded;
                this.style.width = isExpanded ? '250px' : '70px';
                document.querySelector('.main-content').style.marginLeft = 
                    isExpanded ? '250px' : '70px';
            });
        }
    });
</script>
    <div class="main-content">
        <div class="container">
            <h1>Sistema de Gestión de Gastos - Empresa de Grúas</h1>
            
            <div class="tabs">
                <div class="tab active" onclick="openTab('registrar')">Registrar Gasto</div>
                <div class="tab" onclick="openTab('listar')">Listar Gastos</div>
                <div class="tab" onclick="openTab('reportes')">Reportes</div>
            </div>
            
            <!-- Sección Registrar Gasto -->
            <div id="registrar" class="tab-content section active">
                <h2>Registrar Nuevo Gasto</h2>
                
                <form method="post" enctype="multipart/form-data">
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
                        <label for="categoria_id">Categoría:</label>
                        <select id="categoria_id" name="categoria_id" required>
                            <option value="">-- Seleccione --</option>
                            <?php
                            /*
                            $stmt = $pdo->query("SELECT id, nombre FROM categorias_gastos ORDER BY nombre");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                            }
                            */
                            // Datos de ejemplo (simulados)
                            $categorias = [
                                ['id' => 1, 'nombre' => 'Combustible'],
                                ['id' => 2, 'nombre' => 'Mantenimiento'],
                                ['id' => 3, 'nombre' => 'Seguros'],
                                ['id' => 4, 'nombre' => 'Peajes'],
                                ['id' => 5, 'nombre' => 'Personal'],
                            ];
                            
                            foreach ($categorias as $categoria) {
                                echo "<option value='{$categoria['id']}'>{$categoria['nombre']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="vehiculo_id">Vehículo (opcional):</label>
                        <select id="vehiculo_id" name="vehiculo_id">
                            <option value="">-- Seleccione --</option>
                            <?php
                            /*
                            $stmt = $pdo->query("SELECT id, matricula FROM vehiculos ORDER BY matricula");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$row['id']}'>{$row['matricula']}</option>";
                            }
                            */
                            // Datos de ejemplo (simulados)
                            $vehiculos = [
                                ['id' => 1, 'matricula' => 'ABC-123'],
                                ['id' => 2, 'matricula' => 'DEF-456'],
                                ['id' => 3, 'matricula' => 'GHI-789'],
                            ];
                            
                            foreach ($vehiculos as $vehiculo) {
                                echo "<option value='{$vehiculo['id']}'>{$vehiculo['matricula']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="factura">Factura (PDF/Imagen):</label>
                        <input type="file" id="factura" name="factura" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    
                    <button type="submit" name="registrar_gasto">Registrar Gasto</button>
                </form>
            </div>
            
            <!-- Sección Listar Gastos -->
            <div id="listar" class="tab-content section">
                <h2>Listado de Gastos</h2>
                
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Monto</th>
                            <th>Categoría</th>
                            <th>Vehículo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /*
                        $sql = "SELECT g.*, c.nombre as categoria, v.matricula 
                                FROM gastos g
                                LEFT JOIN categorias_gastos c ON g.categoria_id = c.id
                                LEFT JOIN vehiculos v ON g.vehiculo_id = v.id
                                ORDER BY g.fecha DESC
                                LIMIT 50";
                        
                        $stmt = $pdo->query($sql);
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $matricula = isset($row['matricula']) ? $row['matricula'] : 'N/A';
                            echo "<tr>
                                    <td>{$row['fecha']}</td>
                                    <td>{$row['concepto']}</td>
                                    <td>$" . number_format($row['monto'], 2) . "</td>
                                    <td>{$row['categoria']}</td>
                                    <td>{$matricula}</td>
                                  </tr>";
                        }
                        */
                        // Datos de ejemplo (simulados)
                        $gastos = [
                            [
                                'fecha' => '2023-10-15',
                                'concepto' => 'Cambio de aceite',
                                'monto' => 120.50,
                                'categoria' => 'Mantenimiento',
                                'matricula' => 'ABC-123'
                            ],
                            [
                                'fecha' => '2023-10-10',
                                'concepto' => 'Diesel',
                                'monto' => 85.75,
                                'categoria' => 'Combustible',
                                'matricula' => 'DEF-456'
                            ],
                            [
                                'fecha' => '2023-10-05',
                                'concepto' => 'Seguro mensual',
                                'monto' => 350.00,
                                'categoria' => 'Seguros',
                                'matricula' => null
                            ],
                        ];
                        
                        foreach ($gastos as $gasto) {
                            $matricula = isset($gasto['matricula']) ? $gasto['matricula'] : 'N/A';
                            echo "<tr>
                                    <td>{$gasto['fecha']}</td>
                                    <td>{$gasto['concepto']}</td>
                                    <td>$" . number_format($gasto['monto'], 2) . "</td>
                                    <td>{$gasto['categoria']}</td>
                                    <td>{$matricula}</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Sección Reportes -->
            <div id="reportes" class="tab-content section">
                <h2>Reportes de Gastos</h2>
                
                <form method="get" id="filtro-reporte">
                    <div class="form-group">
                        <label for="mes">Mes:</label>
                        <select id="mes" name="mes">
                            <?php 
                            $meses = [
                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                            ];
                            
                            foreach ($meses as $num => $nombre) {
                                $selected = ($num == date('n')) ? 'selected' : '';
                                echo "<option value='$num' $selected>$nombre</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="ano">Año:</label>
                        <select id="ano" name="ano">
                            <?php 
                            for ($i = date('Y') - 2; $i <= date('Y'); $i++) {
                                $selected = ($i == date('Y')) ? 'selected' : '';
                                echo "<option value='$i' $selected>$i</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <button type="button" onclick="generarReporte()">Generar Reporte</button>
                </form>
                
                <div class="charts-container">
                    <div class="chart-container">
                        <h3>Gastos por Categoría</h3>
                        <canvas id="chartCategorias" width="400" height="300"></canvas>
                    </div>
                    
                    <div class="chart-container">
                        <h3>Gastos por Vehículo</h3>
                        <canvas id="chartVehiculos" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Funcionalidad de pestañas
        function openTab(tabName) {
            const tabs = document.getElementsByClassName('tab');
            for (let i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            
            const tabContents = document.getElementsByClassName('tab-content');
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }
            
            document.querySelector(`.tab[onclick="openTab('${tabName}')"]`).classList.add('active');
            document.getElementById(tabName).classList.add('active');
        }
        
        // Generar reporte (simulado)
        function generarReporte() {
            // En una implementación real, esto haría una petición AJAX o recargaría la página con los parámetros
            alert("En un sistema real, esto generaría el reporte con los filtros seleccionados");
            
            // Datos de ejemplo para los gráficos
            const datosCategorias = {
                labels: ['Combustible', 'Mantenimiento', 'Seguros', 'Peajes', 'Personal'],
                datasets: [{
                    data: [1200, 850, 350, 280, 2200],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            };
            
            const datosVehiculos = {
                labels: ['ABC-123', 'DEF-456', 'GHI-789', 'Sin asignar'],
                datasets: [{
                    label: 'Gastos por Vehículo',
                    data: [850, 1200, 650, 1300],
                    backgroundColor: '#36A2EB'
                }]
            };
            
            // Renderizar gráficos
            renderChart('chartCategorias', 'pie', datosCategorias);
            renderChart('chartVehiculos', 'bar', datosVehiculos);
        }
        
        // Función para renderizar gráficos
        function renderChart(canvasId, type, data) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            // Destruir el gráfico anterior si existe
            if (window[canvasId + 'Chart']) {
                window[canvasId + 'Chart'].destroy();
            }
            
            // Crear nuevo gráfico
            window[canvasId + 'Chart'] = new Chart(ctx, {
                type: type,
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    },
                    ...(type === 'bar' ? {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    } : {})
                }
            });
        }
        
        // Generar reporte al cargar la página
        window.onload = function() {
            generarReporte();
        };
    </script>
</body>
</html>