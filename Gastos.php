<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Gastos | Grúas DBACK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href=".\CSS\Gastos.CSS">
    
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
 $_SESSION['usuario_nombre'] = $usuario['nombre'];  // El campo que corresponda
 $_SESSION['usuario_cargo'] = $usuario['cargo'];
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