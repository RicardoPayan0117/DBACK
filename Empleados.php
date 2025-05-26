<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./CSS/Empleados.CSS">
</head>
<body>
    <!-- Barra lateral -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar_header">
            <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo" alt="Logo DBACK">
            <span class="sidebar_text">Grúas DBACK</span>
        </div>

        <ul class="sidebar_list">
            <li class="sidebar_element">
                <a href="MenuAdmin.PHP" class="sidebar_link">
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
                <a href="Empleados.php" class="sidebar_link">
                    <i class="fas fa-users sidebar_icon"></i>
                    <span class="sidebar_text">Empleados</span>
                </a>
            </li>
            <li class="sidebar_element">
                <a href="solicitud.html" class="sidebar_link">
                    <i class="fas fa-clipboard-list sidebar_icon"></i>
                    <span class="sidebar_text">Panel de Solicitud</span>
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

    </aside>

    <div class="main-content">
        <div class="container">
            <header>
                <a href="MenuAdmin.PHP" class="back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                    </svg>
                    Volver al Menú
                </a>
                <h1>Gestión de Empleados</h1>
                <p>Administra la información de tus empleados de manera eficiente</p>
            </header>
            
            <div class="controls">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Buscar empleado...">
                    <button id="searchBtn">Buscar</button>
                </div>
                <button id="addEmployeeBtn">Añadir Empleado</button>
            </div>
            
            <div class="employees-list">
                <table id="employeesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Usuario</th>
                            <th>Cargo</th>
                            <th>Sueldo diario</th>
                            <th>telefono</th>
                            <th>email</th>
                        </tr>
                    </thead>
                    <tbody id="employeesList">
                        <!-- Los empleados se cargarán aquí dinámicamente -->
                    </tbody>
                </table>
            </div>
            
            <div class="pagination" id="pagination">
                <!-- Botones de paginación se generarán aquí -->
            </div>
        </div>
        
        <!-- Modal para añadir/editar empleado -->
                <div id="employeeModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2 id="modalTitle">Añadir Empleado</h2>
                    <form id="employeeForm">
                    <input type="hidden" id="employeeId">

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" required>
                    </div>
                    <div class="form-group">
                    <label for="apellidoPaterno">Apellido Paterno:</label>
                    <input type="text" id="apellidoPaterno" required>
                    </div>
                    <div class="form-group">
                    <label for="apellidoMaterno">Apellido Materno:</label>
                    <input type="text" id="apellidoMaterno" required>
                    </div>
                    <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" required>
                    </div>
                    <div class="form-group">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" id="contraseña" required>
                    </div>
                    <div class="form-group">
                    <label for="cargo">Cargo:</label>
                        <select id="cargo" required>
                            <option value="">Seleccionar...</option>
                            <option value="IT">IT</option>
                            <option value="Recursos Humanos">Recursos Humanos</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Ventas">Ventas</option>
                            <option value="Contador">Contador</option>
                            <option value="Conductor">Conductor</option>
                            <option value="Administrador">Administrador</option>
                        </select>
                    </div>
                    <div class="form-group">
                    <label for="sueldoDiario">Sueldo diario:</label>
                    <input type="number" id="sueldoDiario" required>
                    </div>
                    <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" required>
                    </div>
                    <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" required>
                    </div>
                    <button type="submit" id="saveBtn">Guardar</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Configuración
        const recordsPerPage = 5;
        let currentPage = 1;
        let totalEmployees = 0;
        
        // Elementos DOM
        const employeesList = document.getElementById('employeesList');
        const pagination = document.getElementById('pagination');
        const modal = document.getElementById('employeeModal');
        const closeBtn = document.querySelector('.close');
        const addEmployeeBtn = document.getElementById('addEmployeeBtn');
        const searchBtn = document.getElementById('searchBtn');
        const searchInput = document.getElementById('searchInput');
        const employeeForm = document.getElementById('employeeForm');
        const modalTitle = document.getElementById('modalTitle');
        
        // Cargar empleados
        async function loadEmployees(page = 1, searchTerm = '') {
            try {
                const response = await fetch(`empleados_api.php?action=get_employees&page=${page}&search=${encodeURIComponent(searchTerm)}`);
                
                if (!response.ok) throw new Error(`Error HTTP! estado: ${response.status}`);
                
                const data = await response.json();
                
                if (data.success) {
                    displayEmployees(data.employees, page, data.total);
                } else {
                    showError('Error al cargar empleados: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                showError('Error de conexión: ' + error.message);
            }
        }
        
        // Mostrar empleados
function displayEmployees(employees, page = 1, total = 0) {
    employeesList.innerHTML = '';
    totalEmployees = total;

    if (employees.length === 0) {
        employeesList.innerHTML = '<tr><td colspan="9" class="no-data">No se encontraron empleados</td></tr>';
        setupPagination(page, 0);
        return;
    }

    employees.forEach(employee => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${employee.id}</td>
            <td>${employee.nombre}</td>
            <td>${employee.apellido_paterno}</td>
            <td>${employee.apellido_materno}</td>
            <td>${employee.usuario}</td>
            <td>${employee.cargo}</td>
            <td>$${parseFloat(employee.sueldo_diario).toFixed(2)}</td>
            <td>${employee.telefono}</td>
            <td class="actions">
                <button class="edit-btn" data-id="${employee.id}"><i class="fas fa-edit"></i> Editar</button>
                <button class="delete-btn" data-id="${employee.id}"><i class="fas fa-trash-alt"></i> Eliminar</button>
            </td>
        `;
        employeesList.appendChild(row);
    });

    setupActionButtons();
    setupPagination(page, total);
}
       
        // Configurar botones de acción
        function setupActionButtons() {
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', () => editEmployee(btn.dataset.id));
            });
            
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (confirm('¿Estás seguro de eliminar este empleado?')) {
                        deleteEmployee(btn.dataset.id);
                    }
                });
            });
        }
        
        // Configurar paginación
        function setupPagination(currentPage, totalRecords) {
            pagination.innerHTML = '';
            const pageCount = Math.ceil(totalRecords / recordsPerPage);
            
            if (pageCount <= 1) return;
            
            // Botón Anterior
            const prevBtn = createPaginationButton('Anterior', currentPage > 1, () => {
                if (currentPage > 1) loadEmployees(currentPage - 1, searchInput.value);
            });
            pagination.appendChild(prevBtn);
            
            // Botones de página
            const maxPages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxPages / 2));
            let endPage = Math.min(pageCount, startPage + maxPages - 1);
            
            if (endPage - startPage + 1 < maxPages) {
                startPage = Math.max(1, endPage - maxPages + 1);
            }
            
            if (startPage > 1) {
                pagination.appendChild(createPaginationButton(1, true, () => loadEmployees(1, searchInput.value)));
                if (startPage > 2) pagination.appendChild(createEllipsis());
            }
            
            for (let i = startPage; i <= endPage; i++) {
                pagination.appendChild(
                    createPaginationButton(i, true, () => loadEmployees(i, searchInput.value), i === currentPage)
                );
            }
            
            if (endPage < pageCount) {
                if (endPage < pageCount - 1) pagination.appendChild(createEllipsis());
                pagination.appendChild(
                    createPaginationButton(pageCount, true, () => loadEmployees(pageCount, searchInput.value))
                );
            }
            
            // Botón Siguiente
            const nextBtn = createPaginationButton('Siguiente', currentPage < pageCount, () => {
                if (currentPage < pageCount) loadEmployees(currentPage + 1, searchInput.value);
            });
            pagination.appendChild(nextBtn);
        }
        
        function createPaginationButton(text, enabled, onClick, isCurrent = false) {
            const btn = document.createElement('button');
            btn.textContent = text;
            btn.disabled = !enabled;
            if (isCurrent) btn.classList.add('current-page');
            btn.addEventListener('click', onClick);
            return btn;
        }
        
        function createEllipsis() {
            const ellipsis = document.createElement('span');
            ellipsis.textContent = '...';
            return ellipsis;
        }
        
        // Mostrar modal para añadir
        function showAddModal() {
            modalTitle.textContent = 'Añadir Empleado';
            document.getElementById('employeeId').value = '';
            employeeForm.reset();
            modal.style.display = 'block';
        }
        
        // Editar empleado
async function editEmployee(id) {
    try {
        const response = await fetch(`empleados_api.php?action=get_employee&id=${id}`);
        if (!response.ok) throw new Error(`Error HTTP! estado: ${response.status}`);
        
        const data = await response.json();

        if (data.success) {
            const emp = data.employee;
            modalTitle.textContent = 'Editar Empleado';

            document.getElementById('employeeId').value = emp.id;
            document.getElementById('nombre').value = emp.nombre;
            document.getElementById('apellidoPaterno').value = emp.apellido_paterno;
            document.getElementById('apellidoMaterno').value = emp.apellido_materno;
            document.getElementById('usuario').value = emp.usuario;
            document.getElementById('contraseña').value = emp.contraseña;
            document.getElementById('cargo').value = emp.cargo;
            document.getElementById('sueldoDiario').value = emp.sueldo_diario;
            document.getElementById('telefono').value = emp.telefono;
            document.getElementById('email').value = emp.email;

            modal.style.display = 'block';
        } else {
            showError('Error al cargar empleado: ' + (data.message || 'Error desconocido'));
        }
    } catch (error) {
        showError('Error de conexión: ' + error.message);
    }
}

        
        // Eliminar empleado
        async function deleteEmployee(id) {
            try {
                const response = await fetch('empleados_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_employee', id })
                });
                
                if (!response.ok) throw new Error(`Error HTTP! estado: ${response.status}`);
                
                const data = await response.json();
                
                if (data.success) {
                    loadEmployees(currentPage, searchInput.value);
                } else {
                    showError('Error al eliminar: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                showError('Error de conexión: ' + error.message);
            }
        }
        
        // Guardar empleado
async function saveEmployee(e) {
    e.preventDefault();

    const id = document.getElementById('employeeId').value;

    const employeeData = {
        action: id ? 'update_employee' : 'add_employee',
        nombre: document.getElementById('nombre').value.trim(),
        apellido_paterno: document.getElementById('apellidoPaterno').value.trim(),
        apellido_materno: document.getElementById('apellidoMaterno').value.trim(),
        usuario: document.getElementById('usuario').value.trim(),
        contraseña: document.getElementById('contraseña').value.trim(),
        cargo: document.getElementById('cargo').value.trim(),
        sueldo_diario: parseFloat(document.getElementById('sueldoDiario').value),
        telefono: document.getElementById('telefono').value.trim(),
        email: document.getElementById('email').value.trim()
    };

    if (id) {
        employeeData.id = id;
    }

if (Object.values(employeeData).some(v => v === '' || v === null || v === undefined || (typeof v === 'number' && isNaN(v)))) {
    showError('Por favor, completa todos los campos correctamente.');
    return;
}

try {
    const response = await fetch('empleados_api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(employeeData)
    });

    if (!response.ok) throw new Error(`Error HTTP! estado: ${response.status}`);

    const data = await response.json();

    if (data.success) {
        modal.style.display = 'none';
        loadEmployees(currentPage, searchInput.value);
    } else {
        showError(data.message || 'No se pudo guardar el empleado.');
    }
} catch (error) {
    showError('Error al guardar: ' + error.message);
}

}

        // Buscar empleados
        function searchEmployees() {
            currentPage = 1;
            loadEmployees(currentPage, searchInput.value.trim());
        }
        
        // Mostrar error
        function showError(message) {
            console.error(message);
            employeesList.innerHTML = `<tr><td colspan="7" class="error">${message}</td></tr>`;
        }
        
        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            loadEmployees();
            

            window.addEventListener('click', (e) => e.target === modal && (modal.style.display = 'none'));
            window.onclick = function(event) {
                                                if (event.target === modal) { modal.style.display = "none";}
                                            }
            addEmployeeBtn.addEventListener('click', showAddModal);
            closeBtn.addEventListener('click', () => modal.style.display = 'none');                                    
            employeeForm.addEventListener('submit', saveEmployee);
            searchBtn.addEventListener('click', searchEmployees);
            searchInput.addEventListener('keyup', (e) => e.key === 'Enter' && searchEmployees());
        });
    </script>
</body>
</html>