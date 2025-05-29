<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión de Grúas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="./CSS/Empleados.CSS" />
</head>
<body>
     <!-- Barra lateral mejorada con ARIA -->
    <nav class="sidebar" aria-label="Menú principal">
        <div class="sidebar_header">
            <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo" alt="Logo DBACK" width="30" height="30">
            <span class="sidebar_text">Grúas DBACK</span>
        </div>

        <ul class="sidebar_list" role="menubar">
            
            <li class="sidebar_element" role="menuitem" onclick="showSection('Volver al login')" tabindex="0" aria-label="Volver al Login">
                <a href="index.html" class="sidebar_link">
                    <i class="fas fa-truck sidebar_icon" aria-hidden="true"></i>
                    <span class="sidebar_text">Volver a login</span>
                </a>
            </li>

            <li class="sidebar_element" role="menuitem" onclick="showSection('dashboard')" tabindex="0" aria-label="Inicio">
                <a href="MenuAdmin.PHP" class="sidebar_link">
                <i class="fas fa-home sidebar_icon" aria-hidden="true"></i>
                <span class="sidebar_text">Inicio</span>
                </a>
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
                <a href="solicitud.php" class="sidebar_link">
                    <i class="fas fa-clipboard-list sidebar_icon" aria-hidden="true"></i>
                    <span class="sidebar_text">Panel de solicitud</span>
                </a>
            </li>
        </ul>
<div class="sidebar_footer">
    <div class="sidebar_element" role="contentinfo">
        <i class="fas fa-user-circle sidebar_icon" aria-hidden="true"></i>
        <div>
            <div class="sidebar_text sidebar_title"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></div>
            <div class="sidebar_text sidebar_info"><?php echo htmlspecialchars($_SESSION['usuario_cargo']); ?></div>
        </div>
    </div>
