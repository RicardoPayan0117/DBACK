<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Solicita nuestro servicio de grúas 24/7. Asistencia rápida y profesional para todo tipo de vehículos.">
    <title>Solicitar Servicio de Grúa | Grúas DBACK</title>
    <link rel="stylesheet" href="CSS/Solicitud_ARCO.css">
<link
  rel="stylesheet"
  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

</head>
<body>
    <header>
        <nav class="navbar" aria-label="Navegación principal">
            <div class="nav-content">
                <a href="index.html" class="navbar-brand">
                    <img src="Elementos/LogoDBACK.png" alt="Logo DBACK" width="50" height="50">
                    <h1>Grúas DBACK</h1>
                </a>
                
                <div class="nav-links">
                    <a href="index.html" class="cta-button">Inicio</a>
                    <a href="tel:+526688253351" class="cta-button accent">Llamar ahora</a>
                </div>
            </div>
        </nav>   
    </header>

    <main>
        <section class="formulario" aria-labelledby="form-title">
            <h2 id="form-title">Solicitar Servicio de Grúa</h2>
            <p class="form-description">Complete el formulario y nos pondremos en contacto lo antes posible.</p>
            
            <form action="solicitud_api.php" method="post" id="servicioForm" enctype="multipart/form-data" novalidate>
                <!-- Información de contacto -->
                <fieldset>
                    <legend>Información de contacto</legend>
                    
                    <div class="form-group">
                        <label for="nombre">Nombre completo:</label>
                        <input type="text" id="nombre" name="nombre" required 
                               pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,50}"
                               placeholder="Ej: Pito Pérez"
                               title="Ingrese un nombre válido (solo letras y espacios, mínimo 3 caracteres)"
                               aria-required="true">
                        <div id="nombre-error" class="error-message" role="alert">Por favor ingrese un nombre válido (mínimo 3 caracteres, solo letras y espacios)</div>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono de contacto:</label>
                        <input type="tel" id="telefono" name="telefono" required 
                                pattern="\d{10}" maxlength="10"
                                placeholder="Ej: 6681234567"
                                title="Ingrese un número de 10 dígitos" aria-required="true">
                        <div id="telefono-error" class="error-message" role="alert">Por favor ingrese un teléfono válido (10 dígitos)</div>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo electrónico:</label>
                        <input type="email" id="email" name="email" 
                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                               placeholder="Ej: juan@ejemplo.com"
                               title="Ingrese un correo electrónico válido">
                        <div id="email-error" class="error-message" role="alert">Por favor ingrese un correo electrónico válido</div>
                    </div>
                </fieldset>

                <!-- Sección de ubicaciones -->
                <fieldset>
                    <legend>Ubicaciones</legend>
                    
                    <div class="location-section">
                        <h3>Ubicación de Recogida</h3>
                        <div class="form-group">
                            <label for="ubicacion_origen">Ubicación actual del vehículo:</label>
                            <div class="location-input-container">
                                <input type="text" id="ubicacion_origen" name="ubicacion_origen" required 
                                       minlength="5"
                                       placeholder="Dirección o punto de referencia" 
                                       list="ubicaciones_origen"
                                       title="Ingrese una ubicación válida (mínimo 5 caracteres)"
                                       aria-required="true">
                                <button type="button" id="obtenerUbicacionOrigen" class="location-button" aria-label="Obtener mi ubicación actual">
                                    <img src="https://cdn-icons-png.flaticon.com/512/535/535137.png" alt="Ubicación" width="20" height="20">
                                </button>
                            </div>
                            <div id="mapaOrigen" style="height: 300px; margin-bottom: 20px;"></div>
                            <div id="ubicacion_origen-error" class="error-message" role="alert">Por favor ingrese una ubicación válida (mínimo 5 caracteres)</div>
                            <datalist id="ubicaciones_origen"></datalist>
                        </div>
                    </div>
                    <div class="location-section">
    <h3>Ubicación de Entrega</h3>
    <div class="form-group">
        <label for="ubicacion_destino">¿A dónde necesita llevar el vehículo?</label>
        
        <!-- Agrupamos el input y el botón juntos -->
                  <div class="location-input-container">
                      <input type="text" id="ubicacion_destino" name="ubicacion_destino" required 
                            minlength="5"
                            placeholder="Dirección o punto de referencia" 
                            list="ubicaciones_destino"
                            title="Ingrese una ubicación válida (mínimo 5 caracteres)"
                            aria-required="true">
                      <button type="button" id="obtenerUbicacionDestino" class="location-button" aria-label="Obtener mi ubicación actual">
                          <img src="https://cdn-icons-png.flaticon.com/512/535/535137.png" alt="Ubicación" width="20" height="20">
                      </button>
                       </div>

                        <!-- Aquí se coloca el mapa FUERA del contenedor -->
                        <div id="mapaDestino" style="height: 300px; margin-top: 10px;"></div>

                       <div id="ubicacion_destino-error" class="error-message" role="alert">Por favor ingrese una ubicación válida (mínimo 5 caracteres)</div>
                       <datalist id="ubicaciones_destino"></datalist>
                    </div>
                </div>

                </fieldset>

                <!-- Información del vehículo -->
                <fieldset>
                    <legend>Información del vehículo</legend>
                    
                    <div class="form-group">
                        <label for="tipo_vehiculo">Tipo de vehículo:</label>
                        <select id="tipo_vehiculo" name="tipo_vehiculo" required aria-required="true">
                            <option value="">Seleccione una opción</option>
                            <option value="Automóvil">Automóvil</option>
                            <option value="Camioneta">Camioneta</option>
                            <option value="Motocicleta">Motocicleta</option>
                            <option value="Autobus">Autobus</option>
                            <option value="Submarino">Submarino</option>
                            <option value="Baica">Baica</option> <!-- Unica Linea escrita por Humberto Roman-->
                        </select>
                        <div id="vehiculo-error" class="error-message" role="alert">Por favor seleccione un tipo de vehículo</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="marca">Marca del vehículo:</label>
                        <input type="text" id="marca" name="marca" required
                               minlength="2"
                               placeholder="Ej: Toyota, Ford, Nissan"
                               aria-required="true">
                        <div id="marca-error" class="error-message" role="alert">Por favor ingrese la marca del vehículo (mínimo 2 caracteres)</div>
                    </div>

                     <div class="form-group">
                        <label for="placa">Placa del vehículo:</label>
                        <input type="text" id="placa" name="placa" required
                               minlength="7"
                               placeholder="ABC-1234"
                               aria-required="true">
                        <div id="placa-error" class="error-message" role="alert">Por favor ingrese la placa del vehiculo</div>
                    </div>

                    <div class="form-group">
                        <label for="modelo">Modelo del vehículo:</label>
                        <input type="text" id="modelo" name="modelo" required
                               minlength="2"
                               placeholder="Ej: Corolla, F-150, Sentra"
                               aria-required="true">
                        <div id="modelo-error" class="error-message" role="alert">Por favor ingrese el modelo del vehículo (mínimo 2 caracteres)</div>
                    </div>



                    <div class="form-group">
                        <label for="foto">Foto del vehículo (opcional):</label>
                        <input type="file" id="foto" name="foto" accept="image/jpeg, image/png">
                        <p><small>Formatos aceptados: JPG, PNG. Tamaño máximo: 5MB</small></p>
                        <div id="foto-error" class="error-message" role="alert">El archivo debe ser una imagen (JPG o PNG) y no exceder 5MB</div>
                    </div>
                </fieldset>

                <!-- Información del servicio -->
                <fieldset>
                    <legend>Detalles del servicio</legend>
                    
                    <div class="form-group">
                        <label for="tipo_servicio">Tipo de Servicio:</label>
                        <select id="tipo_servicio" name="tipo_servicio" required aria-required="true">
                            <option value="">Seleccione una opción</option>
                            <option value="remolque">Remolque</option>
                            <option value="Cambio de batería">Cambio de batería</option>
                            <option value="Suministro de gasolina">Suministro de gasolina</option>
                            <option value="Cambio de llanta">Cambio de llanta</option>
                            <option value="Servicio de arranque">Servicio de arranque</option>
                            <option value="Otro Servicio">Otro servicio</option>
                        </select>
                        <div id="tipo_servicio-error" class="error-message" role="alert">Por favor seleccione un tipo de servicio</div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción del problema:</label>
                        <textarea id="descripcion" name="descripcion" rows="4" 
                                  minlength="10" maxlength="500"
                                  placeholder="Describa brevemente la situación"
                                  title="La descripción debe tener entre 10 y 500 caracteres"></textarea>
                        <div id="descripcion-error" class="error-message" role="alert">La descripción debe tener entre 10 y 500 caracteres</div>
                    </div>
                </fieldset>
                
                <!-- Información de cálculo de distancia y costos -->
                <div class="info-container" aria-live="polite">
                    <div class="form-group">
                        <label for="distancia">Distancia estimada:</label>
                        <input type="text" id="distancia_input" name="distancia" readonly 
                               placeholder="Calculando..." value="" aria-readonly="true">
                    </div>
                    
                    <div class="form-group">
                        <label for="costo">Costo estimado:</label>
                        <input type="text" id="costo_input" name="costo" readonly 
                               placeholder="Calculando..." value="" aria-readonly="true">
                    </div>
                </div>
                
                <!-- Sección de resumen para pago -->
                <div class="summary-section" aria-live="polite">
                    <h3>Resumen de Solicitud</h3>
                    <div class="summary-row">
                        <span>Cliente:</span>
                        <span id="display-nombre">(Por completar)</span>
                    </div>
                    <div class="summary-row">
                        <span>Correo:</span>
                        <span id="display-email">(No especificado)</span>
                    </div>
                    <div class="summary-row">
                        <span>Distancia estimada:</span>
                        <span id="distancia">0 km</span>
                    </div>
                    <div class="summary-row">
                        <span>Costo total estimado:</span>
                        <span id="costo">$0.00 MXN</span>
                    </div>
                    <div class="summary-row">
                        <span>Depósito requerido (20%):</span>
                        <span id="display-deposito">$0.00 MXN</span>
                    </div>
                    <div class="summary-row">
                        <span>Pago restante:</span>
                        <span id="display-restante">$0.00 MXN</span>
                    </div>
                </div>
                
                <!-- Información de pago -->
                <fieldset>
                    <legend>Opciones de pago</legend>
                    <p>Para asegurar su servicio, puede realizar un depósito del 20% del costo total estimado.</p>
                    <p>El monto restante se pagará al finalizar el servicio.</p>
                    
                    <!-- Selección de método de pago -->
                    <div class="payment-methods" role="radiogroup" aria-labelledby="payment-methods-label">
                        <h4 id="payment-methods-label">Seleccione método de pago:</h4>
                        
                        <div class="payment-method" tabindex="0" role="radio" aria-checked="true" onclick="selectPaymentMethod('efectivo')" onkeydown="handlePaymentMethodKey(event, 'efectivo')">
                            <input type="radio" name="metodo_pago" id="metodo_efectivo" value="efectivo" <?php echo (!isset($_POST['metodo_pago_seleccionado']) || $_POST['metodo_pago_seleccionado'] == 'efectivo') ? 'checked' : ''; ?>>
                            <img src="https://cdn-icons-png.flaticon.com/512/639/639365.png" alt="Efectivo" class="payment-method-icon" width="40" height="40">
                            <div class="payment-method-details">
                                <h4 class="payment-method-title">Efectivo</h4>
                                <p class="payment-method-description">Pago en efectivo al momento de recibir el servicio</p>
                            </div>
                        </div>
                        
                        <div class="payment-method" tabindex="0" role="radio" aria-checked="false" onclick="selectPaymentMethod('paypal')" onkeydown="handlePaymentMethodKey(event, 'paypal')">
                            <input type="radio" name="metodo_pago" id="metodo_paypal" value="paypal" <?php echo (isset($_POST['metodo_pago_seleccionado']) && $_POST['metodo_pago_seleccionado'] == 'paypal') ? 'checked' : ''; ?>>
                            <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" alt="PayPal" class="payment-method-icon" width="40" height="40">
                            <div class="payment-method-details">
                                <h4 class="payment-method-title">PayPal</h4>
                                <p class="payment-method-description">Pago seguro con tarjeta de crédito o cuenta PayPal</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contenedor para efectivo -->
                    <div id="efectivo-container" class="payment-container" style="<?php echo (!isset($_POST['metodo_pago_seleccionado']) || $_POST['metodo_pago_seleccionado'] == 'efectivo') ? 'display:block;' : 'display:none;'; ?>">
                        <p>Ha seleccionado pago en efectivo.</p>
                        <p>El monto total de <strong id="efectivo-total"><?php echo isset($_POST['costo']) ? '$' . htmlspecialchars($_POST['costo']) . ' MXN' : '$0.00 MXN'; ?></strong> deberá ser pagado al finalizar el servicio.</p>
                        <p><strong>Nota:</strong> Al elegir este método, un operador se pondrá en contacto con usted para confirmar los detalles.</p>
                    </div>
                    
                    <!-- Contenedor del botón de PayPal -->
                    <div id="paypal-container" class="payment-container" style="<?php echo (isset($_POST['metodo_pago_seleccionado']) && $_POST['metodo_pago_seleccionado'] == 'paypal') ? 'display:block;' : 'display:none;'; ?>">
                        <div id="paypal-button-container">
                            <?php if (isset($_POST['paypal_order_id']) && $_POST['paypal_order_id'] != ''): ?>
                            <div class="paypal-success">
                                <h4>¡Pago completado con éxito!</h4>
                                <p>ID de transacción: <?php echo htmlspecialchars($_POST['paypal_order_id']); ?></p>
                                <p>Estado: <?php echo htmlspecialchars($_POST['paypal_status']); ?></p>
                            </div>
                            <?php else: ?>
                            <button id="custom-paypal-button" class="paypal-button" type="button" onclick="initiatePayPalPayment()">
                                <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" alt="PayPal Logo" width="20" height="20">
                                Pagar con PayPal
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <input type="hidden" id="paypal_order_id" name="paypal_order_id" value="<?php echo isset($_POST['paypal_order_id']) ? htmlspecialchars($_POST['paypal_order_id']) : ''; ?>">
                    <input type="hidden" id="paypal_status" name="paypal_status" value="<?php echo isset($_POST['paypal_status']) ? htmlspecialchars($_POST['paypal_status']) : ''; ?>">
                    <input type="hidden" id="paypal_email" name="paypal_email" value="<?php echo isset($_POST['paypal_email']) ? htmlspecialchars($_POST['paypal_email']) : ''; ?>">
                    <input type="hidden" id="paypal_name" name="paypal_name" value="<?php echo isset($_POST['paypal_name']) ? htmlspecialchars($_POST['paypal_name']) : ''; ?>">
                    <input type="hidden" id="metodo_pago_seleccionado" name="metodo_pago_seleccionado" value="<?php echo isset($_POST['metodo_pago_seleccionado']) ? htmlspecialchars($_POST['metodo_pago_seleccionado']) : 'efectivo'; ?>">
                </fieldset>

                <!-- Checkbox para confirmar consentimiento -->
                <div id="privacy-container" class="privacy-checkbox-container">
                    <input type="checkbox" id="consentimiento" name="consentimiento" required aria-required="true">
                    <label for="consentimiento">
                        <span class="privacy-text">
                            He leído y acepto la 
                            <span class="privacy-link" id="openConsentModal" tabindex="0" role="button">política de privacidad</span>
                        </span>
                    </label>
                    <div id="consentimiento-error" class="error-message" role="alert" hidden>Debe aceptar la política de privacidad para continuar</div>
                </div>

                
                <!-- Botones de acción -->
                <div class="action-buttons">
                    <button type="submit" class="cta-button" disabled>Enviar Solicitud</button>
                </div>
            </form>
            <p class="emergency-note">Para emergencias inmediatas, llame al <a href="tel:+526688253351" class="emergency-phone">668-825-3351</a></p>
        </section>

        <!-- Modal de política de privacidad -->
        <div id="consentModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="consentModalTitle" hidden>
            <div class="modal-container">
                <button class="close-modal" id="closeModal" aria-label="Cerrar modal">&times;</button>
                
                <div class="header-decoration">
                    <h1 id="consentModalTitle">Consentimiento de Datos Personales</h1>
                </div>
                
                <div class="modal-content">
                    <p>Por este medio, expreso y otorgo mi consentimiento a Grúas DBACK en la recopilación, almacenamiento y uso de mis datos personales, con los fines relacionados con la prestación y uso de los servicios prestados por Grúas DBACK, tales como solicitudes de asistencia, el seguimiento de vehículos atendidos y la emisión de recibos o facturas de pago correspondiente.</p>
                    
                    <h2>Datos personales recopilados</h2>
                    <ul>
                        <li>Nombre completo</li>
                        <li>Domicilio</li>
                        <li>Número de teléfono</li>
                        <li>Correo electrónico</li>
                        <li>Datos del vehículo</li>
                        <li>Información de la solicitud de servicio</li>
                        <li>Ubicación del servicio</li>
                        <li>Fecha y hora de la solicitud</li>
                    </ul>
                    
                    <h2>Tratamiento de datos</h2>
                    <p>La información recopilada será tratada en estricto apego a lo establecido por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares y será utilizada exclusivamente para los fines mencionados.</p>
                    
                    <h2>Medios de contacto de Grúas DBACK para ejercer mis derechos ARCO</h2>
                    <p>Para más información sobre los derechos ARCO, visita: <a href="https://www.gob.mx/cms/uploads/attachment/file/428335/DDP_Gu_a_derechos_ARCO_13Dic18.pdf" target="_blank">Guía Derechos ARCO</a></p>
                    <p>Correo electrónico: <a href="mailto:protecciondedatos@gruasdback.com">protecciondedatos@gruasdback.com</a></p>
                    <p>Teléfono: <a href="tel:6688132905">668 813 2905</a></p>
                    <p>Dirección: Manuel Castro Elizalde 895 SUR, Luis Donaldo Colosio, Colón, 81233 Los Mochis, Sin.</p>
                    
                    <h2>Consentimiento</h2>
                    <p>Al hacer clic en "Aceptar", confirmo que he leído y comprendido los términos de esta autorización y que otorgo mi consentimiento para el tratamiento de mis datos personales a Grúas DBACK.</p>
                    
                    <div class="button-container">
                        <button class="button" id="acceptConsent">Aceptar</button>
                        <button class="button button-reject" id="rejectConsent">Rechazar</button>
                    </div>
                </div>
                
                <div class="footer-decoration">
                    <p>Grúas DBACK - Documento de Consentimiento de Datos Personales</p>
                </div>
            </div>
        </div>
        
        <!-- Modal de notificación de rechazo -->
        <div id="rejectModal" class="modal-overlay" role="alertdialog" aria-modal="true" aria-labelledby="rejectModalTitle" hidden>
            <div class="small-modal-container">
                <h2 id="rejectModalTitle">Consentimiento Requerido</h2>
                <p>Debe aceptar la política de privacidad para utilizar nuestros servicios. 
                    No podemos procesar su solicitud sin su consentimiento para el tratamiento de datos personales.</p>
                <button class="notification-button" id="closeRejectModal">Entendido</button>
            </div>
        </div>
        
        <!-- Modal de éxito al enviar -->
        <div id="successModal" class="success-modal-overlay" role="alertdialog" aria-modal="true" aria-labelledby="successModalTitle" hidden>
            <div class="success-modal-container">
                <h2 id="successModalTitle">¡Solicitud Enviada!</h2>
                <p>Su solicitud se ha enviado con éxito.</p>
                <p>Nos pondremos en contacto con usted a la brevedad posible.</p>
                <button class="success-button" id="closeSuccessModal">Aceptar</button>
            </div>
        </div>
    </main>

