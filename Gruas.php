<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Grúas</title>
    <link rel="stylesheet" href=".\CSS\Gruas.css">
    <style>
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
    </style>
</head>
<body>
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
                <button id="addCraneBtn">Añadir Grúa</button>
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

    <script src="script.js"></script>
    <script>
        // Hacer que el nuevo botón principal active el mismo comportamiento que el botón original
        document.addEventListener('DOMContentLoaded', function() {
            const mainAddCraneBtn = document.getElementById('mainAddCraneBtn');
            const addCraneBtn = document.getElementById('addCraneBtn');
            
            if (mainAddCraneBtn && addCraneBtn) {
                // Cuando se hace clic en el botón principal, simula un clic en el botón original
                mainAddCraneBtn.addEventListener('click', function() {
                    // Simular clic en el botón original para aprovechar la lógica existente
                    addCraneBtn.click();
                });
            }
        });
    </script>
</body>
</html>