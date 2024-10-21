<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito = $_SESSION['carrito'];

include './modelo/conexion.php'; 

$baseDatos = new BaseDatos();

$productos = [];
$total = 0;

if (!empty($carrito)) {
    $ids = implode(',', array_fill(0, count($carrito), '?'));
    
    $stmt = $baseDatos->prepararConsulta("
        SELECT 
            p.id, p.nombre, p.imagen_url, p.precio_5, p.precio_10, p.precio_15, p.precio_20
        FROM productos p
        WHERE p.id IN ($ids)
    ");

    $stmt->bind_param(str_repeat('i', count($carrito)), ...array_keys($carrito)); 
    $stmt->execute();
    
    $result = $stmt->get_result();
    $productosEnCarrito = [];
    while ($row = $result->fetch_assoc()) {
        $productosEnCarrito[] = $row;
    }

    $sabores_crema_ids = [];
    $sabores_relleno_ids = [];

    foreach ($carrito as $producto_id => $info) {
        $sabores_crema_ids[] = $info['sabor_crema'];
        $sabores_relleno_ids[] = $info['sabor_relleno'];
    }

    if (!empty($sabores_crema_ids)) {
        $sabor_crema_ids_placeholder = implode(',', array_fill(0, count($sabores_crema_ids), '?'));
        $stmt_crema = $baseDatos->prepararConsulta("SELECT id, nombre FROM sabores_crema WHERE id IN ($sabor_crema_ids_placeholder)");
        $stmt_crema->bind_param(str_repeat('i', count($sabores_crema_ids)), ...$sabores_crema_ids);
        $stmt_crema->execute();
        
        $result_crema = $stmt_crema->get_result();
        $sabores_crema = [];
        while ($row = $result_crema->fetch_assoc()) {
            $sabores_crema[] = $row;
        }

        $sabores_crema_map = [];
        foreach ($sabores_crema as $sabor) {
            $sabores_crema_map[$sabor['id']] = $sabor['nombre'];
        }
    }

    if (!empty($sabores_relleno_ids)) {
        $sabor_relleno_ids_placeholder = implode(',', array_fill(0, count($sabores_relleno_ids), '?'));
        $stmt_relleno = $baseDatos->prepararConsulta("SELECT id, nombre FROM sabores_relleno WHERE id IN ($sabor_relleno_ids_placeholder)");
        $stmt_relleno->bind_param(str_repeat('i', count($sabores_relleno_ids)), ...$sabores_relleno_ids);
        $stmt_relleno->execute();
        
        $result_relleno = $stmt_relleno->get_result();
        $sabores_relleno = [];
        while ($row = $result_relleno->fetch_assoc()) {
            $sabores_relleno[] = $row;
        }

        $sabores_relleno_map = [];
        foreach ($sabores_relleno as $sabor) {
            $sabores_relleno_map[$sabor['id']] = $sabor['nombre'];
        }
    }

    foreach ($productosEnCarrito as $producto) {
        $producto_id = $producto['id'];
        $porciones = $carrito[$producto_id]['porciones'] ?? 10; 
        $cantidad = $carrito[$producto_id]['cantidad'] ?? 1; 

        switch ($porciones) {
            case 5:
                $precio = $producto['precio_5'];
                break;
            case 10:
                $precio = $producto['precio_10'];
                break;
            case 15:
                $precio = $producto['precio_15'];
                break;
            case 20:
                $precio = $producto['precio_20'];
                break;
            default:
                $precio = $producto['precio_10']; 
        }

        $productos[$producto_id] = [
            'nombre' => $producto['nombre'],
            'imagen' => $producto['imagen_url'],
            'precio' => $precio,
            'cantidad' => $cantidad,
            'porciones' => $porciones,
            'sabor_relleno' => $sabores_relleno_map[$carrito[$producto_id]['sabor_relleno']] ?? 'No disponible', 
            'sabor_crema' => $sabores_crema_map[$carrito[$producto_id]['sabor_crema']] ?? 'No disponible',   
        ];

        $total += $precio * $cantidad;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['vaciar_carrito'])) {
        $_SESSION['carrito'] = []; 
        header("Location: carrito.php");
        exit;
    }
    if (isset($_POST['finalizar_compra'])) {
        echo "<script>enviarWhatsapp();</script>"; 
        exit;
    }
}

