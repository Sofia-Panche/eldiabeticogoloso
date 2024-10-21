<?php
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
$carrito = $_SESSION['carrito'];

$host = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = 'if0_37008929_diabetico_goloso';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conexion->prepare("SELECT * FROM productos");
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $productos = array_column($productos, null, 'id');

} catch (PDOException $e) {
    echo "Error en la conexión o consulta: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = intval($_POST['producto_id']);
    if (isset($productos[$producto_id])) {
        if (!isset($carrito[$producto_id])) {
            $carrito[$producto_id] = ['cantidad' => 0, 'porciones' => $productos[$producto_id]['porciones_default']];
        }
        $carrito[$producto_id]['cantidad'] += 1;
        $_SESSION['carrito'] = $carrito;
    }
}


/* IDENTIFICARSE*/

session_start();
if ($usuarioValido) {
    $_SESSION['usuario'] = $usuario['id'];
    echo "Sesión iniciada. ID de usuario: " . $_SESSION['usuario'];
    header("Location: oficialclientes.php");
    exit();
} 

$hostDB = '127.0.0.1';
$nombreDB = 'if0_37008929_diabetico_goloso';
$usuarioDB = 'root';
$contrasenyaDB = '';
$hostPDO = "mysql:host=$hostDB;dbname=$nombreDB;charset=utf8mb4";

$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
$password = isset($_REQUEST['contrasenya']) ? $_REQUEST['contrasenya'] : null;
$errores = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    if (empty($email) || empty($password)) {
        $errores[] = 'El email y la contraseña son obligatorios.';
    }

    if (count($errores) === 0) {
        try {
            $miPDO = new PDO($hostPDO, $usuarioDB, $contrasenyaDB);
            $miPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            
            $miConsulta = $miPDO->prepare('SELECT id, rol, activo, password FROM usuarios_db WHERE email = :email');
            $miConsulta->execute(['email' => $email]);
            $resultado = $miConsulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado === false) {
                $errores[] = 'El email no está registrado.';
            } elseif ((int)$resultado['activo'] !== 1) {
                $errores[] = 'Tu cuenta aún no está activa. ¿Has revisado tu bandeja de correo?';
            } else {
                if (password_verify($password, $resultado['password'])) {
                    session_start();
                    
                    $_SESSION['usuario_id'] = $resultado['id'];
                    $_SESSION['email'] = $email;
                    $_SESSION['rol'] = $resultado['rol'];

                    // Redirigir según el rol
                    if ($resultado['rol'] == 1) {
                        header('Location: oficialclientes.php'); 
                    } elseif ($resultado['rol'] == 2) {
                        header('Location: iniciooficial.php'); 
                    }
                    exit();
                } else {
                    $errores[] = 'El email o la contraseña es incorrecta.';
                }
            }
        } catch (PDOException $e) {
            $errores[] = 'Error al conectar con la base de datos: ' . $e->getMessage();
        }
    }
}



/* REGISTRO*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//======================================================================
// PROCESAR FORMULARIO 
//======================================================================

/**
 * Funciones para validar
 */
function validar_requerido(string $texto): bool {
    return !(trim($texto) == '');
}

function validar_email(string $texto): bool {
    return filter_var($texto, FILTER_VALIDATE_EMAIL);
}

//-----------------------------------------------------
// Variables
//-----------------------------------------------------
$errores = [];
$nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : ''; 
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';

$rol = 1; // Rol predeterminado para cliente
if ($email === 'luisardiabeticog@gmail.com' || $email === 'vanessacdiabeticog@gmail.com') {
    $rol = 2; // Rol administrador
}

