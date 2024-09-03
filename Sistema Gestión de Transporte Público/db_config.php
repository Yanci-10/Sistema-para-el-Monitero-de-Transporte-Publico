<?php
$servername = "localhost";
$username = "Yanci-10";
$password = "Jes_cecas";
$dbname = "Proyecto_2024";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
