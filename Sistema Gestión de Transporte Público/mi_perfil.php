<?php
include 'db_config.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT nombre_completo, usuario, email FROM usuarios WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['usuario']); ?></title>
    <link rel="stylesheet" href="css/estilo_miperfil.css">
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="logo">F</div>
            <input type="text" placeholder="Buscar...">
        </div>
        <div class="header-right">
            <div class="icon">🔔</div>
            <div class="icon">💬</div>
            <div class="icon">👤</div>
        </div>
    </div>

    <div class="cover-photo">
        <!-- Foto de portada -->
    </div>

    <div class="profile-section">
        <div class="profile-pic">
            <img src="imagenes/logo.jpg" alt="Foto de perfil" class="profile-photo">
        </div>
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($user['nombre_completo']); ?></h2>
            <p><?php echo htmlspecialchars($user['usuario']); ?></p>
            <div class="nav-links">
                <a href="mi_perfil.php">Biografía</a>
                <a href="#">Información</a>
                <a href="#">Amigos</a>
                <a href="#">Fotos</a>
                <a href="#">Videos</a>
            </div>
        </div>
    </div>

    <div class="content-section">
        <div class="left-column">
            <section class="intro-section">
                <h2>Introducción</h2>
                <p>Trabaja en Eric Jones Records</p>
                <p>Vive en Calgary, Alberta</p>
                <p>De Los Angeles, California</p>
            </section>
            <div class="info-box">
                <p>Información adicional del usuario</p>
            </div>
        </div>
        <div class="right-column">
            <section class="posts-section">
                <div class="post-box">
                    <p>Escribe algo...</p>
                </div>
                <div class="post">
                    <h3>Nuevo Post</h3>
                    <p><?php echo htmlspecialchars($user['nombre_completo']); ?> actualizó su foto de portada.</p>
                </div>
                <div class="post">
                    <p>Publicación anterior del usuario</p>
                </div>
            </section>
        </div>
    </div>

    <footer>
        <p>&copy; Escuela Futurista Ultramagnus</p>
    </footer>
</body>
</html>
