<?php
// Simulated data storage
session_start();

// Initialize gruas array in session if not exists
if (!isset($_SESSION['gruas'])) {
    $_SESSION['gruas'] = [];
}

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes(string: $data);
    $data = htmlspecialchars($data);
    return $data;
}

// Handle form submissions
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new grúa
    if (isset($_POST['add_grua'])) {
        $placa = sanitize_input($_POST['placa']);
        $modelo = sanitize_input($_POST['modelo']);
        $tipo = sanitize_input($_POST['tipo']); // Added to handle the type of grúa
        $capacidad = sanitize_input($_POST['capacidad']);
        $estado = sanitize_input($_POST['estado']);
       

        // Generate a unique ID
        $placa = uniqid();

        // Create new grúa
        $nueva_grua = [
            'placa' => $placa,
            'modelo' => $modelo,
            'tipo' => $tipo,
            'capacidad' => $capacidad,
            'estado' => $estado
        ];

        // Add to gruas array
        $_SESSION['gruas'][] = $nueva_grua;
        $message = "Grúa agregada exitosamente";
    }

    // Delete grúa
    if (isset($_POST['delete_grua'])) {
        $placa = sanitize_input($_POST['grua_id']);
        
        // Find and remove the grúa
        foreach ($_SESSION['gruas'] as $key => $grua) {
            if ($grua['id'] === $id) {
                unset($_SESSION['gruas'][$key]);
                $message = "Grúa eliminada exitosamente";
                break;
            }
        }

        // Reindex the array
        $_SESSION['gruas'] = array_values($_SESSION['gruas']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DBACK - Gestión de Grúas</title>
    <link rel="stylesheet" href=".\CSS\MenuEmpleados.CSS">
    <script src="https://kit.fontawesome.com/your-font-awesome-id.js" crossorigin="anonymous"></script>
</head>
<body>

        <!-- Barra de navegación -->
        <nav class="navbar">
            <div class="container">
                <a href="#" class="logo">
                    <span class="logo-icon"><i class="fas fa-truck-pickup"></i></span>
                    <span class="logo-text">Grúas DBACK</span>
                </a>
    
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
    
                <div class="nav-links" id="navLinks">
                    <a href="#" class="nav-link active">Inicio</a>
                    <a href="#" class="nav-link">Empleados</a>
                    <a href="#" class="nav-link">Gruas</a>
                    <a href="#" class="nav-link">Gastos</a>
                </div>
    
                <div class="nav-right">
                    <button class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">2</span>
                    </button>
    
                    <div class="user-menu">
                        <div class="user-avatar">RP</div>
                        <span class="user-name">Ricardo Payán</span>
                    </div>
                </div>
            </div>
        </nav>

    <main>
<body>
    <div class="container">
        <header>
            <h1>Gestión de Grúas</h1>
            <?php if (!empty($message)): ?>
                <div class="message <?php echo (strpos($message, 'Error') !== false) ? 'error' : 'success'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
        </header>

        <main>
            <section class="gruas-form">
                <h2>Agregar Nueva Grúa</h2>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                    <div class="form-group">
                        <label for="placa">Placa:</label>
                        <input type="text" id="placa" name="placa" required>
                    </div>

                    <div class="form-group">
                        <label for="marca">Marca:</label>
                        <input type="text" id="marca" name="marca" required>
                    </div>

                    <div class="form-group">
                        <label for="modelo">Modelo:</label>
                        <input type="text" id="modelo" name="modelo" required>
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo de Grúa:</label>
                        <select id="tipo" name="tipo" required>
                            <option value="Arrastre">Arrastre</option>
                            <option value="Remolque">Remolque</option>
                            <option value="Plataforma">Plataforma</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="estado">Estado:</label>
                        <select id="estado" name="estado" required>
                            <option value="Activo">Activa</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Inactivo">Inactiva</option>
                        </select>
                    </div>

                    <button type="submit" name="add_grua" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Agregar Grúa
                    </button>
                </form>
            </section>

            <section class="gruas-list">
                <h2>Listado de Grúas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th>Modelo</th>
                            <th>Tipo</th>
                            <th>Capacidad (Ton)</th>
                            <th>Estado</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($_SESSION['gruas'])): ?>
                            <?php foreach($_SESSION['gruas'] as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['placa']); ?></td>
                                    <td><?php echo htmlspecialchars($row['modelo']); ?></td>
                                    <td><?php echo htmlspecialchars($row['capacidad']); ?></td>
                                    <td>
                                        <span class="status 
                                            <?php 
                                            echo strtolower($row['estado']) == 'activo' ? 'status-active' : 
                                                 (strtolower($row['estado']) == 'mantenimiento' ? 'status-maintenance' : 'status-inactive'); 
                                            ?>">
                                            <?php echo htmlspecialchars($row['estado']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['tipo']); ?></td> <!-- Display the type of grúa -->
                                    <td>
                                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="action-form">
                                            <input type="hidden" name="grua_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="delete_grua" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="no-data">No hay grúas registradas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        // Optional: Simple client-side validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.gruas-form form');
            form.addEventListener('submit', function(event) {
                const placa = document.getElementById('placa');
                const modelo = document.getElementById('modelo');
                const capacidad = document.getElementById('capacidad');

                if (!placa.value.trim()) {
                    alert('Por favor, ingrese la placa de la grúa');
                    event.preventDefault();
                    return;
                }

                if (!modelo.value.trim()) {
                    alert('Por favor, ingrese el modelo de la grúa');
                    event.preventDefault();
                    return;
                }

                if (capacidad.value <= 0) {
                    alert('La capacidad debe ser mayor a 0');
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
    </main>

    <footer>
        <p>&copy; 2025 DBACK. Todos los derechos reservados.</p>
    </footer>

</body>
</html>