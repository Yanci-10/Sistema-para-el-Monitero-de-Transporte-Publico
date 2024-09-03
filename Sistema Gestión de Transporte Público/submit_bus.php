<?php
session_start();
include 'db_config.php';

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $salida = $_POST['salida'];
    $llegada = $_POST['llegada'];
    $inicio = $_POST['inicio'];
    $destino = $_POST['destino'];
    $detalles = $_POST['detalles'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];

    $stmt = $conn->prepare("INSERT INTO detalles_bus (salida, llegada, inicio, destino, detalles, latitud, longitud) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $salida, $llegada, $inicio, $destino, $detalles, $latitud, $longitud);

    if ($stmt->execute()) {
        $message = "Detalles del bus añadidos exitosamente";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Enviar Detalles del Bus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="css/miestilo.css">
    <link rel="icon" href="images/icono.png">
    <style>
        #map {
            height: 300px; /* Reduce the height for better mobile view */
            width: 100%;
        }
        .container {
            margin-top: 20px;
            padding: 0 10px; /* Reduce padding for mobile */
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
            width: 100%; /* Make buttons full-width on mobile */
            font-size: 1.2rem; /* Increase button text size */
        }

        /* Media Query for larger screens */
        @media (min-width: 768px) {
            #map {
                height: 500px; /* Restore original height for larger screens */
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
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Enviar Detalles del Bus</h2>
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <div class="form-container">
            <div class="map-section">
                <div id="map"></div>
            </div>
            <div class="form-section">
                <form id="busForm" method="post" action="submit_bus.php">
                    <div class="form-group">
                        <label for="salida">Hora de Salida</label>
                        <input type="time" class="form-control" id="salida" name="salida" required>
                    </div>
                    <div class="form-group">
                        <label for="llegada">Hora de Llegada</label>
                        <input type="time" class="form-control" id="llegada" name="llegada" required>
                    </div>
                    <div class="form-group">
                        <label for="inicio">Lugar de Inicio</label>
                        <input type="text" class="form-control" id="inicio" name="inicio" placeholder="Lugar de Inicio" required>
                    </div>
                    <div class="form-group">
                        <label for="destino">Lugar de Destino</label>
                        <input type="text" class="form-control" id="destino" name="destino" placeholder="Lugar de Destino" required>
                    </div>
                    <div class="form-group">
                        <label for="detalles">Detalles del Bus</label>
                        <textarea class="form-control" id="detalles" name="detalles" rows="3" placeholder="Detalles del Bus" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="latitud">Latitud</label>
                        <input type="text" class="form-control" id="latitud" name="latitud" placeholder="Latitud" required>
                    </div>
                    <div class="form-group">
                        <label for="longitud">Longitud</label>
                        <input type="text" class="form-control" id="longitud" name="longitud" placeholder="Longitud" required>
                    </div>
                </form>
            </div>
        </div>
        <div class="btn-container">
            <button type="submit" form="busForm" class="btn btn-primary">Enviar</button>
            <a href="index.php" class="btn btn-secondary">Volver al inicio</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([13.613748, -89.006737], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker;

        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng, { draggable: true }).addTo(map);
            document.getElementById('latitud').value = e.latlng.lat;
            document.getElementById('longitud').value = e.latlng.lng;

            marker.on('dragend', function (e) {
                var latLng = marker.getLatLng();
                document.getElementById('latitud').value = latLng.lat;
                document.getElementById('longitud').value = latLng.lng;
            });
        });
    </script>
</body>
</html>