$isLoggedIn = isset($_SESSION['usuario']); 

$stmt = $baseDatos->prepararConsulta("SELECT sede, numero FROM sedes");
$stmt->execute();
$result_sedes = $stmt->get_result();
$sedes = [];
while ($row = $result_sedes->fetch_assoc()) {
    $sedes[] = $row;
}
$stmt->close();
?>   

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="website icon" type="png" href="img/logodiabe.png">
    <link rel="stylesheet" href="oficialclientes.css">
    <script src="https://kit.fontawesome.com/89631d50fd.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,200;1,200&display=swap" rel="stylesheet">
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
               <a href="oficialclientes.php"><i class="fa-solid fa-house"></i>        
            </nav>
            <div class="icons-container">
                <a href="perfil.php"><i class="fa-solid fa-user"></i></a>
                <a href="carrito.php"><i class="fa-solid fa-cart-shopping"></i> (<?= array_sum(array_column($carrito, 'cantidad')) ?>)</a>
        </div>
    </header>
    <style>
 body{
            margin:0;
        }
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

        .carrito {
            margin: 2rem auto;
            padding: 2rem;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            max-width: 80%;
        }

        .carrito h2 {
            text-align: center;
            color: #71e5f5;
        }

        .carrito-contenido {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .carrito-producto {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f4f4f4;
        }

        .carrito-producto img {
            width: 100px;
            height: 100px;
            border-radius: 5px;
        }

        .carrito-detalles {
            flex: 1;
        }

        .carrito-detalles h3 {
            margin: 0;
            color: #333;
        }

        .carrito-detalles p {
            color: #000;
            font-size: 1.17em;
      }

        #comprar-todo{
        background-color: #71e5f5;
        width: 100%;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size:20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        }
        #vaciar-carrito-form button {
            margin-top:20px;
            margin-bottom: 18px;
            background-color: #df1818;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        #comprar-todo:hover {
            background-color: #31b0d5;
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



.carrito h1 {
    text-align: center;
    color: #71e5f5;
}

#comprar-todo {
            background-color: #71e5f5;
            width: 100%;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #vaciar-carrito-form button {
            margin-top: 20px;
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #vaciar-carrito-form button:hover {
            background-color: #d32f2f;
        }

        #comprar-todo.disabled {
            display: none;
        }
        .carrito {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: #f9f9f9;
    }

   
    #modal-sedes button {
        background-color: #007BFF; 
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 15px;
        margin: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s, transform 0.2s;
    }

    
    #modal-sedes button:hover {
        background-color: #0056b3; 
        transform: translateY(-2px);
    }

    
    
    
</style>
</head>

