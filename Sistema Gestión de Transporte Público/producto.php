<?php
include 'db_config.php';

$id = $_GET['id'];
$query = "SELECT * FROM productos WHERE id = $id";
$result = $conn->query($query);
$producto = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['nombre']); ?></title>
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

    .product-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    p {
        font-size: 16px;
        margin: 10px 0;
    }

    .button-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    button {
        background-color: #9e42a5;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #8c3b97;
    }

    label {
        font-size: 16px;
        margin-right: 10px;
        color: #7b3390;
    }

    input[type="number"] {
        padding: 5px;
        font-size: 16px;
        border: 1px solid #6a2b8a;
        border-radius: 4px;
    }

    /* Media Queries for responsiveness */

    @media (max-width: 768px) {
        .product-container {
            padding: 10px;
        }

        h1 {
            font-size: 24px;
            margin: 10px 0;
        }

        p {
            font-size: 14px;
        }

        button {
            padding: 8px 16px;
            font-size: 14px;
        }

        label {
            font-size: 14px;
        }

        input[type="number"] {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .product-container {
            margin: 10px;
            padding: 5px;
        }

        h1 {
            font-size: 20px;
            margin: 10px 0;
        }

        p {
            font-size: 12px;
        }

        button {
            padding: 6px 12px;
            font-size: 12px;
        }

        label {
            font-size: 12px;
        }

        input[type="number"] {
            font-size: 12px;
        }
    }
    </style>
</head>
<body>
    <div class="product-container">
        <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
        <img src="images/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
        <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
        <p>Precio: $<?php echo htmlspecialchars($producto['precio']); ?></p>
        <form action="carrito.php" method="post" class="button-container">
            <input type="hidden" name="producto_id" value="<?php echo htmlspecialchars($producto['id']); ?>">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="<?php echo htmlspecialchars($producto['stock']); ?>">
            <button type="submit">Agregar al Carrito</button>
        </form>
    </div>
</body>
</html>
