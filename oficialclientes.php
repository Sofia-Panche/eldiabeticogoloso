<?php
session_start();


if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
$carrito = $_SESSION['carrito'];

include './modelo/conexion.php'; 

try {
 
    $bd = new BaseDatos();

   
    $stmt = $bd->prepararConsulta("SELECT * FROM productos");
    $stmt->execute();

   
    $productos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); 

   
    $productos = array_column($productos, null, 'id');

} catch (mysqli_sql_exception $e) {
    echo "Error en la consulta: " . $e->getMessage();
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


$bd->cerrarConexion();
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
    background-color: #bd9439 ;
    display: flex;
    flex-direction: column;
}
.section_footer{
background-color: #D29D2B;
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
                <a class="consultas" href="index.php">cerrar sesion</a>
               <a class="consultas" href="./vista/1nosotros.php">Sobre nosotros</a>
            </nav>
            <div class="icons-container">
                <a href="perfil.php"><i class="fa-solid fa-user"></i></a>
                <a href="carrito.php"><i class="fa-solid fa-cart-shopping"></i> (<?= array_sum(array_column($carrito, 'cantidad')) ?>)</a>
        </div>
    </header>

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
                        <a href="productoclien.php?id=<?= htmlspecialchars($producto['id']) ?>" class="boton-detalles"><i class="fa-solid fa-cart-shopping"></i></a>
                    </form>
                </div>
            <?php endforeach; ?>
        </section>
        
        <?php if (isset($_GET['status']) && $_GET['status'] === 'added'): ?>
            <p style="color: green;">Producto añadido al carrito correctamente.</p>
        <?php endif; ?>
    </main>
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