<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mapa de Marcadores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Mapa de Marcadores</h2>
        <div id="map" style="height: 500px;"></div>
        <p class="text-center mt-4"><a href="index.php" class="btn btn-primary">Volver al inicio</a></p>
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

        <?php
        $result = $conn->query("SELECT latitud, longitud, descripcion FROM marcadores");
        while ($row = $result->fetch_assoc()) {
            echo "L.marker([" . $row['latitud'] . ", " . $row['longitud'] . "]).addTo(map).bindPopup('" . addslashes($row['descripcion']) . "');\n";
        }
        $conn->close();
        ?>
    </script>
</body>
</html>
