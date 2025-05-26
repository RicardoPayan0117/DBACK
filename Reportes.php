<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Gastos | Grúas DBACK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-color: #dee2e6;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --border-radius: 4px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-color);
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .header h1 {
            color: var(--primary-color);
            margin: 0;
            font-size: 28px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .filters-panel {
            background-color: white;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .filters-title {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filters-title i {
            color: var(--secondary-color);
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: var(--transition);
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-size: 14px;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-secondary {
            background-color: var(--light-color);
            color: var(--dark-color);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background-color: #e9ecef;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border-left: 4px solid var(--secondary-color);
        }

        .summary-card h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: var(--primary-color);
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .summary-card .value {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .summary-card .description {
            font-size: 14px;
            color: #6c757d;
        }

        .chart-container {
            background: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .chart-title {
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--primary-color);
            font-size: 20px;
        }

        canvas {
            width: 100% !important;
            height: 400px !important;
        }

        .table-container {
            overflow-x: auto;
            background: white;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-primary {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
        }

        .badge-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .badge-danger {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
        }

        .actions-cell {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            background: none;
            border: none;
            color: var(--secondary-color);
            cursor: pointer;
            font-size: 16px;
            transition: var(--transition);
        }

        .action-btn:hover {
            color: #2980b9;
        }

        .action-btn.delete {
            color: var(--accent-color);
        }

        .action-btn.delete:hover {
            color: #c0392b;
        }

        .pagination {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            gap: 8px;
        }

        .pagination-btn {
            padding: 8px 12px;
            border-radius: 4px;
            background-color: white;
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: var(--transition);
        }

        .pagination-btn.active {
            background-color: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }

        .pagination-btn:hover:not(.active) {
            background-color: var(--light-color);
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .form-group {
                min-width: 100%;
            }
            
            .summary-cards {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .header-actions {
                width: 100%;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
     <style>
        /* Variables CSS */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --light-color: #f5f5f5;
            --text-color: #333;
            --text-light: #fff;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        /* Estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: var(--light-color);
            color: var(--text-color);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Sidebar mejorado */
        .sidebar {
            width: 60px;
            height: 100vh;
            background-color: var(--primary-color);
            color: var(--text-light);
            transition: var(--transition);
            overflow: hidden;
            position: fixed;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .sidebar:hover {
            width: 250px;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar_header {
            padding: 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar_list {
            list-style: none;
            padding: 15px 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar_element {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
            margin: 5px 10px;
            border-radius: var(--border-radius);
        }

        .sidebar_element:hover {
            background-color: var(--secondary-color);
            transform: translateX(5px);
        }

        .sidebar_element.active {
            background-color: var(--accent-color);
        }

        .sidebar_icon {
            width: 24px;
            height: 24px;
            margin-right: 15px;
            color: var(--text-light);
            flex-shrink: 0;
            text-align: center;
        }

        .sidebar_icon--logo {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .sidebar_text {
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.2s ease;
            font-weight: 500;
        }

        .sidebar:hover .sidebar_text {
            opacity: 1;
        }

        .sidebar_title {
            font-size: 16px;
            margin-bottom: 3px;
            font-weight: 600;
        }

        .sidebar_info {
            font-size: 11px;
            opacity: 0.8;
        }

        .sidebar_footer {
            padding: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Contenido principal mejorado */
        .main-content {
            flex: 1;
            margin-left: 60px;
            padding: 30px;
            transition: var(--transition);
            min-height: 100vh;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 250px;
        }

        .content-section {
            display: none;
            background: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .active {
            display: block;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        h1 {
            color: var(--primary-color);
            font-size: 28px;
            margin: 0;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 20px;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        /* Mejoras de accesibilidad */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        /* Responsive mejorado */
        @media (max-width: 992px) {
            .main-content {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 50px;
            }
            
            .main-content {
                margin-left: 50px;
                padding: 15px;
            }
            
            .sidebar:hover {
                width: 220px;
            }
            
            .sidebar:hover ~ .main-content {
                margin-left: 220px;
            }
            
            .card-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
                height: 60px;
                bottom: 0;
                top: auto;
                flex-direction: row;
                overflow-x: auto;
            }
            
            .sidebar:hover {
                width: 100%;
                height: 60px;
            }
            
            .sidebar_header, 
            .sidebar_footer {
                display: none;
            }
            
            .sidebar_list {
                display: flex;
                padding: 0;
                flex-grow: 1;
            }
            
            .sidebar_element {
                flex-direction: column;
                padding: 10px;
                margin: 0 5px;
                min-width: 60px;
            }
            
            .sidebar_icon {
                margin-right: 0;
                margin-bottom: 5px;
            }
            
            .sidebar_text {
                font-size: 10px;
                opacity: 1;
            }
            
            .sidebar:hover .sidebar_text {
                opacity: 1;
            }
            
            .main-content {
                margin-left: 0;
                margin-bottom: 60px;
            }
            
            .sidebar:hover ~ .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Barra lateral mejorada con ARIA -->
    <nav class="sidebar" aria-label="Menú principal">
        <div class="sidebar_header">
            <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo" alt="Logo DBACK" width="30" height="30">
            <span class="sidebar_text">Grúas DBACK</span>
        </div>

        <ul class="sidebar_list" role="menubar">
            <li class="sidebar_element" role="menuitem" onclick="showSection('dashboard')" tabindex="0" aria-label="Inicio">
                <i class="fas fa-home sidebar_icon" aria-hidden="true"></i>
                <span class="sidebar_text">Inicio</span>
            </li>
            
            <li class="sidebar_element" role="menuitem" onclick="showSection('gruas')" tabindex="0" aria-label="Grúas">
                <a href="Gruas.php" class="sidebar_link">
                    <i class="fas fa-truck sidebar_icon" aria-hidden="true"></i>
                    <span class="sidebar_text">Grúas</span>
                </a>
            </li>
            
            <li class="sidebar_element" role="menuitem" onclick="showSection('gastos')" tabindex="0" aria-label="Gastos">
                <a href="Gastos.php" class="sidebar_link">
                    <i class="fas fa-money-bill-wave sidebar_icon" aria-hidden="true"></i>
                    <span class="sidebar_text">Gastos</span>
                </a>
            </li>
            
            <li class="sidebar_element" role="menuitem" onclick="showSection('empleados')" tabindex="0" aria-label="Empleados">
                <a href="Empleados.php" class="sidebar_link">
                    <i class="fas fa-users sidebar_icon" aria-hidden="true"></i>
                    <span class="sidebar_text">Empleados</span>
                </a>
            </li>

            <li class="sidebar_element" role="menuitem" onclick="showSection('panel-solicitud')" tabindex="0" aria-label="Panel de solicitud">
                <a href="solicitud.html" class="sidebar_link">
                    <i class="fas fa-clipboard-list sidebar_icon" aria-hidden="true"></i>
                    <span class="sidebar_text">Panel de solicitud</span>
                </a>
            </li>
        </ul>

<?php
    session_start();
    $nombre = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'Usuario';
    $cargo  = isset($_SESSION['usuario_cargo']) ? $_SESSION['usuario_cargo'] : '';
?>
<div class="sidebar_footer">
    <div class="sidebar_element" role="contentinfo">
        <i class="fas fa-user-circle sidebar_icon" aria-hidden="true"></i>
        <div>
            <div class="sidebar_text sidebar_title"><?php echo htmlspecialchars($nombre); ?></div>
            <div class="sidebar_text sidebar_info"><?php echo htmlspecialchars($cargo); ?></div>
        </div>
    </div>
</div>

    </nav>

    <!-- Contenido principal mejorado -->
    <main class="main-content" id="main-content">
     
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-file-invoice-dollar"></i> Reportes de Gastos</h1>
            <div class="header-actions">
                <button class="btn btn-primary" id="exportPdf">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </button>
                <button class="btn btn-secondary" id="exportExcel">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </button>
            </div>
        </div>

        <div class="filters-panel">
            <h2 class="filters-title"><i class="fas fa-filter"></i> Filtros del Reporte</h2>
            <form id="reportForm">
                <div class="filter-row">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio">
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin">Fecha de Fin</label>
                        <input type="date" id="fecha_fin" name="fecha_fin">
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoría</label>
                        <select id="categoria" name="categoria">
                            <option value="">Todas las categorías</option>
                            <option value="Combustible">Combustible</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Peajes">Peajes</option>
                            <option value="Personal">Personal</option>
                            <option value="Seguros">Seguros</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                </div>
                <div class="filter-row">
                    <div class="form-group">
                        <label for="vehiculo">Vehículo</label>
                        <select id="vehiculo" name="vehiculo">
                            <option value="">Todos los vehículos</option>
                            <option value="ABC123">ABC123 - Ford F150</option>
                            <option value="XYZ789">XYZ789 - Chevrolet Silverado</option>
                            <option value="DEF456">DEF456 - International 4300</option>
                            <option value="GHI789">GHI789 - Kenworth T800</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="proveedor">Proveedor</label>
                        <select id="proveedor" name="proveedor">
                            <option value="">Todos los proveedores</option>
                            <option value="Estación Shell">Estación Shell</option>
                            <option value="Taller Mecánico">Taller Mecánico</option>
                            <option value="Autopista del Norte">Autopista del Norte</option>
                            <option value="Seguros XYZ">Seguros XYZ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="orden">Ordenar por</label>
                        <select id="orden" name="orden">
                            <option value="fecha_desc">Fecha (más reciente)</option>
                            <option value="fecha_asc">Fecha (más antigua)</option>
                            <option value="monto_desc">Monto (mayor a menor)</option>
                            <option value="monto_asc">Monto (menor a mayor)</option>
                        </select>
                    </div>
                </div>
                <div class="filter-row">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Generar Reporte
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-broom"></i> Limpiar Filtros
                    </button>
                </div>
            </form>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <h3><i class="fas fa-dollar-sign"></i> Total Gastado</h3>
                <div class="value">$5,245.75</div>
                <div class="description">En el período seleccionado</div>
            </div>
            <div class="summary-card">
                <h3><i class="fas fa-list-ol"></i> Registros</h3>
                <div class="value">23</div>
                <div class="description">Transacciones encontradas</div>
            </div>
            <div class="summary-card">
                <h3><i class="fas fa-gas-pump"></i> Combustible</h3>
                <div class="value">$3,120.50</div>
                <div class="description">59.5% del total</div>
            </div>
            <div class="summary-card">
                <h3><i class="fas fa-tools"></i> Mantenimiento</h3>
                <div class="value">$1,450.25</div>
                <div class="description">27.6% del total</div>
            </div>
        </div>

        <div class="chart-container">
            <h2 class="chart-title"><i class="fas fa-chart-bar"></i> Distribución de Gastos</h2>
            <canvas id="gastosChart"></canvas>
        </div>

        <div class="chart-container">
            <h2 class="chart-title"><i class="fas fa-chart-line"></i> Evolución Mensual</h2>
            <canvas id="evolucionChart"></canvas>
        </div>

        <div class="table-container">
            <h2><i class="fas fa-table"></i> Detalle de Gastos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Categoría</th>
                        <th>Vehículo</th>
                        <th>Proveedor</th>
                        <th>Factura</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>15/05/2023</td>
                        <td>Gasolina - Estación Shell</td>
                        <td>$150.00</td>
                        <td><span class="badge badge-primary">Combustible</span></td>
                        <td>ABC123 - Ford F150</td>
                        <td>Estación Shell</td>
                        <td>FAC-001</td>
                        <td class="actions-cell">
                            <button class="action-btn" title="Ver detalle"><i class="fas fa-eye"></i></button>
                            <button class="action-btn" title="Editar"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>14/05/2023</td>
                        <td>Cambio de aceite y filtros</td>
                        <td>$180.00</td>
                        <td><span class="badge badge-success">Mantenimiento</span></td>
                        <td>XYZ789 - Chevrolet Silverado</td>
                        <td>Taller Mecánico</td>
                        <td>FAC-002</td>
                        <td class="actions-cell">
                            <button class="action-btn" title="Ver detalle"><i class="fas fa-eye"></i></button>
                            <button class="action-btn" title="Editar"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>10/05/2023</td>
                        <td>Peaje autopista</td>
                        <td>$25.50</td>
                        <td><span class="badge badge-danger">Peajes</span></td>
                        <td>ABC123 - Ford F150</td>
                        <td>Autopista del Norte</td>
                        <td>P-3456</td>
                        <td class="actions-cell">
                            <button class="action-btn" title="Ver detalle"><i class="fas fa-eye"></i></button>
                            <button class="action-btn" title="Editar"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>08/05/2023</td>
                        <td>Pago de seguro mensual</td>
                        <td>$350.00</td>
                        <td><span class="badge badge-primary">Seguros</span></td>
                        <td>Todos</td>
                        <td>Seguros XYZ</td>
                        <td>SEG-0523</td>
                        <td class="actions-cell">
                            <button class="action-btn" title="Ver detalle"><i class="fas fa-eye"></i></button>
                            <button class="action-btn" title="Editar"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>05/05/2023</td>
                        <td>Compra de repuestos</td>
                        <td>$420.75</td>
                        <td><span class="badge badge-success">Mantenimiento</span></td>
                        <td>DEF456 - International 4300</td>
                        <td>Repuestos S.A.</td>
                        <td>REP-2023-05</td>
                        <td class="actions-cell">
                            <button class="action-btn" title="Ver detalle"><i class="fas fa-eye"></i></button>
                            <button class="action-btn" title="Editar"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="pagination">
                <button class="pagination-btn"><i class="fas fa-angle-double-left"></i></button>
                <button class="pagination-btn">1</button>
                <button class="pagination-btn active">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn"><i class="fas fa-angle-double-right"></i></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de distribución de gastos
            const gastosCtx = document.getElementById('gastosChart').getContext('2d');
            new Chart(gastosCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Combustible', 'Mantenimiento', 'Peajes', 'Seguros', 'Personal', 'Otros'],
                    datasets: [{
                        data: [3120.50, 1450.25, 325.00, 850.00, 300.00, 200.00],
                        backgroundColor: [
                            '#3498db',
                            '#2ecc71',
                            '#e74c3c',
                            '#f39c12',
                            '#9b59b6',
                            '#1abc9c'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: $${value.toFixed(2)} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de evolución mensual
            const evolucionCtx = document.getElementById('evolucionChart').getContext('2d');
            new Chart(evolucionCtx, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [
                        {
                            label: 'Combustible',
                            data: [2800, 2950, 3100, 3020, 3120, 0, 0, 0, 0, 0, 0, 0],
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Mantenimiento',
                            data: [1200, 1350, 1100, 1420, 1450, 0, 0, 0, 0, 0, 0, 0],
                            borderColor: '#2ecc71',
                            backgroundColor: 'rgba(46, 204, 113, 0.1)',
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Total',
                            data: [4500, 4800, 4700, 4950, 5245, 0, 0, 0, 0, 0, 0, 0],
                            borderColor: '#e74c3c',
                            backgroundColor: 'rgba(231, 76, 60, 0.1)',
                            fill: true,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Evolución de Gastos Mensuales 2023'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Monto ($)'
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });

            // Manejo de eventos
            document.getElementById('exportPdf').addEventListener('click', function() {
                alert('Generando reporte en PDF...');
                // Aquí iría la lógica para generar el PDF
            });

            document.getElementById('exportExcel').addEventListener('click', function() {
                alert('Generando reporte en Excel...');
                // Aquí iría la lógica para generar el Excel
            });

            // Configuración de fechas por defecto
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            
            document.getElementById('fecha_inicio').valueAsDate = firstDay;
            document.getElementById('fecha_fin').valueAsDate = today;
        });
    </script>
</body>
</html>