<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Grúas</title>
    <link rel="stylesheet" href=".\CSS\Gruas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilos para la barra lateral */
        .sidebar {
            width: 70px;
            position: fixed;
            height: 100vh;
            background-color: #2c3e50;
            color: white;
            transition: width 0.3s ease;
            z-index: 1000;
            overflow: hidden;
            left: 0;
            top: 0;
        }
        
        .sidebar:hover {
            width: 250px;
        }
        
        .sidebar_header {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar_icon {
            width: 30px;
            height: 30px;
            transition: all 0.3s ease;
        }
        
        .sidebar_icon--logo {
            width: 40px;
            height: 40px;
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
        
        .sidebar_list {
            list-style: none;
            padding: 20px 0;
        }
        
        .sidebar_element {
            padding: 10px 20px;
            display: flex;
            align-items: center;
        }
        
        .sidebar_link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            width: 100%;
        }
        
        .sidebar_link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar_footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
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
        
        .sidebar_title {
            font-weight: bold;
        }
        
        .sidebar_info {
            font-size: 0.8em;
            color: #ecf0f1;
        }
        
        /* Ajustes para el contenido principal */
        .main-content {
            margin-left: 70px;
            transition: margin-left 0.3s ease;
        }
        
        .sidebar:hover ~ .main-content {
            margin-left: 250px;
        }

        /* Estilos existentes */
        .main-action-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            margin: 20px 0;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .main-action-button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .action-button-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        header {
            background-color: #3498db;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
        }

        .back-button {
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-button:hover {
            background-color: #2c3e50;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            margin-bottom: 10px;
            color: #555;
        }

        .number {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
        }

        .filter-controls {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .search-box {
            display: flex;
            align-items: center;
        }

        .search-box input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 250px;
        }

        .filters {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filters select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .cranes-list {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            padding-bottom: 20px;
        }

        .pagination button {
            margin: 0 5px;
        }

        .current-page {
            background-color: #2980b9;
        }

        /* Estilos para modales */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .full-width {
            grid-column: span 2;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Estilos para pestañas */
        .tab-container {
            margin-bottom: 20px;
        }

        .tabs {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            border-bottom: 1px solid #ddd;
        }

        .tabs li {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
        }

        .tabs li.active {
            border-bottom: 3px solid #3498db;
            font-weight: bold;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .maintenance-log {
            margin-top: 20px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .edit-btn {
            background-color: #f39c12;
        }

        .delete-btn {
            background-color: #e74c3c;
        }
    </style>
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

    <div class="main-content">
        <div class="container">
            <header>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <a href="MenuAdmin.php" class="back-button">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Volver al Menú
                    </a>
                    <div>
                        <h1>Gestión de Grúas</h1>
                        <p>Sistema de administración y seguimiento de flota de grúas</p>
                    </div>
                </div>
            </header>

            <div class="dashboard">
                <div class="stat-card">
                    <h3>Total de Grúas</h3>
                    <div class="number" id="totalCranes">0</div>
                </div>
                <div class="stat-card">
                    <h3>Grúas Disponibles</h3>
                    <div class="number" id="availableCranes">0</div>
                </div>
                <div class="stat-card">
                    <h3>En Operación</h3>
                    <div class="number" id="inUseCranes">0</div>
                </div>
                <div class="stat-card">
                    <h3>En Mantenimiento</h3>
                    <div class="number" id="maintenanceCranes">0</div>
                </div>
            </div>
            
            <!-- Nuevo botón destacado para añadir grúas -->
            <div class="action-button-container">
                <button id="addCraneBtn" class="main-action-button">
                    <i class="fas fa-plus"></i>
                    Añadir Nueva Grúa
                </button>
            </div>
            
            <div class="filter-controls">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Buscar por ID o modelo...">
                    <button id="searchBtn">Buscar</button>
                </div>
                
                <div class="filters">
                    <select id="statusFilter">
                        <option value="all">Todos los estados</option>
                        <option value="available">Disponible</option>
                        <option value="in-use">En operación</option>
                        <option value="maintenance">En mantenimiento</option>
                    </select>
                    <select id="typeFilter">
                        <option value="all">Todos los tipos</option>
                        <option value="torre">Grúa Torre</option>
                        <option value="movil">Grúa Móvil</option>
                        <option value="telescopica">Grúa Telescópica</option>
                        <option value="portuaria">Grúa Portuaria</option>
                    </select>
                </div>
            </div>
            
            <div class="cranes-list">
                <table id="cranesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Modelo</th>
                            <th>Tipo</th>
                            <th>Capacidad (ton)</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Próx. Mantenimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cranesList">
                        <!-- Las grúas se cargarán aquí dinámicamente -->
                    </tbody>
                </table>
            </div>
            
            <div class="pagination" id="pagination">
                <!-- Botones de paginación se generarán aquí -->
            </div>
        </div>
        
        <!-- Modal para añadir/editar grúa -->
        <div id="craneModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modalTitle">Añadir Grúa</h2>
                <form id="craneForm">
                    <input type="hidden" id="craneId">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="model">Modelo:</label>
                            <input type="text" id="model" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Tipo:</label>
                            <select id="type" required>
                                <option value="">Seleccionar...</option>
                                <option value="torre">Grúa Torre</option>
                                <option value="movil">Grúa Móvil</option>
                                <option value="telescopica">Grúa Telescópica</option>
                                <option value="portuaria">Grúa Portuaria</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="capacity">Capacidad (ton):</label>
                            <input type="number" id="capacity" min="0" step="0.1" required>
                        </div>
                        <div class="form-group">
                            <label for="manufacturer">Fabricante:</label>
                            <input type="text" id="manufacturer" required>
                        </div>
                        <div class="form-group">
                            <label for="year">Año de fabricación:</label>
                            <input type="number" id="year" min="1900" max="2099" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Estado:</label>
                            <select id="status" required>
                                <option value="">Seleccionar...</option>
                                <option value="available">Disponible</option>
                                <option value="in-use">En operación</option>
                                <option value="maintenance">En mantenimiento</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="location">Ubicación:</label>
                            <input type="text" id="location" required>
                        </div>
                        <div class="form-group">
                            <label for="nextMaintenance">Próximo mantenimiento:</label>
                            <input type="date" id="nextMaintenance" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="notes">Notas adicionales:</label>
                            <textarea id="notes"></textarea>
                        </div>
                    </div>
                    <button type="submit" id="saveBtn">Guardar</button>
                </form>
            </div>
        </div>
        
        <!-- Modal para ver detalles de la grúa -->
        <div id="detailsModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="detailsTitle">Detalles de Grúa</h2>
                
                <div class="tab-container">
                    <ul class="tabs">
                        <li class="active" data-tab="info">Información</li>
                        <li data-tab="maintenance">Historial de Mantenimiento</li>
                        <li data-tab="operations">Historial de Operaciones</li>
                    </ul>
                </div>
                
                <div id="info" class="tab-content active">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>ID:</label>
                            <p id="detail-id"></p>
                        </div>
                        <div class="form-group">
                            <label>Modelo:</label>
                            <p id="detail-model"></p>
                        </div>
                        <div class="form-group">
                            <label>Tipo:</label>
                            <p id="detail-type"></p>
                        </div>
                        <div class="form-group">
                            <label>Capacidad:</label>
                            <p id="detail-capacity"></p>
                        </div>
                        <div class="form-group">
                            <label>Fabricante:</label>
                            <p id="detail-manufacturer"></p>
                        </div>
                        <div class="form-group">
                            <label>Año de fabricación:</label>
                            <p id="detail-year"></p>
                        </div>
                        <div class="form-group">
                            <label>Estado actual:</label>
                            <p id="detail-status"></p>
                        </div>
                        <div class="form-group">
                            <label>Ubicación:</label>
                            <p id="detail-location"></p>
                        </div>
                        <div class="form-group">
                            <label>Próximo mantenimiento:</label>
                            <p id="detail-nextMaintenance"></p>
                        </div>
                        <div class="form-group full-width">
                            <label>Notas adicionales:</label>
                            <p id="detail-notes"></p>
                        </div>
                    </div>
                    
                    <div class="actions">
                        <button id="editFromDetails" class="edit-btn">Editar</button>
                        <button id="scheduleMaintenanceBtn">Programar Mantenimiento</button>
                    </div>
                </div>
                
                <div id="maintenance" class="tab-content">
                    <button id="addMaintenanceBtn">Registrar Mantenimiento</button>
                    
                    <div class="maintenance-log" id="maintenanceLog">
                        <!-- Historial de mantenimiento se cargará aquí -->
                    </div>
                </div>
                
                <div id="operations" class="tab-content">
                    <div class="maintenance-log" id="operationsLog">
                        <!-- Historial de operaciones se cargará aquí -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para programar mantenimiento -->
        <div id="maintenanceModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Programar Mantenimiento</h2>
                <form id="maintenanceForm">
                    <input type="hidden" id="maintenanceCraneId">
                    <div class="form-group">
                        <label for="maintenanceType">Tipo de Mantenimiento:</label>
                        <select id="maintenanceType" required>
                            <option value="">Seleccionar...</option>
                            <option value="preventivo">Preventivo</option>
                            <option value="correctivo">Correctivo</option>
                            <option value="revision">Revisión Rutinaria</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="maintenanceDate">Fecha:</label>
                        <input type="date" id="maintenanceDate" required>
                    </div>
                    <div class="form-group">
                        <label for="technicianName">Técnico Responsable:</label>
                        <input type="text" id="technicianName" required>
                    </div>
                    <div class="form-group">
                        <label for="maintenanceCost">Costo:</label>
                        <input type="number" id="maintenanceCost" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="maintenanceDetails">Detalles del Mantenimiento:</label>
                        <textarea id="maintenanceDetails" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="nextMaintenanceUpdate">Próximo Mantenimiento:</label>
                        <input type="date" id="nextMaintenanceUpdate" required>
                    </div>
                    <button type="submit" id="saveMaintenanceBtn">Guardar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="Gruas.js"></script>
    <script>
        // Datos de ejemplo de grúas
        let cranes = [
            { id: 1, model: "T-1000", type: "torre", capacity: 10, manufacturer: "Liebherr", year: 2020, status: "available", location: "Almacén Central", nextMaintenance: "2023-12-15", notes: "Nueva adquisición" },
            { id: 2, model: "M-500", type: "movil", capacity: 5, manufacturer: "Terex", year: 2019, status: "in-use", location: "Obra Norte", nextMaintenance: "2023-11-20", notes: "Requiere revisión de frenos" },
            { id: 3, model: "TL-800", type: "telescopica", capacity: 8, manufacturer: "Manitowoc", year: 2021, status: "maintenance", location: "Taller", nextMaintenance: "2023-12-01", notes: "En reparación de motor" },
            { id: 4, model: "P-2000", type: "portuaria", capacity: 20, manufacturer: "Konecranes", year: 2018, status: "available", location: "Puerto Este", nextMaintenance: "2024-01-10", notes: "Recién mantenida" },
            { id: 5, model: "T-1200", type: "torre", capacity: 12, manufacturer: "Liebherr", year: 2022, status: "in-use", location: "Obra Sur", nextMaintenance: "2023-11-30", notes: "Funcionando perfectamente" }
        ];

        // Variables para paginación
        const recordsPerPage = 5;
        let currentPage = 1;

        // Referencias a elementos DOM
        const cranesList = document.getElementById('cranesList');
        const pagination = document.getElementById('pagination');
        const craneModal = document.getElementById('craneModal');
        const detailsModal = document.getElementById('detailsModal');
        const maintenanceModal = document.getElementById('maintenanceModal');
        const closeButtons = document.querySelectorAll('.close');
        const addCraneBtn = document.getElementById('addCraneBtn');
        const searchBtn = document.getElementById('searchBtn');
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const typeFilter = document.getElementById('typeFilter');
        const craneForm = document.getElementById('craneForm');
        const maintenanceForm = document.getElementById('maintenanceForm');
        const modalTitle = document.getElementById('modalTitle');
        const detailsTitle = document.getElementById('detailsTitle');
        const editFromDetailsBtn = document.getElementById('editFromDetails');
        const scheduleMaintenanceBtn = document.getElementById('scheduleMaintenanceBtn');
        const addMaintenanceBtn = document.getElementById('addMaintenanceBtn');
        const tabs = document.querySelectorAll('.tabs li');
        const tabContents = document.querySelectorAll('.tab-content');
        const sidebar = document.getElementById('sidebar');

        // Función para mostrar grúas
        function displayCranes(page = 1, cranesArray = cranes) {
            cranesList.innerHTML = '';
            
            const start = (page - 1) * recordsPerPage;
            const end = start + recordsPerPage;
            const paginatedCranes = cranesArray.slice(start, end);
            
            if (paginatedCranes.length === 0) {
                cranesList.innerHTML = '<tr><td colspan="8" style="text-align: center">No se encontraron grúas</td></tr>';
                return;
            }
            
            paginatedCranes.forEach(crane => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${crane.id}</td>
                    <td>${crane.model}</td>
                    <td>${getTypeName(crane.type)}</td>
                    <td>${crane.capacity}</td>
                    <td>${crane.location}</td>
                    <td><span class="status-badge ${crane.status}">${getStatusName(crane.status)}</span></td>
                    <td>${formatDate(crane.nextMaintenance)}</td>
                    <td class="actions">
                        <button class="view-btn" data-id="${crane.id}">Ver</button>
                        <button class="edit-btn" data-id="${crane.id}">Editar</button>
                        <button class="delete-btn" data-id="${crane.id}">Eliminar</button>
                    </td>
                `;
                cranesList.appendChild(row);
            });
            
            // Actualizar estadísticas
            updateStats();
            
            // Configurar botones de acción
            setupActionButtons();
            
            // Configurar paginación
            setupPagination(cranesArray);
        }

        // Función para obtener nombre del tipo
        function getTypeName(type) {
            const types = {
                'torre': 'Grúa Torre',
                'movil': 'Grúa Móvil',
                'telescopica': 'Grúa Telescópica',
                'portuaria': 'Grúa Portuaria'
            };
            return types[type] || type;
        }

        // Función para obtener nombre del estado
        function getStatusName(status) {
            const statuses = {
                'available': 'Disponible',
                'in-use': 'En operación',
                'maintenance': 'En mantenimiento'
            };
            return statuses[status] || status;
        }

        // Función para formatear fecha
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('es-ES', options);
        }

        // Función para actualizar estadísticas
        function updateStats() {
            document.getElementById('totalCranes').textContent = cranes.length;
            document.getElementById('availableCranes').textContent = cranes.filter(c => c.status === 'available').length;
            document.getElementById('inUseCranes').textContent = cranes.filter(c => c.status === 'in-use').length;
            document.getElementById('maintenanceCranes').textContent = cranes.filter(c => c.status === 'maintenance').length;
        }

        // Configurar botones de acción
        function setupActionButtons() {
            const viewButtons = document.querySelectorAll('.view-btn');
            const editButtons = document.querySelectorAll('.edit-btn');
            const deleteButtons = document.querySelectorAll('.delete-btn');
            
            viewButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = parseInt(button.getAttribute('data-id'));
                    showCraneDetails(id);
                });
            });
            
            editButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = parseInt(button.getAttribute('data-id'));
                    editCrane(id);
                });
            });
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = parseInt(button.getAttribute('data-id'));
                    if (confirm('¿Estás seguro de que deseas eliminar esta grúa?')) {
                        deleteCrane(id);
                    }
                });
            });
        }

        // Configurar paginación
        function setupPagination(cranesArray) {
            pagination.innerHTML = '';
            const pageCount = Math.ceil(cranesArray.length / recordsPerPage);
            
            // Botón anterior
            const prevBtn = document.createElement('button');
            prevBtn.innerText = 'Anterior';
            prevBtn.disabled = currentPage === 1;
            prevBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    displayCranes(currentPage, cranesArray);
                }
            });
            pagination.appendChild(prevBtn);
            
            // Botones de página
            for (let i = 1; i <= pageCount; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.innerText = i;
                if (i === currentPage) {
                    pageBtn.classList.add('current-page');
                }
                pageBtn.addEventListener('click', () => {
                    currentPage = i;
                    displayCranes(currentPage, cranesArray);
                });
                pagination.appendChild(pageBtn);
            }
            
            // Botón siguiente
            const nextBtn = document.createElement('button');
            nextBtn.innerText = 'Siguiente';
            nextBtn.disabled = currentPage === pageCount;
            nextBtn.addEventListener('click', () => {
                if (currentPage < pageCount) {
                    currentPage++;
                    displayCranes(currentPage, cranesArray);
                }
            });
            pagination.appendChild(nextBtn);
        }

        // Mostrar modal para añadir grúa
        function showAddModal() {
            modalTitle.innerText = 'Añadir Grúa';
            document.getElementById('craneId').value = '';
            craneForm.reset();
            craneModal.style.display = 'block';
        }

        // Mostrar detalles de la grúa
        function showCraneDetails(id) {
            const crane = cranes.find(c => c.id === id);
            
            if (crane) {
                detailsTitle.textContent = `Detalles: ${crane.model}`;
                document.getElementById('detail-id').textContent = crane.id;
                document.getElementById('detail-model').textContent = crane.model;
                document.getElementById('detail-type').textContent = getTypeName(crane.type);
                document.getElementById('detail-capacity').textContent = `${crane.capacity} ton`;
                document.getElementById('detail-manufacturer').textContent = crane.manufacturer;
                document.getElementById('detail-year').textContent = crane.year;
                document.getElementById('detail-status').textContent = getStatusName(crane.status);
                document.getElementById('detail-location').textContent = crane.location;
                document.getElementById('detail-nextMaintenance').textContent = formatDate(crane.nextMaintenance);
                document.getElementById('detail-notes').textContent = crane.notes || 'N/A';
                
                // Configurar botones de acción
                editFromDetailsBtn.setAttribute('data-id', crane.id);
                scheduleMaintenanceBtn.setAttribute('data-id', crane.id);
                addMaintenanceBtn.setAttribute('data-id', crane.id);
                
                // Mostrar solo la pestaña de información al principio
                tabs.forEach(tab => tab.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                document.querySelector('.tabs li[data-tab="info"]').classList.add('active');
                document.getElementById('info').classList.add('active');
                
                detailsModal.style.display = 'block';
            }
        }

        // Editar grúa
        function editCrane(id) {
            modalTitle.innerText = 'Editar Grúa';
            const crane = cranes.find(c => c.id === id);
            
            if (crane) {
                document.getElementById('craneId').value = crane.id;
                document.getElementById('model').value = crane.model;
                document.getElementById('type').value = crane.type;
                document.getElementById('capacity').value = crane.capacity;
                document.getElementById('manufacturer').value = crane.manufacturer;
                document.getElementById('year').value = crane.year;
                document.getElementById('status').value = crane.status;
                document.getElementById('location').value = crane.location;
                document.getElementById('nextMaintenance').value = crane.nextMaintenance;
                document.getElementById('notes').value = crane.notes || '';
                
                craneModal.style.display = 'block';
            }
        }

        // Eliminar grúa
        function deleteCrane(id) {
            cranes = cranes.filter(c => c.id !== id);
            displayCranes(currentPage);
        }

        // Guardar grúa (añadir o actualizar)
        function saveCrane(e) {
            e.preventDefault();
            
            const id = document.getElementById('craneId').value;
            const model = document.getElementById('model').value;
            const type = document.getElementById('type').value;
            const capacity = parseFloat(document.getElementById('capacity').value);
            const manufacturer = document.getElementById('manufacturer').value;
            const year = parseInt(document.getElementById('year').value);
            const status = document.getElementById('status').value;
            const location = document.getElementById('location').value;
            const nextMaintenance = document.getElementById('nextMaintenance').value;
            const notes = document.getElementById('notes').value;
            
            if (id) {
                // Actualizar grúa existente
                const index = cranes.findIndex(c => c.id === parseInt(id));
                if (index !== -1) {
                    cranes[index] = {
                        id: parseInt(id),
                        model,
                        type,
                        capacity,
                        manufacturer,
                        year,
                        status,
                        location,
                        nextMaintenance,
                        notes
                    };
                }
            } else {
                // Añadir nueva grúa
                const newId = cranes.length > 0 ? Math.max(...cranes.map(c => c.id)) + 1 : 1;
                cranes.push({
                    id: newId,
                    model,
                    type,
                    capacity,
                    manufacturer,
                    year,
                    status,
                    location,
                    nextMaintenance,
                    notes
                });
            }
            
            craneModal.style.display = 'none';
            displayCranes(currentPage);
        }

        // Programar mantenimiento
        function scheduleMaintenance(id) {
            document.getElementById('maintenanceCraneId').value = id;
            maintenanceModal.style.display = 'block';
        }

        // Guardar mantenimiento
        function saveMaintenance(e) {
            e.preventDefault();
            
            const craneId = parseInt(document.getElementById('maintenanceCraneId').value);
            const type = document.getElementById('maintenanceType').value;
            const date = document.getElementById('maintenanceDate').value;
            const technician = document.getElementById('technicianName').value;
            const cost = parseFloat(document.getElementById('maintenanceCost').value) || 0;
            const details = document.getElementById('maintenanceDetails').value;
            const nextMaintenance = document.getElementById('nextMaintenanceUpdate').value;
            
            // En una aplicación real, aquí guardarías el mantenimiento en la base de datos
            // Por ahora solo actualizamos la fecha del próximo mantenimiento en la grúa
            
            const craneIndex = cranes.findIndex(c => c.id === craneId);
            if (craneIndex !== -1) {
                cranes[craneIndex].nextMaintenance = nextMaintenance;
                
                // Si es mantenimiento correctivo, cambiar estado a mantenimiento
                if (type === 'correctivo') {
                    cranes[craneIndex].status = 'maintenance';
                }
            }
            
            maintenanceModal.style.display = 'none';
            displayCranes(currentPage);
            
            // Mostrar mensaje de éxito
            alert('Mantenimiento registrado correctamente');
        }

        // Buscar grúas
        function searchCranes() {
            const searchTerm = searchInput.value.toLowerCase();
            const status = statusFilter.value;
            const type = typeFilter.value;
            
            let filteredCranes = cranes;
            
            if (searchTerm.trim() !== '') {
                filteredCranes = filteredCranes.filter(c => 
                    c.id.toString().includes(searchTerm) ||
                    c.model.toLowerCase().includes(searchTerm)
                );
            }
            
            if (status !== 'all') {
                filteredCranes = filteredCranes.filter(c => c.status === status);
            }
            
            if (type !== 'all') {
                filteredCranes = filteredCranes.filter(c => c.type === type);
            }
            
            currentPage = 1;
            displayCranes(currentPage, filteredCranes);
        }

        // Configurar pestañas
        function setupTabs() {
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    tab.classList.add('active');
                    const tabId = tab.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        }

        // Event Listeners
        window.addEventListener('load', () => {
            displayCranes();
            setupTabs();
        });

        addCraneBtn.addEventListener('click', showAddModal);

        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.modal').style.display = 'none';
            });
        });

        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                e.target.style.display = 'none';
            }
        });

        craneForm.addEventListener('submit', saveCrane);
        maintenanceForm.addEventListener('submit', saveMaintenance);

        searchBtn.addEventListener('click', searchCranes);
        statusFilter.addEventListener('change', searchCranes);
        typeFilter.addEventListener('change', searchCranes);

        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') {
                searchCranes();
            }
        });

        editFromDetailsBtn.addEventListener('click', function() {
            const id = parseInt(this.getAttribute('data-id'));
            detailsModal.style.display = 'none';
            editCrane(id);
        });

        scheduleMaintenanceBtn.addEventListener('click', function() {
            const id = parseInt(this.getAttribute('data-id'));
            detailsModal.style.display = 'none';
            scheduleMaintenance(id);
        });

        addMaintenanceBtn.addEventListener('click', function() {
            const id = parseInt(this.getAttribute('data-id'));
            detailsModal.style.display = 'none';
            scheduleMaintenance(id);
        });

        // Mejorar la experiencia en móviles para la barra lateral
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
    </script>
</body>
</html>