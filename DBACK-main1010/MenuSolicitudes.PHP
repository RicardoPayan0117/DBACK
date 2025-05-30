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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Solicitudes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./CSS/Empleados.css">
</head>
<body>
    <nav class="sidebar" aria-label="Menú principal">
        <div class="sidebar_header">
            <img src="Elementos/LogoDBACK.png" class="sidebar_icon sidebar_icon--logo" alt="Logo DBACK" width="30" height="30">
            <span class="sidebar_text">Grúas DBACK</span>
        </div>

        <ul class="sidebar_list" role="menubar">
            <li class="sidebar_element"><a href="index.html" class="sidebar_link"><i class="fas fa-sign-out-alt sidebar_icon"></i><span class="sidebar_text">Volver a login</span></a></li>
            <li class="sidebar_element"><a href="MenuAdmin.php" class="sidebar_link"><i class="fas fa-home sidebar_icon"></i><span class="sidebar_text">Inicio</span></a></li>
            <li class="sidebar_element"><a href="Gruas.php" class="sidebar_link"><i class="fas fa-truck sidebar_icon"></i><span class="sidebar_text">Grúas</span></a></li>
            <li class="sidebar_element"><a href="Gastos.php" class="sidebar_link"><i class="fas fa-money-bill-wave sidebar_icon"></i><span class="sidebar_text">Gastos</span></a></li>
            <li class="sidebar_element"><a href="Empleados.php" class="sidebar_link"><i class="fas fa-users sidebar_icon"></i><span class="sidebar_text">Empleados</span></a></li>
            <li class="sidebar_element active"><a href="MenuSolicitudes.PHP" class="sidebar_link"><i class="fas fa-clipboard-list sidebar_icon"></i><span class="sidebar_text">Panel de solicitud</span></a></li>
        </ul>

        <div class="sidebar_footer">
            <div class="sidebar_element">
                <i class="fas fa-user-circle sidebar_icon"></i>
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
                <a href="MenuAdmin.php" class="back-button"><i class="fas fa-arrow-left"></i> Volver al Menú</a>
                <h1>Gestión de Solicitudes</h1>
                <p>Administra las solicitudes de servicio de manera eficiente</p>
            </header>

            <div class="controls">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Buscar solicitud...">
                    <button id="searchBtn">Buscar</button>
                </div>
                <button id="addRequestBtn" class="primary-btn">Añadir Solicitud</button>
            </div>

<div class="requests-notes" id="requestsList">
    <!-- Aquí se cargarán las notas dinámicamente -->