<main>
    <div class="carrito">
        <h2>Carrito de Compras</h2>
        <?php if (empty($productos)): ?>
            <p>No hay productos en el carrito.</p>
        <?php else: ?>
            <div class="carrito-contenido">
                <?php foreach ($productos as $producto): ?>
                    <div class="carrito-producto" data-id="<?php echo isset($productos['id']) ? $productos['id'] : ''; ?>">
                        <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <div class="carrito-detalles">
                            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p>Porciones: <?php echo htmlspecialchars($producto['porciones']); ?></p>
                            <p>Sabor Relleno: <?php echo htmlspecialchars($producto['sabor_relleno']); ?></p>
                            <p>Sabor Crema: <?php echo htmlspecialchars($producto['sabor_crema']); ?></p>
                            <p>
                                Cantidad: 
                                <button class="btn-decrementar">-</button>
                                <span class="cantidad"><?php echo htmlspecialchars($producto['cantidad']); ?></span>
                                <button class="btn-incrementar">+</button>
                            </p>
                            <p>Precio: $<span class="precio"><?php echo htmlspecialchars(number_format($producto['precio'], 2)); ?></span></p>
                            <button class="btn-eliminar">Eliminar</button>
                        </div>
                    </div>
                <?php endforeach; ?>
                <h3>Total: $<span id="total"><?php echo htmlspecialchars(number_format($total, 2)); ?></span></h3>
            </div>
            <form id="vaciar-carrito-form" method="POST">
                <button type="submit" name="vaciar_carrito">Vaciar Carrito</button>
            </form>
            <form id="comprar-form" onsubmit="mostrarSedes(); return false;">
                <button id="comprar-todo" type="submit">Finalizar Compra</button>
            </form>
        <?php endif; ?>

        <div id="modal-sedes" style="display: none;">
            <h3>Seleccione la sede para completar su compra:</h3>
            <?php foreach ($sedes as $sede): ?>
                <button onclick="enviarWhatsapp('<?= htmlspecialchars($sede['numero']); ?>')">
                    <?= htmlspecialchars($sede['sede']); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</main>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productos = <?php echo json_encode($productos); ?>;
        const totalElement = document.getElementById('total');

        function actualizarTotal() {
            let total = 0;
            document.querySelectorAll('.carrito-producto').forEach(producto => {
                const precio = parseFloat(producto.querySelector('.precio').textContent);
                const cantidad = parseInt(producto.querySelector('.cantidad').textContent);
                total += precio * cantidad;
            });
            totalElement.textContent = total.toFixed(2);
        }

        // Incrementar cantidad
        document.querySelectorAll('.btn-incrementar').forEach(button => {
            button.addEventListener('click', function () {
                const productoDiv = this.closest('.carrito-producto');
                const cantidadElement = productoDiv.querySelector('.cantidad');
                let cantidad = parseInt(cantidadElement.textContent);
                cantidad++;
                cantidadElement.textContent = cantidad;
                actualizarTotal();
            });
        });

        // Decrementar cantidad
        document.querySelectorAll('.btn-decrementar').forEach(button => {
            button.addEventListener('click', function () {
                const productoDiv = this.closest('.carrito-producto');
                const cantidadElement = productoDiv.querySelector('.cantidad');
                let cantidad = parseInt(cantidadElement.textContent);
                if (cantidad > 1) {
                    cantidad--;
                    cantidadElement.textContent = cantidad;
                    actualizarTotal();
                }
            });
        });

        // Eliminar producto
        document.querySelectorAll('.btn-eliminar').forEach(button => {
            button.addEventListener('click', function () {
                const productoDiv = this.closest('.carrito-producto');
                productoDiv.remove();
                actualizarTotal();
            });
        });
    });

    function mostrarSedes() {
        document.getElementById('modal-sedes').style.display = 'block';
    }

    function enviarWhatsapp(numero) {
    let mensaje = "Hola, me gustaría comprar los siguientes productos:\n\n";

    <?php foreach ($productos as $producto): ?>
        mensaje += "Producto: <?php echo htmlspecialchars($producto['nombre']); ?>\n";
        mensaje += "Cantidad: <?php echo htmlspecialchars($producto['cantidad']); ?>\n";
        mensaje += "Porciones: <?php echo htmlspecialchars($producto['porciones']); ?>\n";
        mensaje += "Precio: $<?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?>\n";
        mensaje += "Sabor Relleno: <?php echo htmlspecialchars($producto['sabor_relleno']); ?>\n";
        mensaje += "Sabor Crema: <?php echo htmlspecialchars($producto['sabor_crema']); ?>\n\n";
    <?php endforeach; ?>

    mensaje += "Total: $<?php echo number_format($total, 2); ?>\n";

    let url = `https://wa.me/${numero}?text=` + encodeURIComponent(mensaje);
    window.open(url, "_blank");
}

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