</div>

    </nav>
    <div class="main-content">
        <div class="container">
            <header>
                <a href="MenuAdmin.PHP" class="back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                    </svg>
                    Volver al Menú
                </a>
                <h1>Gestión de Grúas</h1>
                <p>Administra la información de las grúas</p>
            </header>

            <div class="controls">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Buscar grúa..." />
                    <button id="searchBtn">Buscar</button>
                </div>
                <button id="addGruaBtn">Añadir grúa</button>
            </div>

            <div class="gruas-list">
                <table id="gruasTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Marca</th>
                            <th>Placa</th>
                            <th>Modelo</th>
                            <th>Estado</th>
                            <th>Capacidad (KG)</th>
                            <th>Fecha de registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="gruasList">
                        <!-- Las grúas se cargarán aquí -->
                    </tbody>
                </table>
            </div>

            <div class="pagination" id="pagination">
                <!-- Botones de paginación -->
            </div>
        </div>

        <!-- Modal para añadir/editar grúas -->
        <div id="gruasModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modalTitle">Grúas</h2>
                <form id="gruasForm">
                    <input type="hidden" id="gruasId" />
                    <div class="form-group">
                        <label for="nombre">Marca:</label>
                        <input type="text" id="nombre" required />
                    </div>
                    <div class="form-group">
                        <label for="placa">Placa:</label>
                        <input type="text" id="placa" required />
                    </div>
                    <div class="form-group">
                        <label for="modelo">Modelo:</label>
                        <input type="text" id="modelo" required />
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado:</label>
                            <select id="estado" required>
                            <option value="">Seleccionar...</option>
                            <option value="Disponible">Disponible</option>
                            <option value="En Servicio">En Servicio</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="capacidad">Capacidad (kg):</label>
                        <input type="number" id="capacidad" required min="0" />
                    </div>
                    <button type="submit" id="saveBtn">Guardar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const recordsPerPage = 5;
        let currentPage = 1;
        let totalGruas = 0;

        // Elementos DOM
        const gruasList = document.getElementById('gruasList');
        const pagination = document.getElementById('pagination');
        const modal = document.getElementById('gruasModal');
        const closeBtn = modal.querySelector('.close');
        const addGruaBtn = document.getElementById('addGruaBtn');
        const searchBtn = document.getElementById('searchBtn');
        const searchInput = document.getElementById('searchInput');
        const gruasForm = document.getElementById('gruasForm');
        const modalTitle = document.getElementById('modalTitle');

        async function loadGruas(page = 1, searchTerm = '') {
            try {
                const response = await fetch(`gruas_api.php?action=get_gruas&page=${page}&search=${encodeURIComponent(searchTerm)}`);
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                const data = await response.json();

                if (data.success) {
                    displayGruas(data.gruas, page, data.total);
                } else {
                    showError('Error al cargar grúas: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                showError('Error en la conexión con el servidor.');
                console.error(error);
            }
        }

        function displayGruas(gruas, page, total) {
            gruasList.innerHTML = '';
            totalGruas = total;
            currentPage = page;

            if (gruas.length === 0) {
                gruasList.innerHTML = '<tr><td colspan="8">No se encontraron grúas.</td></tr>';
                pagination.innerHTML = '';
                return;
            }

            for (const grua of gruas) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${grua.id}</td>
                    <td>${escapeHTML(grua.nombre)}</td>
                    <td>${escapeHTML(grua.placa)}</td>
                    <td>${escapeHTML(grua.modelo)}</td>
                    <td>${escapeHTML(grua.estado)}</td>
                    <td>${grua.capacidad_kg}</td>
                    <td>${escapeHTML(grua.fecha_registro)}</td>
                    <td>
                        <button class="editBtn" data-id="${grua.id}">Editar</button>
                        <button class="deleteBtn" data-id="${grua.id}">Eliminar</button>
                    </td>
                `;
                gruasList.appendChild(tr);
            }

            renderPagination();
            attachActionButtons();
        }

        function renderPagination() {
            pagination.innerHTML = '';
            const totalPages = Math.ceil(totalGruas / recordsPerPage);
            if (totalPages <= 1) return;

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = i === currentPage ? 'active' : '';
                btn.addEventListener('click', () => loadGruas(i, searchInput.value.trim()));
                pagination.appendChild(btn);
            }
        }

        function attachActionButtons() {
            document.querySelectorAll('.editBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    openModalForEdit(id);
                });
            });

            document.querySelectorAll('.deleteBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    if (confirm('¿Seguro que desea eliminar esta grúa?')) {
                        deleteGrua(id);
                    }
                });
            });
        }

        async function openModalForEdit(id) {
            try {
                const response = await fetch(`gruas_api.php?action=get_grua&id=${id}`);
                if (!response.ok) throw new Error('Error en la respuesta del servidor');

                const data = await response.json();
                if (data.success && data.grua) {
                    fillForm(data.grua);
                    modalTitle.textContent = 'Editar Grúa';
                    modal.style.display = 'block';
                } else {
                    showError('No se pudo cargar la grúa para edición.');
                }
            } catch (error) {
                showError('Error al obtener datos de la grúa.');
                console.error(error);
            }
        }

        function fillForm(grua) {
            document.getElementById('gruasId').value = grua.id;
            document.getElementById('nombre').value = grua.nombre;
            document.getElementById('placa').value = grua.placa;
            document.getElementById('modelo').value = grua.modelo;
            document.getElementById('estado').value = grua.estado;
            document.getElementById('capacidad').value = grua.capacidad_kg;
        }

        addGruaBtn.addEventListener('click', () => {
            modalTitle.textContent = 'Añadir Grúa';
            gruasForm.reset();
            document.getElementById('gruasId').value = '';
            modal.style.display = 'block';
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', e => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        gruasForm.addEventListener('submit', async e => {
            e.preventDefault();
            const id = document.getElementById('gruasId').value;
            const nombre = document.getElementById('nombre').value.trim();
            const placa = document.getElementById('placa').value.trim();
            const modelo = document.getElementById('modelo').value.trim();
            const estado = document.getElementById('estado').value.trim();
            const capacidad = parseInt(document.getElementById('capacidad').value, 10);

            if (!nombre || !placa || !modelo || !estado || isNaN(capacidad) || capacidad < 0) {
                alert('Por favor, complete todos los campos correctamente.');
                return;
            }

            const payload = {
                nombre,
                placa,
                modelo,
                estado,
                capacidad_kg: capacidad
            };

            let action = '';
            if (id) {
                action = 'update_grua';
                payload.id = id;
            } else {
                action = 'add_grua';
            }

            try {
                const response = await fetch(`gruas_api.php?action=${action}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                if (data.success) {
                    modal.style.display = 'none';
                    loadGruas(currentPage, searchInput.value.trim());
                } else {
                    showError('Error al guardar grúa: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                showError('Error en la conexión con el servidor.');
                console.error(error);
            }
        });

        async function deleteGrua(id) {
            try {
                const response = await fetch(`empleados_api.php?action=delete_grua&id=${id}`, {
                    method: 'POST'
                });

                const data = await response.json();
                if (data.success) {
                    loadGruas(currentPage, searchInput.value.trim());
                } else {
                    showError('No se pudo eliminar la grúa.');
                }
            } catch (error) {
                showError('Error al eliminar la grúa.');
                console.error(error);
            }
        }

        searchBtn.addEventListener('click', () => {
            loadGruas(1, searchInput.value.trim());
        });

        // Escape HTML para evitar XSS
        function escapeHTML(text) {
            return text.replace(/[&<>"']/g, function(m) {
                return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m];
            });
        }

        // Mostrar error simple (puedes mejorar este método)
        function showError(msg) {
            alert(msg);
        }

        // Carga inicial
        loadGruas();
    </script>
</body>
</html>
