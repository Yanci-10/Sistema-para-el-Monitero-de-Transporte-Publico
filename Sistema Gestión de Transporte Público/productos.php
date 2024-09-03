<?php
include 'db_config.php';

$query = "SELECT * FROM productos";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link rel="stylesheet" href="css/estilo_tienda.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h1 {
        color: #b450b4;
        text-align: center;
        margin: 20px 0;
    }

    .productos {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        padding: 0 10px;
    }

    .producto {
        border: 1px solid #ccc;
        background-color: #fff;
        width: 30%;
        margin: 10px;
        padding: 15px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        border-radius: 8px;
    }

    .producto img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .producto h2 {
        font-size: 18px;
        margin: 10px 0;
        color: #9e42a5;
    }

    .producto p {
        font-size: 14px;
        margin: 10px 0;
        color: #7b3390;
    }

    .producto a {
        display: inline-block;
        padding: 10px 20px;
        background-color: #b450b4;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
    }

    .producto a:hover {
        background-color: #9e42a5;
    }

    /* Media Queries for responsiveness */

    @media (max-width: 768px) {
        .producto {
            width: 45%;
        }

        .producto h2 {
            font-size: 16px;
        }

        .producto p {
            font-size: 12px;
        }

        .producto a {
            font-size: 12px;
            padding: 8px 16px;
        }
    }

    @media (max-width: 480px) {
        .producto {
            width: 100%;
            margin: 5px 0;
        }

        .producto h2 {
            font-size: 14px;
        }

        .producto p {
            font-size: 10px;
        }

        .producto a {
            font-size: 10px;
            padding: 6px 12px;
        }
    }
    </style>
</head>
<body>
    <h1>Tienda</h1>
    <div class="productos">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="producto">
                <img src="images/<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                <h2><?php echo htmlspecialchars($row['nombre']); ?></h2>
                <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
                <p>Precio: $<?php echo htmlspecialchars($row['precio']); ?></p>
                <a href="producto.php?id=<?php echo htmlspecialchars($row['id']); ?>">Ver Detalles</a>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
