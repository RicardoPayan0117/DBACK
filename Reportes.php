<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Gastos - Empresa de Grúas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h2, h3 { color: #333; }
        .form-group { margin-bottom: 15px; display: inline-block; margin-right: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button, .btn { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover, .btn:hover { background-color: #45a049; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .grafico-container { width: 80%; margin: 30px auto; }
        .filtros { background-color: #e9e9e9; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .resumen { background-color: #e6f7ff; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Reportes de Gastos - Empresa de Grúas</h1>
        
        <div class="filtros">
            <h2>Filtrar Reporte</h2>
            <form method="get">
                <div class="form-group">
                    <label for="fecha_inicio">Fecha Inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" value="">
                </div>
                
                <div class="form-group">
                    <label for="fecha_fin">Fecha Fin:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" value="">
                </div>
                
                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select id="categoria" name="categoria">
                        <option value="">Todas las categorías</option>
                        <option value="Combustible">Combustible</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Peajes">Peajes</option>
                        <option value="Personal">Personal</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="vehiculo">Vehículo:</label>
                    <select id="vehiculo" name="vehiculo">
                        <option value="">Todos los vehículos</option>
                        <option value="ABC123">ABC123 - Ford F150</option>
                        <option value="XYZ789">XYZ789 - Chevrolet Silverado</option>
                    </select>
                </div>
                
                <button type="submit">Generar Reporte</button>
                <a href="?" class="btn">Limpiar Filtros</a>
            </form>
        </div>
        
        <div class="resumen">
            <h2>Resumen del Reporte</h2>
            <p><strong>Total gastado:</strong> $255.50</p>
            <p><strong>Cantidad de registros:</strong> 3</p>
            <p><strong>Período:</strong> 2023-05-10 al 2023-05-15</p>
        </div>
        
        <div class="grafico-container">
            <canvas id="graficoGastos"></canvas>
        </div>
        
        <h2>Detalle de Gastos</h2>
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
            <tr>
                <td>2023-05-15</td>
                <td>Gasolina</td>
                <td>$150.00</td>
                <td>Combustible</td>
                <td>ABC123</td>
                <td>Estación Shell</td>
                <td>FAC-001</td>
            </tr>
            <tr>
                <td>2023-05-14</td>
                <td>Cambio de aceite</td>
                <td>$80.00</td>
                <td>Mantenimiento</td>
                <td>XYZ789</td>
                <td>Taller Mecánico</td>
                <td>FAC-002</td>
            </tr>
            <tr>
                <td>2023-05-10</td>
                <td>Peaje autopista</td>
                <td>$25.50</td>
                <td>Peajes</td>
                <td>ABC123</td>
                <td>Autopista del Norte</td>
                <td>P-3456</td>
            </tr>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Datos estáticos para el gráfico
            const ctx = document.getElementById('graficoGastos').getContext('2d');
            const categorias = ["Combustible", "Mantenimiento", "Peajes"];
            const montos = [150, 80, 25.50];
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: categorias,
                    datasets: [{
                        label: 'Gastos por Categoría',
                        data: montos,
                        backgroundColor: '#4CAF50'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Distribución de Gastos'
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
                    }
                }
            });
        });
    </script>
</body>
</html>