<?php
include 'db_config.php';

// Obtener detalles del próximo bus
$sql_next = "SELECT * FROM detalles_bus ORDER BY id DESC LIMIT 1";
$result_next = $conn->query($sql_next);
$bus_next = $result_next->fetch_assoc();

// Obtener detalles del bus anterior
$sql_previous = "SELECT * FROM detalles_bus ORDER BY id DESC LIMIT 1 OFFSET 1";
$result_previous = $conn->query($sql_previous);
$bus_previous = $result_previous->fetch_assoc();

// Obtener todos los marcadores
$sql_markers = "SELECT * FROM detalles_bus";
$result_markers = $conn->query($sql_markers);
$markers = [];

if ($result_markers->num_rows > 0) {
    while ($row = $result_markers->fetch_assoc()) {
        $markers[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo de Transporte Público</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="css/miestilo.css">
    <link rel="icon" href="images/icono.png">
    
    <style>
        #map {
            height: 300px; /* Initial height for mobile view */
            width: 100%;
        }
        .container {
            margin-top: 20px;
            padding: 0 15px; /* Padding adjusted for mobile */
        }
        .form-container {
            display: flex;
            flex-direction: column;
        }
        .map-section, .form-section {
            flex: 1;
            margin-bottom: 20px;
        }
        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .btn-container .btn {
            margin: 0 5px;
            width: 100%; /* Full-width buttons on mobile */
            font-size: 1.2rem; /* Increase button text size */
        }

        /* Media Query for larger screens */
        @media (min-width: 768px) {
            #map {
                height: 500px; /* Restore height for larger screens */
            }
            .form-container {
                flex-direction: row;
            }
            .map-section {
                margin-right: 20px;
            }
            .form-section {
                margin-bottom: 0;
            }
            .btn-container .btn {
                width: auto; /* Restore button width for larger screens */
                font-size: 1rem; /* Restore original button text size */
                margin: 0 10px; /* Adjust horizontal margin for buttons */
            }
        }

        #bus-info {
            padding-left: 15px;
        }

        .remove-marker {
            cursor: pointer;
            color: red;
            font-size: 16px;
        }

        /* Additional responsive styles */
        @media (max-width: 768px) {
            #bus-info {
                padding-left: 0;
                margin-top: 20px;
            }

            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
        }

        @media (max-width: 576px) {
            #map {
                height: 250px;
            }
            
            #bus-info {
                padding-left: 0;
                margin-top: 20px;
            }

            h3 {
                font-size: 1.25rem;
            }

            p {
                font-size: 0.9rem;
            }
        }

        #search-bar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Monitoreo de Transporte Público</h2>
        <div class="d-flex justify-content-center mb-4">
            <a href="index.html" class="btn btn-primary mx-2">Volver a Inicio</a>
            <a href="submit_bus.php" class="btn btn-primary mx-2">Agregar detalles del bus</a>
        </div>

        <!-- Barra de búsqueda -->
        <div id="search-bar" class="input-group">
            <input type="text" id="search-input" class="form-control" placeholder="Buscar lugar...">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="searchLocation()">Buscar</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div id="map"></div>
            </div>
            <div class="col-md-6">
                <div id="bus-info">
                    <h3>Próximo Bus</h3>
                    <?php if ($bus_next): ?>
                        <p><strong>Salida:</strong> <?php echo $bus_next['salida']; ?></p>
                        <p><strong>Llegada:</strong> <?php echo $bus_next['llegada']; ?><strong> Aprox.</strong></p>
                        <p><strong>Inicio:</strong> <?php echo $bus_next['inicio']; ?></p>
                        <p><strong>Destino:</strong> <?php echo $bus_next['destino']; ?></p>
                        <p><strong>Detalles:</strong> <?php echo $bus_next['detalles']; ?></p>
                    <?php else: ?>
                        <p>No hay información disponible.</p>
                    <?php endif; ?>
                    
                    <h3>Bus anterior</h3>
                    <?php if ($bus_previous): ?>
                        <p><strong>Salida:</strong> <?php echo $bus_previous['salida']; ?></p>
                        <p><strong>Llegada:</strong> <?php echo $bus_previous['llegada']; ?><strong> Aprox.</strong></p>
                        <p><strong>Inicio:</strong> <?php echo $bus_previous['inicio']; ?></p>
                        <p><strong>Destino:</strong> <?php echo $bus_previous['destino']; ?></p>
                        <p><strong>Detalles:</strong> <?php echo $bus_previous['detalles']; ?></p>
                    <?php else: ?>
                        <p>No hay información disponible</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([<?php echo $bus_next ? $bus_next['latitud'] : '13.613748'; ?>, <?php echo $bus_next ? $bus_next['longitud'] : '-89.006737'; ?>], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        <?php if ($bus_next): ?>
        var marker = L.marker([<?php echo $bus_next['latitud']; ?>, <?php echo $bus_next['longitud']; ?>]).addTo(map)
            .bindPopup('Estación Actual: <?php echo $bus_next['inicio']; ?><br> Salida del próximo bus a las: <?php echo $bus_next['salida']; ?>. <br> Hacia: <?php echo $bus_next['destino']; ?>')
            .openPopup();
        <?php endif; ?>

        var markers = <?php echo json_encode($markers); ?>;
        var markerLayerGroup = L.layerGroup().addTo(map);

        markers.forEach(function(marker) {
            var markerObj = L.marker([marker.latitud, marker.longitud]).addTo(markerLayerGroup)
                .bindPopup(`<b>Hora de Salida:</b> ${marker.salida}<br>
                            <b>Hora de Llegada:</b> ${marker.llegada}<br>
                            <b>Inicio:</b> ${marker.inicio}<br>
                            <b>Destino:</b> ${marker.destino}<br>
                            <b>Detalles:</b> ${marker.detalles}<br>
                            <span class="remove-marker" onclick="removeMarker(${marker.id}, this)">&#10006; Eliminar</span>`);
        });

       function removeMarker(markerId, element) {
            if (confirm('¿Estás seguro de que deseas eliminar este marcador?')) {
                // Enviar solicitud para eliminar el marcador
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'drop_marker.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert('Marcador eliminado exitosamente.');
                        // Eliminar el marcador del mapa
                        var marker = element.closest('.leaflet-marker-icon');
                        if (marker) {
                            marker.remove();
                        }
                    } else {
                        alert('Error al eliminar el marcador.');
                    }
                };
                xhr.send('id=' + markerId);
            }
        }

        function searchLocation() {
            var searchInput = document.getElementById('search-input').value;

            if (searchInput.length > 0) {
                var url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchInput)}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            var firstResult = data[0];
                            var lat = firstResult.lat;
                            var lon = firstResult.lon;

                            map.setView([lat, lon], 16);

                            var searchMarker = L.marker([lat, lon]).addTo(map)
                                .bindPopup(firstResult.display_name)
                                .openPopup();
                        } else {
                            alert('No se encontró ninguna ubicación.');
                        }
                    })
                    .catch(error => console.error('Error en la búsqueda:', error));
            } else {
                alert('Por favor, ingrese un término de búsqueda.');
            }
        }
    </script>
</body>
</html>
