<?php
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
            // Incluir el archivo de conexión
            require './modelo/conexion.php';

            // Crear una instancia de la clase BaseDatos
            $bd = new BaseDatos();

            // Usar la conexión para preparar y ejecutar consultas
            // Preparar la consulta
            $miConsulta = $bd->prepararConsulta('SELECT COUNT(*) as length FROM usuarios_db WHERE email = ?');
            
            if ($miConsulta === false) {
                die('Error en la preparación de la consulta: ' . $bd->conexion->error);
            }

            // Vincular parámetros y ejecutar
            $miConsulta->bind_param('s', $email);
            $miConsulta->execute();
            $resultado = $miConsulta->get_result()->fetch_assoc();

            if ((int)$resultado['length'] > 0) {
                $errores[] = 'La dirección de email ya está registrada.';
            }
        } catch (mysqli_sql_exception $e) {
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
            $miNuevoRegistro = $bd->prepararConsulta('INSERT INTO usuarios_db (email, nombre, password, activo, rol, token) 
                                                    VALUES (?, ?, ?, ?, ?, ?)');
            // Ejecuta el nuevo registro
            if ($miNuevoRegistro === false) {
                die('Error en la preparación de la consulta: ' . $bd->conexion->error);
            }

            // Vincular parámetros y ejecutar
            $activo = 0; // Deja la cuenta inactiva para que sea activada por correo
            $miNuevoRegistro->bind_param('sssisi', $email, $nombre, $hashPassword, $activo, $rol, $token);
            $miNuevoRegistro->execute();

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
                <a href='http://eldiabeticogoloso.000.pe/controlador/verificar-cuenta.php?email=" . urlencode($email) . "&token=" . urlencode($token) . "'>Activar cuenta</a>";

                $mail->send();
                echo 'Correo de activación enviado correctamente.';
            } catch (Exception $e) {
                echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
            }

            header('Location: ./controlador/verificar-cuenta.php?registrado=1');
            exit();
        } catch (mysqli_sql_exception $e) {
            $errores[] = 'Error en la inserción en la base de datos: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="website icon" type="png" href="logodiabe.png">
    <title>Registro</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {   
            background-image: url('./img/fondo.png');
            font-family: "poppins", sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            text-align: center;
            margin-top: 20px;
        }

        .login-section {
            display: flex;
            justify-content: center;
            margin-top: 5%;
            margin-right: 7%;
        }

        .login-section .form-box {
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .formulario {
            width: 40%;
            background-color: rgb(255 255 255 / 21%);
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
            border-bottom: 2px solid #3498db; /* Cambia el color de la línea al hacer foco */
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #D29D2B;
            color: #fff;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #ad8630;
        }

        .create-account {
            color: #000;
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
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Registro de Usuario</h1>
    </header>

    <section class="login-section">
    <div class="login-section">
    <div class="form-box">
        <form action="" method="post" class="formulario">
            <?php
            // Mostrar errores si los hay
            if (count($errores) > 0) {
                echo '<div class="errores">' . implode('<br>', $errores) . '</div>';
            }
            ?>
            <div class="input-box">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
            </div>
            <div class="input-box">
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="input-box">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password">
            </div>
            <input type="submit" value="Registrarme">
            <div class="create-account">
                <p>¿Ya tienes cuenta? <a class="aaa" href="./identificarse.php">Identifícate</a></p>
            </div>
        </form>
    </div>
</div>
    </section>
</body>
</html>