//no le muevan si no saben
<script>
document.getElementById('obtenerUbicacionOrigen').addEventListener('click', () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;

        document.getElementById('ubicacion_origen').value = `${lat},${lon}`;
        actualizarDistanciaYCosto(); // <--- AÑADE ESTO
      },
      (error) => {
        alert('No se pudo obtener la ubicación. Por favor, ingrésela manualmente.');
        console.error(error);
      }
    );
  } else {
    alert('Geolocalización no es soportada por este navegador.');
  }
});


document.getElementById('obtenerUbicacionDestino').addEventListener('click', () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        document.getElementById('ubicacion_destino').value = `${lat},${lon}`;
        actualizarDistanciaYCosto(); // <--- AÑADE ESTO
      },
      (error) => {
        alert('No se pudo obtener la ubicación. Por favor, ingrésela manualmente.');
        console.error(error);
      }
    );
  } else {
    alert('Geolocalización no es soportada por este navegador.');
  }
});

// Inicializar mapa centrado en coordenadas por defecto (puedes usar cualquier lugar)
  document.addEventListener("DOMContentLoaded", function () {
    // 🆕 Mapa para origen
  const mapaOrigen = L.map('mapaOrigen').setView([25.7896, -109.0053], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(mapaOrigen);

  let marcadorOrigen = null;
  mapaOrigen.on('click', function (e) {
    const { lat, lng } = e.latlng;
    if (marcadorOrigen) {
      marcadorOrigen.setLatLng(e.latlng);
    } else {
      marcadorOrigen = L.marker(e.latlng).addTo(mapaOrigen);
    }
    document.getElementById('ubicacion_origen').value = `${lat},${lng}`;
    actualizarDistanciaYCosto();
  });
  // Mapa para destino (ya lo tienes)
  const mapaDestino = L.map('mapaDestino').setView([25.7896, -109.0053], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(mapaDestino);

  let marcadorDestino = null;
  mapaDestino.on('click', function (e) {
    const { lat, lng } = e.latlng;
    if (marcadorDestino) {
      marcadorDestino.setLatLng(e.latlng);
    } else {
      marcadorDestino = L.marker(e.latlng).addTo(mapaDestino);
    }
    document.getElementById('ubicacion_destino').value = `${lat},${lng}`;
    actualizarDistanciaYCosto();
  });


});

function calcularDistancia(coord1, coord2) {
    const [lat1, lon1] = coord1.split(',').map(coord => parseFloat(coord));
    const [lat2, lon2] = coord2.split(',').map(coord => parseFloat(coord));

    const R = 6371; // Radio de la Tierra en km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;

    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distancia = R * c;

    console.log(`Origen: ${coord1}, Destino: ${coord2}`);
    console.log(`Distancia cruda: ${distancia} km`);

    return distancia;
}



document.getElementById('ubicacion_origen').addEventListener('change', actualizarDistanciaYCosto);
document.getElementById('ubicacion_destino').addEventListener('change', actualizarDistanciaYCosto);


function actualizarDistanciaYCosto() {
    const origen = document.getElementById('ubicacion_origen').value;
    const destino = document.getElementById('ubicacion_destino').value;

    if (!origen || !destino) {
        console.log("Faltan coordenadas");
        return;
    }

    const distancia = calcularDistancia(origen, destino);
    const distanciaRedondeada = distancia.toFixed(2);

    // Costo por km
    const costoPorKm = 15;
    const base = 250; // Costo base fijo
    const costoEstimado = (distancia * costoPorKm + base).toFixed(2);

    document.getElementById('distancia').textContent = `${distanciaRedondeada} km`;
    document.getElementById('costo').textContent = `$${costoEstimado} MXN`;

    const deposito = (costoEstimado * 0.20).toFixed(2);
    const restante = (costoEstimado - deposito).toFixed(2);

    document.getElementById('distancia_input').value = distanciaRedondeada;
    document.getElementById('costo_input').value = `$${costoEstimado} MXN`;

    document.getElementById('display-deposito').textContent = `$${deposito} MXN`;
    document.getElementById('display-restante').textContent = `$${restante} MXN`;
    document.getElementById('efectivo-total').textContent = `$${costoEstimado} MXN`;

    console.log(`Distancia calculada: ${distanciaRedondeada} km`);
    console.log(`Costo estimado: $${costoEstimado} MXN`);
}




document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('servicioForm');
    const consentModal = document.getElementById('consentModal');
    const openConsentModal = document.getElementById('openConsentModal');
    const closeModal = document.getElementById('closeModal');
    const acceptConsent = document.getElementById('acceptConsent');
    const rejectConsent = document.getElementById('rejectConsent');
    const rejectModal = document.getElementById('rejectModal');
    const closeRejectModal = document.getElementById('closeRejectModal');
    const successModal = document.getElementById('successModal');
    const closeSuccessModal = document.getElementById('closeSuccessModal');

    
    // Muestra el modal de éxito
    function mostrarModalExito() {
        successModal.removeAttribute('hidden');
    }

    // Cierra el modal de éxito
    closeSuccessModal.addEventListener('click', () => {
        successModal.setAttribute('hidden', true);
        window.location.href = 'index.html';
    });

    // Mostrar el modal al hacer clic en el texto "política de privacidad"
    openConsentModal.addEventListener('click', () => {
        consentModal.removeAttribute('hidden');
    });

    // Cerrar el modal principal
    closeModal.addEventListener('click', () => {
        consentModal.setAttribute('hidden', true);
    });

    // Botón "Aceptar" dentro del modal
    acceptConsent.addEventListener('click', () => {
        consentModal.setAttribute('hidden', true);
        document.getElementById('consentimiento').checked = true; // Marca el checkbox
    });

    // Botón "Rechazar" dentro del modal
    rejectConsent.addEventListener('click', () => {
        consentModal.setAttribute('hidden', true);
        document.getElementById('consentimiento').checked = false;
        rejectModal.removeAttribute('hidden');
    });

    // Cerrar el modal de rechazo
    closeRejectModal.addEventListener('click', () => {
        rejectModal.setAttribute('hidden', true);
    });
    const consentimientoCheckbox = document.getElementById('consentimiento');
    const enviarBtn = document.getElementById('cta-button');

    // Actualiza el estado del botón según el checkbox
    function toggleBotonEnviar() {
        enviarBtn.disabled = !consentimientoCheckbox.checked;
    }

    // Ejecutar al cargar la página por si el checkbox ya está marcado
    toggleBotonEnviar();

    // Escuchar cambios en el checkbox
    consentimientoCheckbox.addEventListener('change', toggleBotonEnviar);

  // Campos
  const nombre = form.nombre;
  const telefono = form.telefono;
  const email = form.email;
  const ubicacion_origen = form.ubicacion_origen;
  const ubicacion_destino = form.ubicacion_destino;
  const tipo_vehiculo = form.tipo_vehiculo;
  const marca = form.marca;
  const modelo = form.modelo;
  const placa = form.placa;
  const foto = form.foto;
  const tipo_servicio = form.tipo_servicio;
  const descripcion = form.descripcion;
  const consentimiento = form.consentimiento;

  // Contenedores de error
  const errores = {
    nombre: document.getElementById('nombre-error'),
    telefono: document.getElementById('telefono-error'),
    email: document.getElementById('email-error'),
    ubicacion_origen: document.getElementById('ubicacion_origen-error'),
    ubicacion_destino: document.getElementById('ubicacion_destino-error'),
    tipo_vehiculo: document.getElementById('vehiculo-error'),
    marca: document.getElementById('marca-error'),
    modelo: document.getElementById('modelo-error'),
    placa: document.getElementById("placa"),
    foto: document.getElementById('foto-error'),
    tipo_servicio: document.getElementById('tipo_servicio-error'),
    descripcion: document.getElementById('descripcion-error'),
    consentimiento: document.getElementById('consentimiento-error'),
  };


  // Validadores individuales
  function validarNombre() {
    const regex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,50}$/;
    if (!nombre.value.trim() || !regex.test(nombre.value.trim())) {
      errores.nombre.style.display = 'block';
      return false;
    }
    errores.nombre.style.display = 'none';
    return true;
  }

  function validarTelefono() {
    const regex = /^\d{10}$/;
    if (!telefono.value.trim() || !regex.test(telefono.value.trim())) {
      errores.telefono.style.display = 'block';
      return false;
    }
    errores.telefono.style.display = 'none';
    return true;
  }

  function validarEmail() {
    if (email.value.trim() === '') {
      // email es opcional, no mostrar error si está vacío
      errores.email.style.display = 'none';
      return true;
    }
    const regex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
    if (!regex.test(email.value.trim())) {
      errores.email.style.display = 'block';
      return false;
    }
    errores.email.style.display = 'none';
    return true;
  }

  function validarUbicacionOrigen() {
    if (!ubicacion_origen.value.trim() || ubicacion_origen.value.trim().length < 7) {
      errores.ubicacion_origen.style.display = 'block';
      return false;
    }
    errores.ubicacion_origen.style.display = 'none';
    return true;
  }

  function validarUbicacionDestino() {
    if (!ubicacion_destino.value.trim() || ubicacion_destino.value.trim().length < 7) {
      errores.ubicacion_destino.style.display = 'block';
      return false;
    }
    errores.ubicacion_destino.style.display = 'none';
    return true;
  }

  function validarTipoVehiculo() {
    if (!tipo_vehiculo.value) {
      errores.tipo_vehiculo.style.display = 'block';
      return false;
    }
    errores.tipo_vehiculo.style.display = 'none';
    return true;
  }

  function validarMarca() {
    if (!marca.value.trim() || marca.value.trim().length < 2) {
      errores.marca.style.display = 'block';
      return false;
    }
    errores.marca.style.display = 'none';
    return true;
  }
function validarPlaca() {
  const regex = /^[A-Z0-9]{6,7}$/i;
  if (!placa.value.trim() || !regex.test(placa.value.trim())) {
    errores.placa.style.display = 'block';
    return false;
  }
  errores.placa.style.display = 'none';
  return true;
}


  function validarModelo() {
    if (!modelo.value.trim() || modelo.value.trim().length < 2) {
      errores.modelo.style.display = 'block';
      return false;
    }
    errores.modelo.style.display = 'none';
    return true;
  }

  function validarFoto() {
    if (foto.files.length === 0) {
      // Es opcional, no error si no hay archivo
      errores.foto.style.display = 'none';
      return true;
    }
    const file = foto.files[0];
    const validTypes = ['image/jpeg', 'image/png'];
    if (!validTypes.includes(file.type) || file.size > 5 * 1024 * 1024) {
      errores.foto.style.display = 'block';
      return false;
    }
    errores.foto.style.display = 'none';
    return true;
  }

  function validarTipoServicio() {
    if (!tipo_servicio.value) {
      errores.tipo_servicio.style.display = 'block';
      return false;
    }
    errores.tipo_servicio.style.display = 'none';
    return true;
  }

  function validarDescripcion() {
    const val = descripcion.value.trim();
    if (val.length > 0 && (val.length < 10 || val.length > 500)) {
      errores.descripcion.style.display = 'block';
      return false;
    }
    errores.descripcion.style.display = 'none';
    return true;
  }
function convertirImagenABase64(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = () => resolve(reader.result); // INCLUYE el encabezado data:image/...
    reader.onerror = error => reject(error);
    reader.readAsDataURL(file); // OJO: esto es lo correcto
  });
}

  function validarConsentimiento() {
    if (!consentimiento.checked) {
      errores.consentimiento.style.display = 'block';
      return false;
    }
    errores.consentimiento.style.display = 'none';
    return true;
  }

  // Actualiza resumen
  function actualizarResumen() {
    document.getElementById('display-nombre').textContent = nombre.value.trim() || '(Por completar)';
    document.getElementById('display-email').textContent = email.value.trim() || '(No especificado)';
  }

  // Validaciones en vivo
  [nombre, telefono, email, ubicacion_origen, ubicacion_destino, tipo_vehiculo, marca, modelo, foto, tipo_servicio, descripcion, consentimiento].forEach(field => {
    field.addEventListener('input', () => {
      switch (field.name) {
        case 'nombre': validarNombre(); break;
        case 'telefono': validarTelefono(); break;
        case 'email': validarEmail(); break;
        case 'ubicacion_origen': validarUbicacionOrigen(); break;
        case 'ubicacion_destino': validarUbicacionDestino(); break;
        case 'tipo_vehiculo': validarTipoVehiculo(); break;
        case 'marca': validarMarca(); break;
        case 'placa': validarPlaca(); break;
        case 'modelo': validarModelo(); break;
        case 'foto': validarFoto(); break;
        case 'tipo_servicio': validarTipoServicio(); break;
        case 'descripcion': validarDescripcion(); break;
        case 'consentimiento': validarConsentimiento(); break;
      }
      actualizarResumen();
    });
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const validaciones = [
      validarNombre(),
      validarTelefono(),
      validarEmail(),
      validarUbicacionOrigen(),
      validarUbicacionDestino(),
      validarTipoVehiculo(),
      validarMarca(),
      validarPlaca(),
      validarModelo(),
      validarFoto(),
      validarTipoServicio(),
      validarDescripcion(),
      validarConsentimiento()
    ];

    if (validaciones.every(v => v)) {
      const datosFormulario = {
        nombre: nombre.value.trim(),
        telefono: parseInt(telefono.value.trim()),
        email: email.value.trim(),
        ubicacion_origen: ubicacion_origen.value.trim(),
        ubicacion_destino: ubicacion_destino.value.trim(),
        tipo_vehiculo: tipo_vehiculo.value.trim(),
        marca: marca.value.trim(),
        modelo: modelo.value.trim(),
        placa: form.placa ? form.placa.value.trim() : '',
        foto_vehiculo: null, // si quieres base64, hay que leer el archivo
        tipo_servicio: tipo_servicio.value.trim(),
        descripcion: descripcion.value.trim(),
        distancia: document.getElementById("distancia").textContent.replace(" km", "").trim(),
        costo: document.getElementById("costo").textContent.replace(" MXN", "").replace("$", "").trim(),
        metodo_pago: "Efectivo",
        consentimiento: consentimiento.checked
      };

      if (foto.files.length > 0) {
          try {
            datosFormulario.foto_vehiculo = await convertirImagenABase64(foto.files[0]);
          } catch (error) {
              console.error('Error al convertir imagen a base64:', error);
              errores.foto.style.display = 'block';
            return; // detener envío
          }
        }

      console.log("Datos a enviar:", JSON.stringify(datosFormulario, null, 2));
      // Enviar con fetch
      fetch("solicitud_api.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(datosFormulario)
      })
      .then(res => res.json())
      .then(data => {
        // Mostrar modal de éxito si todo va bien
        document.getElementById('successModal').hidden = false;
        console.log("Respuesta del servidor:", data);
      })
      .catch(err => {
        console.error("Error al enviar la solicitud:", err);
        alert("Ocurrió un error al enviar el formulario.");
      });

    } else {
      const firstErrorField = [
        nombre, telefono, email, ubicacion_origen, ubicacion_destino, tipo_vehiculo,
        marca, placa, modelo, foto, tipo_servicio, descripcion, consentimiento
      ].find((field, i) => !validaciones[i]);
      if (firstErrorField) firstErrorField.focus();
    }
  });
  function enviarDatos(datosFormulario) {
  fetch("solicitud_api.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(datosFormulario)
  })
  .then(res => res.json())
  .then(data => {
    document.getElementById('successModal').hidden = false;
    console.log("Respuesta del servidor:", data);
  })
  .catch(err => {
    console.error("Error al enviar la solicitud:", err);
    alert("Ocurrió un error al enviar el formulario.");
  });
}

});

</script>
</body>


</html>