</div>


            <div class="pagination" id="pagination">
                <!-- Paginación futura -->
            </div>
        </div>

        <!-- Modal -->
        <div id="requestModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modalTitle">Añadir Solicitud</h2>
                <form id="requestForm">
                    <input type="hidden" id="Id">

                    <div class="form-group">
                        <label for="nombre">Cliente:</label>
                        <input type="text" id="nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" id="telefono" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="ubicacion_origen">Ubicación Origen:</label>
                        <input type="text" id="ubicacion_origen" required>
                    </div>

                    <div class="form-group">
                        <label for="ubicacion_destino">Ubicación Destino:</label>
                        <input type="text" id="ubicacion_destino" required>
                    </div>

                    <div class="form-group">
                        <label for="tipo_vehiculo">Tipo de Vehículo:</label>
                        <input type="text" id="tipo_vehiculo" required>
                    </div>

                    <div class="form-group">
                        <label for="marca">Marca:</label>
                        <input type="text" id="marca">
                    </div>

                    <div class="form-group">
                        <label for="modelo">Modelo:</label>
                        <input type="text" id="modelo">
                    </div>

                    <div class="form-group">
                        <label for="placa">Placa:</label>
                        <input type="text" id="placa">
                    </div>

                    <div class="form-group">
                        <label for="foto_vehiculo">Foto del Vehículo (URL):</label>
                        <input type="text" id="foto_vehiculo">
                    </div>

                    <div class="form-group">
                        <label for="tipo_servicio">Servicio:</label>
                        <input type="text" id="tipo_servicio" required>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="distancia">Distancia (km):</label>
                        <input type="number" id="distancia" step="0.1">
                    </div>

                    <div class="form-group">
                        <label for="costo">Costo:</label>
                        <input type="number" id="costo" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="metodo_pago">Método de Pago:</label>
                        <input type="text" id="metodo_pago">
                    </div>

                    <div class="form-group">
                        <label for="consentimiento">
                            <input type="checkbox" id="consentimiento">
                            Consentimiento para el tratamiento de datos
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="fecha_solicitud">Fecha:</label>
                        <input type="date" id="fecha_solicitud" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Estado:</label>
                        <select id="status" required>
                            <option value="">Seleccionar...</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En curso">En curso</option>
                            <option value="Completado">Completado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="gruas_id">ID de Grúa:</label>
                        <input type="number" id="gruas_id" min="1">
                    </div>

                    <button type="submit" id="saveBtn">Guardar</button>
                </form>

            </div>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.getElementById("requestsList");
    const searchInput = document.getElementById("searchInput");
    const searchBtn = document.getElementById("searchBtn");
    const addRequestBtn = document.getElementById("addRequestBtn");
    const requestModal = document.getElementById("requestModal");
    const closeModal = document.querySelector(".close");
    const requestForm = document.getElementById("requestForm");
    const modalTitle = document.getElementById("modalTitle");

    let requests = [];
    let editId = null;

    function fetchRequests() {
        fetch('MenuSolicitudes_api.PHP?action=get_solicitudes')
            .then(res => {
                if (!res.ok) throw new Error("Error al obtener solicitudes");
                return res.json();
            })
            .then(data => {
                if (!data.success) throw new Error(data.message || "Error en la respuesta del servidor");
                requests = data.solicitudes;
                renderRequests(requests);
            })
            .catch(err => {
                console.error('Error al obtener solicitudes:', err);
                alert('Error al cargar solicitudes: ' + err.message);
            });
    }

    function renderRequests(data) {
        const container = document.getElementById("requestsList");
        container.innerHTML = "";

        data.forEach(request => {
            const note = document.createElement("div");
            note.className = "request-note";

            note.innerHTML = `
                <h3>${request.nombre} (ID: ${request.id})</h3>
                <p><strong>Teléfono:</strong> ${request.telefono}</p>
                <p class="direccion"><strong>Dirección:</strong> ${request.ubicacion_origen} → ${request.ubicacion_destino}</p>
                <p><strong>Fecha:</strong> ${request.fecha_solicitud}</p>
                <p><strong>Servicio:</strong> ${request.tipo_servicio}</p>
                <p><strong>Costo:</strong> $${parseFloat(request.costo).toFixed(2)}</p>
                <p><strong>Estado:</strong> ${request.status}</p>
                <div class="actions">
                    <button onclick="editRequest(${request.id})">Editar</button>
                    <button onclick="deleteRequest(${request.id})">Eliminar</button>
                </div>
            `;

            container.appendChild(note);
        });
    }


    searchBtn.addEventListener("click", () => {
        const query = searchInput.value.toLowerCase();
        const filtered = requests.filter(r =>
            r.nombre.toLowerCase().includes(query) ||
            r.telefono.toString().includes(query) ||
            r.tipo_servicio.toLowerCase().includes(query) ||
            r.status.toLowerCase().includes(query)
        );
        renderRequests(filtered);
    });

    addRequestBtn.addEventListener("click", () => {
        requestForm.reset();
        editId = null;
        modalTitle.textContent = "Añadir Solicitud";
        requestModal.style.display = "block";
    });

    closeModal.addEventListener("click", () => {
        requestModal.style.display = "none";
    });

    window.onclick = function(event) {
        if (event.target === requestModal) {
            requestModal.style.display = "none";
        }
    };

    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape") {
            requestModal.style.display = "none";
        }
    });

    window.editRequest = function(id) {
        const request = requests.find(r => r.id == id);
        if (!request) return;

        document.getElementById("Id").value = request.id;
        document.getElementById("nombre").value = request.nombre;
        document.getElementById("telefono").value = request.telefono;
        document.getElementById("email").value = request.email;
        document.getElementById("ubicacion_origen").value = request.ubicacion_origen;
        document.getElementById("ubicacion_destino").value = request.ubicacion_destino;
        document.getElementById("tipo_vehiculo").value = request.tipo_vehiculo;
        document.getElementById("marca").value = request.marca;
        document.getElementById("modelo").value = request.modelo;
        document.getElementById("placa").value = request.placa;
        document.getElementById("foto_vehiculo").value = request.foto_vehiculo;
        document.getElementById("tipo_servicio").value = request.tipo_servicio;
        document.getElementById("descripcion").value = request.descripcion;
        document.getElementById("distancia").value = request.distancia;
        document.getElementById("costo").value = request.costo;
        document.getElementById("metodo_pago").value = request.metodo_pago;


        editId = id;
        modalTitle.textContent = "Editar Solicitud";
        requestModal.style.display = "block";
    };

    window.deleteRequest = function(id) {
        if (!confirm("¿Estás seguro de que deseas eliminar esta solicitud?")) return;
        fetch(`MenuSolicitudes_api.PHP?action=delete_solicitud`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(res => {
            if (!res.ok) throw new Error("Error al eliminar");
            return res.json();
        })
        .then(data => {
            if (!data.success) throw new Error(data.message || "Error desconocido");
            fetchRequests();
        })
        .catch(err => console.error('Error al eliminar:', err));
    };

    requestForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = {
            nombre: document.getElementById("nombre").value.trim(),
            telefono: document.getElementById("telefono").value.trim(),
            email: document.getElementById("email").value.trim(),
            ubicacion_origen: document.getElementById("ubicacion_origen").value.trim(),
            ubicacion_destino: document.getElementById("ubicacion_destino").value.trim(),
            tipo_vehiculo: document.getElementById("tipo_vehiculo").value,
            marca: document.getElementById("marca").value.trim(),
            modelo: document.getElementById("modelo").value.trim(),
            placa: document.getElementById("placa").value.trim(),
            foto_vehiculo: document.getElementById("foto_vehiculo").value.trim(),
            tipo_servicio: document.getElementById("tipo_servicio").value,
            descripcion: document.getElementById("descripcion").value.trim(),
            distancia: document.getElementById("distancia").value.trim(),
            costo: parseFloat(document.getElementById("costo").value),
            metodo_pago: document.getElementById("metodo_pago").value,
            consentimiento: document.getElementById("consentimiento").checked ? 1 : 0,
            fecha_solicitud: document.getElementById("fecha_solicitud").value,
            status: document.getElementById("status").value,
            gruas_id: parseInt(document.getElementById("gruas_id").value)
        };

        // Validar campos requeridos
        if (!formData.nombre || !formData.fecha_solicitud || !formData.tipo_servicio) {
            alert("Por favor completa los campos obligatorios.");
            return;
        }

        const action = editId ? 'update_solicitud' : 'add_solicitud';
        if (editId) formData.id = editId;

        fetch(`MenuSolicitudes_api.PHP?action=${action}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        })
        .then(res => {
            if (!res.ok) throw new Error("Error al guardar la solicitud");
            return res.json();
        })
        .then(data => {
            if (!data.success) throw new Error(data.message || "Error desconocido");
            requestModal.style.display = "none";
            fetchRequests();
        })
        .catch(err => {
            console.error('Error al guardar:', err);
            alert('Error al guardar: ' + err.message);
        });
    });

    fetchRequests();
});
</script>


</body>
</html>