// Comprobamos si llegan los datos por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //-----------------------------------------------------
    // Validaciones
    //-----------------------------------------------------
    if (!validar_requerido($nombre)) {
        $errores[] = 'El campo Nombre es obligatorio.';
    }

    if (!validar_requerido($email)) {
        $errores[] = 'El campo Email es obligatorio.';
    }

    if (!validar_email($email)) {
        $errores[] = 'El Email no tiene un formato válido.';
    }

    if (!validar_requerido($password)) {
        $errores[] = 'El campo Contraseña es obligatorio.';
    }

    //-----------------------------------------------------
    // Verificar si el email ya está registrado
    //-----------------------------------------------------
    if (count($errores) === 0) {
        try {
            // Conexión a la base de datos
            $hostDB = '127.0.0.1';
            $nombreDB = 'if0_37008929_diabetico_goloso';
            $usuarioDB = 'root';
            $contrasenyaDB = '';
            $hostPDO = "mysql:host=$hostDB;dbname=$nombreDB;charset=utf8mb4";
            
            $miPDO = new PDO($hostPDO, $usuarioDB, $contrasenyaDB);
            $miPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verificar si el email ya está registrado
            $miConsulta = $miPDO->prepare('SELECT COUNT(*) as length FROM usuarios_db WHERE email = :email');
            $miConsulta->execute(['email' => $email]);
            $resultado = $miConsulta->fetch();

            if ((int)$resultado['length'] > 0) {
                $errores[] = 'La dirección de email ya está registrada.';
            }
        } catch (PDOException $e) {
            $errores[] = 'Error en la conexión a la base de datos: ' . $e->getMessage();
        }
    }

    //-----------------------------------------------------
    // Crear cuenta si no hay errores
    //-----------------------------------------------------
    if (count($errores) === 0) {
        try {
            // Cifrar la contraseña
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(openssl_random_pseudo_bytes(16)); 

            // Preparar el INSERT en la base de datos
            $miNuevoRegistro = $miPDO->prepare('INSERT INTO usuarios_db (email, nombre, password, activo, rol, token) 
                                                VALUES (:email, :nombre, :password, :activo, :rol, :token)');
            // Ejecuta el nuevo registro
            $miNuevoRegistro->execute([
                'email' => $email,
                'nombre' => $nombre,
                'password' => $hashPassword,
                'activo' => 0,  // Deja la cuenta inactiva para que sea activada por correo
                'rol' => $rol,
                'token' => $token
            ]);

            // Envío de Email de activación (opcional)

            require 'PHPMailer/src/PHPMailer.php';
            require 'PHPMailer/src/SMTP.php';
            require 'PHPMailer/src/Exception.php';

            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor de correo
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'vanessacdiabeticog@gmail.com';
                $mail->Password = 'gggj iqga bowg frdp'; // Contraseña o contraseña de aplicación
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Remitente y destinatario
                $mail->setFrom('vanessacdiabeticog@gmail.com', 'El Diabetico Goloso');
                $mail->addAddress($email, 'Usuario');

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Activa tu cuenta';
                $mail->Body = "Hola!<br>Para activar tu cuenta, haz clic en el siguiente enlace:<br>
                <a href='http://eldiabeticogoloso.000.pe/verificar-cuenta.php?email=" . urlencode($email) . "&token=" . urlencode($token) . "'>Activar cuenta</a>";

                $mail->send();
                echo 'Correo de activación enviado correctamente.';
            } catch (Exception $e) {
                echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
            }

           
            header('Location: verificar-cuenta.php?registrado=1');
exit();
            exit();
        } catch (PDOException $e) {
            $errores[] = 'Error en la inserción en la base de datos: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Diabético Goloso</title>
    <link rel="website icon" type="png" href="img\logodiabe.png">
    <link rel="stylesheet" href="./css/oficialclientes.css">
    <script src="https://kit.fontawesome.com/89631d50fd.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
</head>
<body>
    <style>

.nav-img {
    width: 100%;
    height: auto;
    max-width: 70px;
    padding: a
  }
  
  .nav-img img {
    width: 100%;
    height: auto;
    object-fit: contain; 
  }

header .top-bar {
    background-color: #71e5f5;
    color: #000;
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 2rem;
}

header .top-bar .contact-info p {
    margin: 0;
}

header .top-bar .social-links a {
    width: 50px;
    height: 50px;
    border-radius: 10%;

    font-size: 28px;
    text-decoration: none;
    margin-left: 0.5rem;
    color: #f1f1f1;
}

header .top-bar .social-links img {
    width: 20px;
    height: 20px;
}

header .main-nav {
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header .main-nav .logo {
    height: 300px; 
    width: 300px;
}

header .main-nav nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
}

header .main-nav nav ul li {
    margin: 0 1rem;
}

header .main-nav nav ul li a {
    color: #000;
    text-decoration: none;
    font-size: 1rem;
}

header .main-nav nav ul li.icons a {
    margin: 0 0.5rem;
}

header .main-nav nav ul li.icons img {
    width: 20px;
    height: 20px;
}

main {
    padding: 2rem;
    text-align: center;
}

main {
    padding: 2rem;
    text-align: center;
}

.main-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;
    background-color: transparent; 
}
.nav-left, .nav-right {
    flex: 1;
}

.nav-left ul, .nav-right ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
}

