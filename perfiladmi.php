<?php
session_start();

include './modelo/conexion.php'; 


if (!isset($_SESSION['email'])) {
    die("No se ha iniciado sesión. Por favor, inicia sesión.");
}

$email = $_SESSION['email'];

$db = new BaseDatos(); 

// Consulta con mysqli
$sql = "SELECT * FROM usuarios_db WHERE email = ?";
$stmt = $db->prepararConsulta($sql);

// Prepara la consulta
if ($stmt) {
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica si se encontró un usuario
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();  // Asignamos el usuario a la variable
    } else {
        $usuario = null;  // No se encontró el usuario
    }
} else {
    die("Error en la consulta a la base de datos");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $foto = $_FILES['foto'];
    $nombre_foto = basename($foto['name']);
    $tipo_foto = $foto['type'];
    $tamano_foto = $foto['size'];
    $tmp_foto = $foto['tmp_name'];

    if ($tipo_foto == 'image/jpeg' || $tipo_foto == 'image/png') {
        $ruta_foto = 'img/perfil/' . $nombre_foto;

        if (move_uploaded_file($tmp_foto, $ruta_foto)) {
            $sql = "UPDATE usuarios_db SET foto = ? WHERE email = ?";
            $stmt = $db->prepararConsulta($sql);
            
            if ($stmt) {
                $stmt->bind_param('ss', $ruta_foto, $email);
                $stmt->execute();
            }
        } else {
            echo "Error al subir la foto";
        }
    } else {
        echo "Formato de archivo no válido";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
     <link rel="website icon" type="png" href="logodiabe.png">
    <link rel="stylesheet" href="./css/perfil.css">
     <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<style>
body {
    font-family: "Nunito Sans", sans-serif;
    background-color: #f1f1f1;
    margin: 0;
    padding: 0;
    line-height: 1.6; /* Mejor legibilidad */
    font-size: 100%; /* Usamos un valor relativo */
}
.nav-img {
  width: 100%;
  height: auto;
  max-width: 70px;
  padding: 0;
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
  color: #000;
}

header .main-nav nav ul li.icons img {
  width: 20px;
  height: 20px;
}

.top-bar {
  background-color: #71e5f5;
  color: #000;
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 2rem;
}

.top-bar .social-links a {
  font-size: 28px;
  text-decoration: none;
  margin-left: 0.5rem;
  color: #f1f1f1;
}

.fa-solid, .fas {
  font-weight: 900;
  color: #000;
}

.main-nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #fff;
  margin-bottom: 2%;
  padding: 1%;
  align-items: center;
}

.fa-solid, .fas {
  font-weight: 900;
  color: #000;
}

.icons-container {
  display: flex;
  gap: 10px;
}

.icons-container a {
  color: #000;
  font-size: 20px;
}

main {
    padding: 2rem;
    text-align: center;
}

.perfil {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 1.5rem;
    width: 90%;
    max-width: 600px; /* Limitar ancho máximo */
    margin: 0 auto;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.perfil img {
    width: 100%;
    max-width: 200px;
    border-radius: 5px;
}

.perfil form {
    margin-top: 1rem;
}

.perfil form input[type="file"] {
    margin-bottom: 1rem;
}

.perfil form button[type="submit"] {
    background-color: #71e5f5;
    color: #fff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem; /* Escalado de botón */
}

.perfil p {
    margin: 1rem 0;
    font-size: 1.2rem; /* Tamaño del texto adaptable */
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

/* Footer */

.footer{
    background-color:#D29D2B ;
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

footer .top-barr .social-links a {
    width: 50px;
    height: 50px;
    border-radius: 10%;
    font-size: 28px;
    text-decoration: none;
    margin-left: 0.5rem;
    color: #f1f1f1;
}

footer.top-barr .social-links img {
    width: 20px;
    height: 20px;
}

@media only screen and (max-width: 850px) {
    .perfil {
        width: 80%;
    }

    .section_footer {
        padding: 1rem;
    }

    .pie_div {
        flex-direction: column;
    }

    .articulo {
        max-width: 100%;
        text-align: center;
    }

    header nav ul {
        flex-direction: column;
        align-items: center;
    }

    header nav ul li {
        margin-bottom: 1rem;
    }
}

@media only screen and (max-width: 510px) {
    .perfil {
        width: 100%;
        padding: 1rem;
    }

    .footer_titulo {
        font-size: 1.2rem;
    }

    .footer_copy {
        font-size: 0.9rem;
    }

    header nav ul li a {
        font-size: 1rem;
    }
}

</style>
<body>

   <header>
            <div class="top-bar">
                 <div class="contact-info">
                      <div class="nav-img" >
                      <img src="img\logodiabe.png" alt="">
                 </div>
                 </div>
                 <div class="social-links">
                     <a href="https://www.facebook.com/eldiabeticogoloso?mibextid=ZbWKwL"><i class="fa-brands fa-facebook"></i></a>
                     <a href="https://www.instagram.com/eldiabeticogoloso/?igsh=dnM0eWJ0NHNqcWds"><i class="fa-brands fa-instagram"></i></a>
                 </div>
             </div>
         <div class="main-nav">
             <nav>
                    <a href="iniciooficial.php"><i class="fa-solid fa-house"></i></a>         
             </nav>
         </div>
        </header>
  
    <main>
    <section class="perfil">
        <h2>Perfil de <?= $usuario ? htmlspecialchars($usuario['nombre']) : "Usuario no encontrado" ?></h2>
        
        <?php if ($usuario && !empty($usuario['foto'])): ?>
            <img src="<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil">
        <?php else: ?>
            <p>No se ha subido una foto de perfil.</p>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="foto" accept="image/jpeg, image/png, image/jpg">
            <button type="submit">Subir foto de perfil</button>
        </form>
        
        <p>Email: <?= $usuario ? htmlspecialchars($usuario['email']) : "No disponible" ?></p>
        <br>
              <a class="consultas" href="index.php">cerrar sesion</a>
    </section>
    </main>

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
                    <div class="top-barr">
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