<?php
include 'db_config.php';
session_start();

// Redireccionar al usuario si ya está logueado
if(isset($_SESSION['usuario'])){
    header("location: php/bienvenida.php");
    exit();
}

$message = '';
$error = '';

// Verificar si el formulario de inicio de sesión o registro fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Procesar el inicio de sesión
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Usar una declaración preparada para evitar inyecciones SQL
        $sql = "SELECT id, contrasena FROM usuarios WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['contrasena'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['email'] = $email;
                header("Location: mi_perfil.php");
                exit();
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "Usuario no encontrado";
        }

        $stmt->close();
    } elseif (isset($_POST['register'])) {
        // Procesar el registro
        $nombre_completo = $_POST['nombre_completo'];
        $email = $_POST['email'];
        $usuario = $_POST['usuario'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hasheando la contraseña

        // Usar una declaración preparada para evitar inyecciones SQL
        $sql = "INSERT INTO usuarios (nombre_completo, usuario, contrasena, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre_completo, $usuario, $password, $email);

        if ($stmt->execute()) {
            $message = "Registro exitoso";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilo_L_S.css">
</head>
<body>

<main>
<a href="index.html" class="boton__inicio">Inicio</a>

    <div class="contendor__todo">
        <div class="caja__trasera">
            <div class="caja__trasera-login">
                <h3>¿Ya tienes una cuenta?</h3>
                <p>Inicia sesion para acceder</p>
                <button id="btn__iniciar-sesion">Iniciar Sesion</button>
            </div>
            <div class="caja__trasera-register">
                <h3>¿Aun No Tienes una Cuenta?</h3>
                <p>Registrarme para Acceder</p>
                <button id="btn__registrarse">Registrarme</button>
            </div>
        </div>

        <!--Formulario de login y Registro-->
        <div class="contenedor__login-register">
            <!-- Mostrar mensajes de error o éxito -->
            <?php if($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!--Login-->
            <form action="" method="POST" class="formulario__login">
                <h2>Iniciar Sesion</h2>
                <input type="text" placeholder="Email" name="email" required>
                <input type="password" placeholder="Password" name="password" required>
                <button type="submit" name="login">Acceder</button>
            </form>

            <!--Registro-->
            <form action="" method="POST" class="formulario__register">
                <h2>Registrarme</h2>
                <input type="text" placeholder="Nombre Completo" name="nombre_completo" required>
                <input type="text" placeholder="Email" name="email" required>
                <input type="text" placeholder="Usuario" name="usuario" required>
                <input type="password" placeholder="Password" name="password" required>
                <button type="submit" name="register">Registrarme</button>
                
            </form>
        </div>
    </div>  
</main>

<script>
document.getElementById("btn__iniciar-sesion").addEventListener("click", iniciarSesion);
document.getElementById("btn__registrarse").addEventListener("click", register);
window.addEventListener("resize", anchoPagina);

//Declaracion de Variables
var contenedor_login_register = document.querySelector(".contenedor__login-register");
var formulario_login = document.querySelector(".formulario__login");
var formulario_register = document.querySelector(".formulario__register");
var caja_trasera_login = document.querySelector(".caja__trasera-login");
var caja_trasera_register = document.querySelector(".caja__trasera-register");

function anchoPagina () {
    if (window.innerWidth > 850) {
        caja_trasera_login.style.display = "block";
        caja_trasera_register.style.display = "block";
    } else {
        caja_trasera_register.style.display = "block";
        caja_trasera_register.style.opacity = "1";
        caja_trasera_login.style.display = "none";
        formulario_login.style.display = "block";
        formulario_register.style.display = "none";
        contenedor_login_register.style.left = "0px";
    }
}
anchoPagina();

function iniciarSesion() {
    if (window.innerWidth > 850) {
        formulario_register.style.display = "none";
        contenedor_login_register.style.left = "10px";
        formulario_login.style.display = "block";
        caja_trasera_register.style.opacity = "1";
        caja_trasera_login.style.opacity = "0";
    } else {
        formulario_register.style.display = "none";
        contenedor_login_register.style.left = "0px";
        formulario_login.style.display = "block";
        caja_trasera_register.style.display = "block";
        caja_trasera_login.style.display = "none";
    }
}

function register() { 
    if (window.innerWidth > 850) {
        formulario_register.style.display = "block";
        contenedor_login_register.style.left = "410px";
        formulario_login.style.display = "none";
        caja_trasera_register.style.opacity = "0";
        caja_trasera_login.style.opacity = "1";
    } else {
        formulario_register.style.display = "block";
        contenedor_login_register.style.left = "0px";
        formulario_login.style.display = "none";
        caja_trasera_register.style.display = "none";
        caja_trasera_login.style.display = "block";
        caja_trasera_login.style.opacity = "1";
    }
}
</script>

</body>
</html>