.nav-left ul {
    justify-content: flex-start;
}

.nav-right ul {
    justify-content: flex-end;
}


.icons-container {
    flex: 1;
    display: flex;
    flex-wrap: nowraps;
    justify-content: flex-end;
}

.icons-container a {
    margin-left: 10px;
    text-decoration: none;
    color: #000303;
}
.social_icon{
    color: #f1f1f1;
    margin-bottom: 0.3rem;
}
.social_icon:hover{
    color: #4e4c4c;

}
.social_icon{
    margin-right: 15px;

}
.social_icon img{
    height: 28px;
}

    .bienvenida {
    position: relative;           
    color: #fff;
    text-align: center;
    padding: 20px 0;
    height: 300px;
    z-index: 1;                   
}

.bienvenida::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('./img/collagetortas.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    filter: brightness(0.);
    z-index: -1;                  
    opacity: 0.;                 
}
.bienvenida h2{
    color: #d3c4a4;
    justify-content: center;
    z-index: 2;                   
    animation: 2s infinite;
    text-shadow:0 0 20px #64c4d4;
    font-family: "Nunito Sans", sans-serif;
}
.bienvenida >h3 {
    font-size: 40px;
    color: #dee6e7;
    justify-content: center;
    z-index: 2;                   
    animation: 2s infinite;
    font-family: "Nunito Sans", sans-serif;
}
.bienvenida-content {
    max-width: 800px; 
    margin: 0 auto; 
}

.bienvenida h2, .bienvenida p {
    margin: 0; 
    padding: 20px; 
    
}

.bienvenida h2 {
    font-size: 40px;
    margin-top: 0;
}

.bienvenida p {
    font-size: 20px;
    margin: 1rem 0;
    color: #f1f1f1;
}
        .intento {
            padding: 0%;
            margin: 0%;
            background-color: #fff;
        }
  
        .coso-de-arriba {
            padding: 0;
            margin: 0;
            padding-top: 0%;
            color: #fff;
            background-color: #D29D2B;
            font-size: 30px;
            font-family: "Nunito Sans", sans-serif;
        }
  
        .pasos > h4 {
            font-family: "Nunito Sans", sans-serif;
            width: 70%;
            margin: 0 auto;
        }
  
        .gif {
            width: 20%;
            height: 40%;
            margin: 2% 5%;
            float: left;
        }
  
        .texto {
            font-size: 18px;
            line-height: 1.5;
            margin: 0 20px;
        }
  
        .coso-del-centro {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .boton-wasap {
            text-decoration: none;
            border: none;
            border-radius: 10px;
            background-color: #04a73a;
            color: #fff;
            font-family: "Nunito Sans", sans-serif;
            font-size: 20px;
            padding: 8px 10px; 
            transition: background-color 0.3s ease, transform 0.3s ease; 
            display: inline-block;
            cursor: pointer;             
        }

        .boton-wasap:hover {  
            transform: scale(1.05);
        }

        .boton-wasap:active {
            transform: scale(0.95);
        }
        .boton-detalles{
             text-decoration: none;
            border: none;
            border-radius: 10px;
            background-color: #71e5f5;
            color: #fff;
            font-family: "Nunito Sans", sans-serif;
            font-size: 15px;
            padding: 8px 10px; 
            transition: background-color 0.3s ease, transform 0.3s ease; 
            display: inline-block;
            cursor: pointer;  
        }
        .boton-detalles:hover{
            transform: scale(1.05);
        }
        .boton-detalles:active {
            transform: scale(0.95);
        }

        @media only screen and (max-width: 600px) {
            .coso-del-centro {
                flex-direction: column;
            }
            .gif {
                width: 30%; 
                height: auto;
                margin: 5px 0;
            }
            .texto {
                margin: 5px 0; 
            }
        }

        
.productos {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    margin-top: 2rem;
}

.producto {
    background-color: white;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin: 1rem;
    padding: 1rem;
    width: 300px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: left;
}

.producto img {
    width: 100%;
    border-radius: 5px;
}

.producto h3 {
    margin: 1rem 0 0.5rem;
}

.producto p {
    margin: 0.5rem 0;
    color: #555;
}

.producto form {
    text-align: center;
}

.producto button {
    font-family: "Nunito Sans", sans-serif;
    background-color: #71e5f5;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
}

.producto button:hover {
    background-color: #555;
}

.carrito {
    margin-top: 2rem;
    text-align: left;
}

.carrito h2 {
    margin-top: 0;
}

.footer{
    background-color:#D29D2B  ;
    display: flex;
    flex-direction: column;
}
.section_footer{
background-color: #bd9439;
padding: 50px 20px;
display: flex;
justify-content: center;
}
.pie_div{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    width: 100%;

}
.footer_titulo{
    color: black;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}
.social_icon{
    color: #fff;
    margin-bottom: 0.3rem;
}
.social_icon:hover{
    color: #4e4c4c;

}
.social_icon{
    margin-right: 15px;

}
.social_icon img{
    height: 28px;
}

.footer_copy{
    text-align: center;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 500;
    padding: 20px 0;
}
.footer_copy_links{
    color: #fff;
    font-weight:900 ;
}
.footer_copy_links:hover{
    color: #4e4c4c;
}
@media only screen and(max-width:850px) {
    .section_footer{
        width: 49%;
        margin-bottom: 1.5rem;

    }
}
@media only screen and(max-width:510px) {
    .section_footer{
        width: 100%;
        
        
    }
}

footer .top-bar .social-links a {
    width: 50px;
    height: 50px;
    border-radius: 10%;

    font-size: 28px;
    text-decoration: none;
    margin-left: 0.5rem;
    color: #f1f1f1;
}

footer.top-bar .social-links img {
    width: 20px;
    height: 20px;
}
.consultas{
    padding: 50px;
    padding-left: 15px;
    padding-bottom: 5px;
    padding-top: 5px;
    padding-right: 15px;
    color: #000;
}
.consultas:hover{
    padding: 50px;
    padding-left: 10px;
    padding-bottom: 5px;
    padding-top: 5px;
    padding-right: 10px;
    background-color: #D29D2B;
}
.consultas{
    text-decoration: none;
    color: #000303;
}
/*whatsapp*/
.whatsapp-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #25D366;
            color: white;
            padding: 15px;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            cursor: pointer;
            z-index: 1000;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dropdown {
            display: none;
            position: fixed;
            bottom: 80px;
            right: 20px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .dropdown a {
            display: block;
            padding: 10px;
            color: #000;
            text-decoration: none;
        }
        .dropdown a:hover {
            background-color: #73C883;
        }


        /* REGISTRO*/

.login-section {
    display: flex;
    justify-content: center;
    margin-top: 5%;
    margin-right: 7%;
    z-index: 2;    
}


.login-section .form-box{
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
z-index: 2;    
}

.formulario {
    width:35%;
    background-color: #fff;
    background-color:rgb(255 255 255 / 21%);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    z-index: 2;    
}

.input-box {
    margin-bottom: 20px;
}


label {
    display: block;
    margin-bottom: 5px;
    font-size: 14px;
    color: #555;
}
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 10px;
    border: none; /* Quita los bordes */
    border-bottom: 2px solid #555; /* Agrega una línea debajo */
    background-color: transparent; /* Sin fondo */
    outline: none; /* Quita el borde al hacer clic */
}

input[type="text"]:focus, input[type="password"]:focus {
    -bottom: 2px solid #3498db; /* Cambia el color de la línea al hacer foco */
}

input[type="submit"] {
width: 100%;
padding: 12px;
background-color: #D29D2B ;
color: #fff;
font-size: 1rem;
font-weight: bold;
border: none;
border-radius: 5px;
cursor: pointer;
transition: background-color 0.3s;
}


input[type="submit"]:hover {
    background-color:#ad8630;
}

.create-account {
    color:#000;
    text-align: center;
    margin-top: 20px;
    font-size: 20px;
}

.aaa {
    color: #000;
    text-decoration: none;
     font-size: 20px;
     
}

.aaa:hover {
    text-decoration: underline;
     
}

.errores {
    color: #ff6b6b;
    background-color: #ffe6e6;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}

/* Media queries para dispositivos móviles */
@media (max-width: 768px) {
    .login-section {
        justify-content: center;
        margin-right: 0;
    }

    .formulario {
        width: 100%;
        max-width: 400px;
        margin: 0 20px;
    }
}

.text-item {
display: flex;
flex-direction: column;
align-items: center; /* Centra horizontalmente */
justify-content: center; /* Centra verticalmente */
text-align: center; /* Alinea el texto horizontalmente */
height: auto; 
}
.text-item h1 {
color: #333;
z-index: 2;
animation: 2s infinite;
text-shadow: 0 0 20px #D29D2B;
font-family: "Nunito Sans", sans-serif;
font-size: 50px;
line-height: 1;
}


/*INICIO DE SESION*/
.login-section {
    display: flex;
    justify-content: center;
    margin-top: 5%;
    margin-right: 7%;
}
.login-section .form-box{
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;

}
.formulario {
    width:40%;
    background-color: #fff;
    background-color:rgb(255 255 255 / 21%);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
}
.input-box {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-size: 14px;
    color: #555;
}
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 10px;
    border: none; /* Quita los bordes */
    border-bottom: 2px solid #555; /* Agrega una línea debajo */
    background-color: transparent; /* Sin fondo */
    outline: none; /* Quita el borde al hacer clic */
}

input[type="text"]:focus, input[type="password"]:focus {
    -bottom: 2px solid #3498db; /* Cambia el color de la línea al hacer foco */
}

input[type="submit"] {
width: 100%;
padding: 12px;
background-color: #D29D2B ;
color: #fff;
font-size: 1rem;
font-weight: bold;
border: none;
border-radius: 5px;
cursor: pointer;
transition: background-color 0.3s;
}
input[type="submit"]:hover {
    background-color:#ad8630;
}

.create-account {
    color:#000;
    text-align: center;
    margin-top: 20px;
    font-size: 20px;
}
.aaa {
    color: #000;
    text-decoration: none;
     font-size: 20px;  
}
.aaa:hover {
    text-decoration: underline;  
}
.errores {
    color: #ff6b6b;
    background-color: #ffe6e6;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}

/* Media queries para dispositivos móviles */
@media (max-width: 768px) {
    .login-section {
        justify-content: center;
        margin-right: 0;
    }

    .formulario {
        width: 100%;
        max-width: 400px;
        margin: 0 20px;
    }
}


.btn{
width: 100%;
height: 45px;
outline: none;
border: none;
border-radius: 4px;
cursor: pointer;
background: #D29D2B;
font-size: 16px;
color: #fff;
box-shadow: rgba(0,0,0,0.4);

}
.btn:hover{
background: #ad8630;
}

.text-item {
display: flex;
flex-direction: column;
align-items: center; /* Centra horizontalmente */
justify-content: center; /* Centra verticalmente */
text-align: center; /* Alinea el texto horizontalmente */
height: auto; 
}
.text-item h1 {
color: #333;
z-index: 2;
animation: 2s infinite;
text-shadow: 0 0 20px #D29D2B;
font-family: "Nunito Sans", sans-serif;
font-size: 50px;
line-height: 1;
}


/* VENTANA EMERGENTE*/


/* Botón para abrir la ventana emergente */

.btn-open{
    padding: 50px;
    padding-left: 15px;
    padding-bottom: 5px;
    padding-top: 5px;
    padding-right: 15px;
    color: #000;
}
.btn-open:hover{
    padding: 50px;
    padding-left: 10px;
    padding-bottom: 5px;
    padding-top: 5px;
    padding-right: 10px;
    background-color: #D29D2B;
}


.btn-open{
    text-decoration: none;
    color: #000303;
}


/* El fondo de la ventana emergente */
.modal {
    display: none; /* Inicialmente oculta */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

/* Contenido de la ventana */
.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    width: 300px;
    position: relative;
}

/* Botón para cerrar la ventana */
.close {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    cursor: pointer;
}

/* Mostrar la ventana cuando esté activa */
input[type="checkbox"]:checked ~ .modal {
    display: flex;
}

/* Estilos de los formularios */
form {
    display: flex;
    flex-direction: column;
}

form input {
    margin-bottom: 10px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

form button {
    padding: 10px;
    background-color: #007BFF;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}

/* Inicialmente, el formulario de registro está oculto */
#form-register {
    display: none;
}

/* Botón de cambio de formulario */
.switch-btn {
    background-color: transparent;
    border: none;
    color: #007BFF;
    cursor: pointer;
    margin-top: 10px;
    text-decoration: underline;
}
.login-section {
    display: flex;
    justify-content: center;
    margin-top: 5%;
    margin-right: 7%;
}


.login-section .form-box{
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;

}

.formulario {
    width:40%;
    background-color: #fff;
    background-color:rgb(255 255 255 / 21%);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
}

.input-box {
    margin-bottom: 20px;
}


label {
    display: block;
    margin-bottom: 5px;
    font-size: 14px;
    color: #555;
}
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 10px;
    border: none; /* Quita los bordes */
    border-bottom: 2px solid #555; /* Agrega una línea debajo */
    background-color: transparent; /* Sin fondo */
    outline: none; /* Quita el borde al hacer clic */
}

input[type="text"]:focus, input[type="password"]:focus {
    -bottom: 2px solid #3498db; /* Cambia el color de la línea al hacer foco */
}

input[type="submit"] {
width: 100%;
padding: 12px;
background-color: #D29D2B ;
color: #fff;
font-size: 1rem;
font-weight: bold;
border: none;
border-radius: 5px;
cursor: pointer;
transition: background-color 0.3s;
}


input[type="submit"]:hover {
    background-color:#ad8630;
}

.create-account {
    color:#000;
    text-align: center;
    margin-top: 20px;
    font-size: 20px;
}

.aaa {
    color: #000;
    text-decoration: none;
     font-size: 20px;
     
}

.aaa:hover {
    text-decoration: underline;
     
}

.errores {
    color: #ff6b6b;
    background-color: #ffe6e6;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}

/* Media queries para dispositivos móviles */
@media (max-width: 768px) {
    .login-section {
        justify-content: center;
        margin-right: 0;
    }

    .formulario {
        width: 100%;
        max-width: 400px;
        margin: 0 20px;
    }
}
.btn{
width: 100%;
height: 45px;
outline: none;
border: none;
border-radius: 4px;
cursor: pointer;
background: #D29D2B;
font-size: 16px;
color: #fff;
box-shadow: rgba(0,0,0,0.4);

}
.btn:hover{
background: #ad8630;
}

.text-item {
display: flex;
flex-direction: column;
align-items: center; /* Centra horizontalmente */
justify-content: center; /* Centra verticalmente */
text-align: center; /* Alinea el texto horizontalmente */
height: auto; 
}
.text-item h1 {
color: #333;
z-index: 2;
animation: 2s infinite;
text-shadow: 0 0 20px #D29D2B;
font-family: "Nunito Sans", sans-serif;
font-size: 50px;
line-height: 1;
}

    </style>
   <header>
       <div class="top-bar">
            <div class="contact-info">
                 <div class="nav-img" >
               <img src="img/logodiabe.png" alt="">
            </div>
            </div>
            <div class="social-links">
                <a href="https://www.facebook.com/eldiabeticogoloso?mibextid=ZbWKwL"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.instagram.com/eldiabeticogoloso/?igsh=dnM0eWJ0NHNqcWds"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="main-nav">
            <nav>
            
    <!-- Checkbox para activar el modal -->
    <input type="checkbox" id="modal-toggle" hidden>

    <!-- Botón para abrir la ventana emergente -->
    <label for="modal-toggle" class="btn-open">Iniciar Sesión</label>

    <!-- Ventana emergente -->
    <div class="modal">
        <div class="modal-content">
            <!-- Botón para cerrar la ventana -->
            <label for="modal-toggle" class="close">&times;</label>


         <div class="container">
            <div class="text-item">
                <h1>Inisiar sesion</h1>
            </div>
       


             <div id="form-login">
              <form class="form" action="" method="post">
                <div class="input-box">
                    <label>E-mail</label>
                    <input type="text" required name="email" placeholder="" value="<?php echo htmlspecialchars($email); ?>">
                </div>

            <div class="input-box">
                 <label for="">Contraseña</label>
                 <input type="password" required name="contrasenya" placeholder="">
                    <i class="bx bxs-lock"></i>
                </div>
                    <div class="input-box">
                    <button class="btn" type="submit">iniciar sesion</button>
                </div>
                <button class="switch-btn" onclick="switchForm('register')">¿No tienes cuenta? Regístrate aquí</button>

            </form>

         
            </div>
        </div>









    <div id="form-register">
            <form class="form" action="" method="post">
                <div class="input-box">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
                </div>

                <div class="input-box">
                    <label>E-mail</label>
                    <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>

                <div class="input-box">
                    <label>Contraseña</label>
                    <input type="password" name="password" value="<?php echo htmlspecialchars($password); ?>">
                </div>

                <input type="submit" class="btn" value="registrarse">
            </form>

            <button class="switch-btn" onclick="switchForm('login')">¿Ya tienes cuenta? Inicia sesión aquí</button>
           
        </div>

 </div>
 </div>

    <!-- Script para cambiar entre los formularios -->
    <script>
        function switchForm(form) {
            // Cambiar entre formularios
            if (form === 'register') {
                document.getElementById('form-login').style.display = 'none';
                document.getElementById('form-register').style.display = 'block';
            } else {
                document.getElementById('form-login').style.display = 'block';
                document.getElementById('form-register').style.display = 'none';
            }
        }
    </script>


         
            <a class="consultas" href="./vista/1nosotros.php">Sobre nosotros</a>
            </nav>
            <div class="icons-container">
            <a href="./vista/carrito2.php"><i class="fa-solid fa-cart-shopping"></i> (<?= array_sum(array_column($carrito, 'cantidad')) ?>)</a>
        </div>
    </header>

    <!-- Mostrar errores si existen -->
    <?php if (count($errores) > 0): ?>
        <ul class="errores">
            <?php foreach ($errores as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?> 

    <main>
       <section class="bienvenida">
            <div class="bienvenida-content">
                <h2>Bienvenido a El Diabético Goloso</h2>
                <h3>Lo mejor sin remordimientos</h3>
            </div>
        </section>
        <main class="intento">
        <div class="coso-de-arriba"><h2>Disfruta tu experiencia🤗</h2></div>
         <section class="coso-del-centro">
            <div class="pasos">
            <img class="gif" src="./img/calendario.gif" alt="">
                <h4 class="texto">1 - Pide tu torta con 3 dias de anticipacion.</h4>
            </div>
            <div class="pasos">
                <img class="gif" src="./img/confirmacion.gif"alt="">
                <h4 class="texto">2 - Confírmanos en un mensaje fecha y hora de entrega y tu direccion.</h4>
            </div>
            <div class="pasos">
                <img  class="gif" src="./img/transferencia.gif" alt="">
                <h4 class="texto" >3 - No olvides que los medios de pago son daviplata y nequi. </h4>
            </div>
            </section>
        </main>
        <section class="productos">
            <?php foreach ($productos as $producto) : ?>
                <div class="producto">
                    <img src="<?= htmlspecialchars($producto['imagen_url']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="producto-imagen">
                    <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                    <p><?= htmlspecialchars($producto['descripcion']) ?></p>
                    <form action="" method="post">
                        <input type="hidden" name="producto_id" value="<?= htmlspecialchars($producto['id']) ?>">
                        <a href="producto.php?id=<?= htmlspecialchars($producto['id']) ?>" class="boton-detalles"><i class="fa-solid fa-cart-shopping"></i></a>
                    </form>
                </div>
            <?php endforeach; ?>
        </section>
        
        <?php if (isset($_GET['status']) && $_GET['status'] === 'added'): ?>
            <p style="color: green;">Producto añadido al carrito correctamente.</p>
        <?php endif; ?>
    </main>

    <div id="cart-modal" style="display:none;">
        <div class="modal-content">
            <span id="close-modal">&times;</span>
            <h2>Tu Carrito</h2>
            <?php if (empty($carrito)): ?>
                <p>El carrito está vacío.</p>
            <?php else: ?>
                <ul>
                    <?php
                    $total = 0;
                    foreach ($carrito as $id => $item) {
                        $stmt = $conexion->prepare("SELECT nombre, precio_10 FROM productos WHERE id = ?");
                        $stmt->bindParam(1, $id, PDO::PARAM_INT);
                        $stmt->execute();
                        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($producto) {
                            $precio = $producto['precio_10'];
                            $total += $precio * $item['cantidad'];
                            echo "<li>" . htmlspecialchars($producto['nombre']) . " - " . $item['cantidad'] . " unidades de " . $item['porciones'] . " porciones - $" . ($precio * $item['cantidad']) . "</li>";
                        }
                    }
                    ?>
                </ul>
                <h2>Total: $<?= $total; ?></h2>
            <?php endif; ?>
        </div>
    </div>
      <!-- Whatsapp -->
    <a href="#" class="whatsapp-button" id="whatsapp-button">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
    
    <div class="dropdown" id="dropdown">
        <?php
        // Array de contactos
        $contactos = [
            ['nombre' => 'Sede Bogotá', 'numero' => '3107623278'], // Primer WhatsApp
            ['nombre' => 'Sede Villavicencio', 'numero' => '3178531932'], // Segundo WhatsApp
        ];

        foreach ($contactos as $contacto) {
            echo '<a href="https://wa.me/' . $contacto['numero'] . '" target="_blank">' . $contacto['nombre'] . '</a>';
        }
        ?>
    </div>

    <script>
        const whatsappButton = document.getElementById('whatsapp-button');
        const dropdown = document.getElementById('dropdown');

        whatsappButton.onclick = (event) => {
            event.preventDefault(); // Evitar el comportamiento por defecto del enlace
            dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
        };

        // Cerrar el dropdown si se hace clic fuera de él
        window.onclick = function(event) {
            if (!event.target.matches('.whatsapp-button') && !event.target.matches('.dropdown a')) {
                dropdown.style.display = 'none';
            }
        };
    </script>

    <footer class="footer">
        <section class="section_footer">
            <div class="pie_div">
                <article class="articulo">
                    <h1 class="footer_titulo">Sedes</h1>
                    <p><strong>Sede Villavicencio</strong><br>
                        Cra 9 #37-04 <br>
                        Cel: 317 853 1932 <br>
                        luisardiabeticog@gmail.com
                    </p>
                    <br>
                    <p><strong>Sede Bogotá</strong><br>
                        Calle 127 bis#88-45 <br>
                        Cel: 310 762 3278 <br>
                        vanessacdiabeticog@gmail.com
                    </p>
                </article>
                <article class="articulo">
                    <h1 class="footer_titulo">Síguenos</h1>
                    <div class="top-bar">
                        <div class="contact-info">
                            <div class="social-links">
                                <a href="https://www.facebook.com/eldiabeticogoloso?mibextid=ZbWKwL"><i class="fa-brands fa-facebook"></i></a>
                                <a href="https://www.instagram.com/eldiabeticogoloso/?igsh=dnM0eWJ0NHNqcWds"><i class="fa-brands fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h1 class="footer_titulo">Metodos de pago</h1>
                            <div class="social-links" >
                            <img src="img\daviplata.png" alt="" width="60px";><img src="img\nequi.jpg" alt=""  width="60px">
                    </div>
                </article>
            </div> 
        </section> 
        <div class="footer_copy">
            <p class="footer_copy-text" id="copyright">
                &COPY; <samp id="año"></samp> Todos los derechos reservados. <br>Hecho por:
                <a href="SoftCuchau.php" target="_blank" class="footer_copy_links">SoftCuchau</a>
            </p>
        </div>
    </footer> 
</body>
</html>